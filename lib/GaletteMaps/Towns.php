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
            $select = new \Zend_Db_Select($zdb->db);

            $rtown = preg_replace(
                array('/-/', '/_/', '/ /'),
                '',
                $town
            );

            $select->from(
                $this->getTableName(),
                array('full_name_nd_ro', 'latitude', 'longitude')
            )->where(
                'LOWER(sort_name_ro) LIKE ?',
                '%' . strtolower($rtown) . '%'
            )->orWhere(
                'LOWER(full_name_ro) LIKE ?',
                '%' . strtolower($town) . '%'
            )->orWhere(
                'LOWER(full_name_nd_ro) LIKE ?',
                '%' . strtolower($town) . '%'
            )->orWhere(
                'LOWER(sort_name_rg) LIKE ?',
                '%' . strtolower($rtown) . '%'
            )->orWhere(
                'LOWER(full_name_rg) LIKE ?',
                '%' . strtolower($town) . '%'
            )->orWhere(
                'LOWER(full_name_nd_rg) LIKE ?',
                '%' . strtolower($town) . '%'
            );
            return $select->query(\Zend_Db::FETCH_ASSOC)->fetchAll();
        } catch (\Exception $e) {
            Analog::log(
                'Unable to find town "' . $town  . '". | ' . $e->getMessage(),
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
