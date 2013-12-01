<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Towns GPS coordinates
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
 * @since     Available since 0.7.4dev - 2012-10-03
 */

namespace GaletteMaps;

use Analog\Analog as Analog;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Expression;

/**
 * Towns GPS coordinates
 *
 * @category  Plugins
 * @name      Towns
 * @package   GaletteMaps
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2012-2013 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7.4dev - 2012-10-03
 */

class Towns
{
    const TABLE = 'towns';
    const PK = 'id';

    /**
     * Search a town by its name
     *
     * @param string $town Town name
     *
     * @return array
     */
    public function search($town)
    {
        global $zdb;

        try {
            $select = $zdb->select($this->getTableName());

            $rtown = preg_replace(
                array('/-/', '/_/', '/ /'),
                '',
                $town
            );

            $select->columns(
                array('full_name_nd_ro', 'latitude', 'longitude')
            )->where(
                array(
                    new PredicateSet(
                        array(
                            new Expression(
                                'LOWER(sort_name_ro) LIKE ?',
                                '%' . strtolower($rtown) . '%'
                            ),
                            new Expression(
                                'LOWER(full_name_ro) LIKE ?',
                                '%' . strtolower($town) . '%'
                            ),
                            new Expression(
                                'LOWER(full_name_nd_ro) LIKE ?',
                                '%' . strtolower($town) . '%'
                            ),
                            new Expression(
                                'LOWER(sort_name_rg) LIKE ?',
                                '%' . strtolower($rtown) . '%'
                            ),
                            new Expression(
                                'LOWER(full_name_nd_rg) LIKE ?',
                                '%' . strtolower($town) . '%'
                            )
                        ),
                        PredicateSet::OP_OR
                    )
                )
            );

            $results = $zdb->execute($select);
            return $results;
        } catch (\Exception $e) {
            Analog::log(
                'Unable to find town "' . $town  . '". | ' . $e->getMessage(),
                Analog::WARNING
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

