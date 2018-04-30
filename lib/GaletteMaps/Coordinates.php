<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Members GPS coordinates
 *
 * PHP version 5
 *
 * Copyright © 2012-2014 The Galette Team
 *
 * This file is part of Galette (http://galette.tuxfamily.org).
 *
 * Galette is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Galette is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Plugins
 * @package   GaletteMaps
 *
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2012-2014 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7.4dev - 2012-10-04
 */

namespace GaletteMaps;

use Analog\Analog;
use Galette\Entity\Adherent;
use Galette\Repository\Members;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Predicate\Expression as PredicateExpression;

/**
 * Members GPS coordinates
 *
 * @category  Plugins
 * @name      Coordinates
 * @package   GaletteMaps
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2012-2014 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7.4dev - 2012-10-04
 */

class Coordinates
{
    const TABLE = 'coordinates';
    const PK = 'id_adh';

    /**
     * Retrieve member coordinates
     *
     * @param int $id Member id
     *
     * @return array
     */
    public function getCoords($id)
    {
        global $zdb;

        try {
            $select = $zdb->select($this->getTableName());
            $select->where(self::PK . ' = ' . $id);
            $results = $zdb->execute($select);

            if ($results->count() > 0) {
                return $results->current();
            } else {
                return array();
            }
        } catch (\Exception $e) {
            if ($e->getCode() == '42S02') {
                Analog::log(
                    'Maps coordinates table does not exists',
                    Analog::WARNING
                );
                return false;
            } else {
                Analog::log(
                    'Unable to retrieve members coordinates for "' .
                    $id  . '". | ' . $e->getMessage(),
                    Analog::WARNING
                );
            }
            return false;
        }
    }

    /**
     * Returns list of all know coordinates, filtered on publically
     * visible profile for non admins and non staff
     *
     * @return array
     */
    public function listCoords()
    {
        global $zdb, $login;

        try {
            $select = $zdb->select($this->getTableName(), 'c');
            $select->join(
                array(
                    'a' => PREFIX_DB . Adherent::TABLE
                ),
                'a.' . self::PK . '=' . 'c.' . self::PK
            )->where->equalTo(
                'activite_adh',
                new Expression('true')
            );

            if (!$login->isAdmin()
                && !$login->isStaff()
                && !$login->isSuperAdmin()
            ) {
                //limit query to public up to date profiles
                $select->where(
                    array(
                        new PredicateSet(
                            array(
                                new Operator(
                                    'date_echeance',
                                    '>=',
                                    date('Y-m-d')
                                ),
                                new Operator(
                                    'bool_exempt_adh',
                                    '=',
                                    new Expression('true')
                                )
                            ),
                            PredicateSet::OP_OR
                        ),
                        new PredicateSet(
                            array(
                                new Operator(
                                    'bool_display_info',
                                    '=',
                                    new Expression('true')
                                )
                            )
                        )
                    )
                );

                if ($login->isLogged() && !$login->isSuperAdmin()) {
                    $select->where(
                        new PredicateSet(
                            array(
                                new Operator(
                                    'a.' . Adherent::PK,
                                    '=',
                                    $login->id
                                )
                            )
                        ),
                        PredicateSet::OP_OR
                    );
                }
            }

            $results = $zdb->execute($select);

            $res = array();
            foreach ($results as $r) {
                $a = new Adherent($zdb, $r);
                $m = array(
                    'id_adh'    => $a->id,
                    'lat'       => $r->latitude,
                    'lng'       => $r->longitude,
                    'name'      => $a->sname,
                    'nickname'  => $a->nickname
                );
                if ($a->isCompany()) {
                    $m['company'] = $a->company_name;
                }
                $res[] = $m;
            }

            return $res;
        } catch (\Exception $e) {
            if ($e->getCode() == '42S02') {
                Analog::log(
                    'Maps coordinates table does not exists',
                    Analog::WARNING
                );
            } else {
                Analog::log(
                    'Unable to retrieve members coordinates list "' .
                    '". | ' . $e->getMessage(),
                    Analog::WARNING
                );
            }
            return false;
        }
    }

    /**
     * Set member coordinates
     *
     * @param int   $id        Member id
     * @param float $latitude  Latitude
     * @param float $longitude Longitude
     *
     * @return boolean
     */
    public function setCoords($id, $latitude, $longitude)
    {
        global $zdb;

        try {
            $res = null;
            $coords = $this->getCoords($id);
            if (count($coords) === 0) {
                //cordinates does not exists yet
                $insert = $zdb->insert($this->getTableName());
                $insert->values(
                    array(
                        self::PK    => $id,
                        'latitude'  => $latitude,
                        'longitude' => $longitude
                    )
                );
                $results = $zdb->execute($insert);
            } else {
                //coordinates already exists, just update
                $update = $zdb->update($this->getTableName());
                $update->set(
                    array(
                        'latitude'  => $latitude,
                        'longitude' => $longitude
                    )
                )->where(
                    self::PK . '=' . $id
                );
                $results = $zdb->execute($update);
            }
            return ($results->count() > 0);
        } catch (\Exception $e) {
            Analog::log(
                'Unable to set coordinatates for member ' .
                $id_adh . ' | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Remove member coordinates
     *
     * @param int $id Member id
     *
     * @return boolean
     */
    public function removeCoords($id)
    {
        global $zdb;

        try {
            $delete = $zdb->delete($this->getTableName());
            $delete->where(self::PK . '=' . $id);
            $del = $zdb->execute($delete);
            return ($del->count() > 0);
        } catch (\Exception $e) {
            Analog::log(
                'Unable to set coordinates for member ' .
                $id . ' | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Get table's name
     *
     * @return string
     */
    protected function getTableName()
    {
        return MAPS_PREFIX  . self::TABLE;
    }
}
