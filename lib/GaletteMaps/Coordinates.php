<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Members GPS coordinates
 *
 * PHP version 5
 *
 * Copyright © 2012-2013 The Galette Team
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
 * @copyright 2012-2013 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7.4dev - 2012-10-04
 */

namespace GaletteMaps;

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use Galette\Repository\Members as Members;

/**
 * Members GPS coordinates
 *
 * @category  Plugins
 * @name      Towns
 * @package   GaletteMaps
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2012-2013 The Galette Team
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
            $select = new \Zend_Db_Select($zdb->db);
            $select->from($this->getTableName())->where(self::PK . ' = ?', $id);
            $res = $select->query(\Zend_Db::FETCH_ASSOC)->fetchAll();
            if ( count($res) > 0 ) {
                return $res[0];
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve members coordinates for "' .
                $id  . '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            Analog::log(
                'Query was: ' . $select->__toString() . ' ' . $e->__toString(),
                Analog::ERROR
            );
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
            $select = new \Zend_Db_Select($zdb->db);
            $select->from(
                array(
                    'c' => $this->getTableName()
                )
            )->join(
                array(
                    'a' => PREFIX_DB . Adherent::TABLE
                ),
                'a.' . self::PK . '=' . 'c.' . self::PK
            )->where('activite_adh=true');

            if ( !$login->isAdmin()
                && !$login->isStaff()
                && !$login->isSuperAdmin()
            ) {
                //limit query to public profiles
                $select->where(
                    'date_echeance > ? OR bool_exempt_adh = true',
                    date('Y-m-d')
                )->where(
                    'bool_display_info = ?', true
                );
                if ( $login->isLogged() ) {
                    $select->orWhere(
                        'a.' . Adherent::PK . ' = ' . $login->id
                    );
                }
            }

            $rs = $select->query()->fetchAll();

            $res = array();

            foreach ( $rs as $r ) {
                $a = new Adherent($r);
                $m = array(
                    'id_adh'    => $a->id,
                    'lat'       => $r->latitude,
                    'lng'       => $r->longitude,
                    'name'      => $a->sname,
                    'nickname'  => $a->nickname
                );
                if ( $a->isCompany() ) {
                    $m['company'] = $a->company_name;
                }
                $res[] = $m;
            }

            return $res;
        } catch ( \Exception $e) {
            Analog::log(
                'Unable to retrieve members coordinates list "' .
                '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            Analog::log(
                'Query was: ' . $select->__toString() . ' ' . $e->__toString(),
                Analog::ERROR
            );
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
            if ( count($coords) === 0 ) {
                //cordinates does not exists yet
                $res = $zdb->db->insert(
                    $this->getTableName(),
                    array(
                        self::PK    => $id,
                        'latitude'  => $latitude,
                        'longitude' => $longitude
                    )
                );
            } else {
                //coordinates already exists, just update
                $res = $zdb->db->update(
                    $this->getTableName(),
                    array(
                        'latitude'  => $latitude,
                        'longitude' => $longitude
                    ),
                    self::PK . '=' . $id
                );
            }
            return ($res > 0);
        } catch ( \Exception $e ) {
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
            $del = $zdb->db->delete(
                $this->getTableName(),
                self::PK . '=' . $id
            );
            return ($del > 0);
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to set coordinatates for member ' .
                $id_adh . ' | ' . $e->getMessage(),
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
        return PREFIX_DB . MAPS_PREFIX  . self::TABLE;
    }
}
?>
