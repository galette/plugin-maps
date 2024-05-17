<?php

/**
 * Copyright © 2003-2024 The Galette Team
 *
 * This file is part of Galette (https://galette.eu).
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
 */

declare(strict_types=1);

namespace GaletteMaps;

use Analog\Analog;
use ArrayObject;
use Galette\Core\Db;
use Galette\Entity\Adherent;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Predicate\Operator;

/**
 * Members GPS coordinates
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 */

class Coordinates
{
    public const TABLE = 'coordinates';
    public const PK = 'id_adh';

    /**
     * Retrieve member coordinates
     *
     * @param int $id Member id
     *
     * @return array<string>|ArrayObject<string, int|string>
     */
    public function getCoords(int $id): array|ArrayObject
    {
        /** @var Db $zdb */
        global $zdb;

        try {
            $select = $zdb->select($this->getTableName());
            $select->where([self::PK => $id]);
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
            } else {
                Analog::log(
                    'Unable to retrieve members coordinates for "' .
                    $id  . '". | ' . $e->getMessage(),
                    Analog::WARNING
                );
            }
            throw $e;
        }
    }

    /**
     * Returns list of all know coordinates, filtered on publicly
     * visible profile for non admins and non staff
     *
     * @return array<int, array<string,mixed>>
     */
    public function listCoords(): array
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

            if (
                !$login->isAdmin()
                && !$login->isStaff()
                && !$login->isSuperAdmin()
            ) {
                //limit query to public up-to-date profiles
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
            throw $e;
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
    public function setCoords(int $id, float $latitude, float $longitude): bool
    {
        global $zdb;

        try {
            $coords = $this->getCoords($id);
            if (count($coords) === 0) {
                //coordinates does not exist yet
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
                    [self::PK => $id]
                );
                $results = $zdb->execute($update);
            }
            return ($results->count() > 0);
        } catch (\Exception $e) {
            Analog::log(
                'Unable to set coordinates | ' . $e->getMessage(),
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
    public function removeCoords(int $id): bool
    {
        global $zdb;

        try {
            $delete = $zdb->delete($this->getTableName());
            $delete->where([self::PK => $id]);
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
    protected function getTableName(): string
    {
        return MAPS_PREFIX  . self::TABLE;
    }
}
