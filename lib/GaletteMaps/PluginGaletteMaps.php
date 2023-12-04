<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Members GPS coordinates
 *
 * PHP version 5
 *
 * Copyright © 2012-2021 The Galette Team
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
 * @copyright 2012-2021 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7.4dev - 2012-10-04
 */

namespace GaletteMaps;

use Galette\Core\Login;
use Galette\Entity\Adherent;
use Galette\Core\GalettePlugin;

/**
 * Members GPS coordinates
 *
 * @category  Plugins
 * @name      Coordinates
 * @package   GaletteMaps
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2012-2021 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7.4dev - 2012-10-04
 */

class PluginGaletteMaps extends GalettePlugin
{
    /**
     * Extra menus entries
     *
     * @return array|array[]
     */
    public static function getMenusContents(): array
    {
        /** @var Login $login */
        global $login;
        $menus = [];

        if ($login->isLogged() && !$login->isSuperAdmin()) {
            $menus['myaccount'] = [
                'items' => [
                    [
                        'label' => _T('My localization', 'maps'),
                        'route' => [
                            'name' => 'maps_mymap', //or maps_localize_member
                        ]
                    ],
                ]
            ];
        }

        return $menus;
    }

    /**
     * Extra public menus entries
     *
     * @return array|array[]
     */
    public static function getPublicMenusItemsList(): array
    {
        return [
            [
                'label' => _T("Maps", "maps"),
                'route' => [
                    'name' => 'maps_map'
                ],
                'icon' => 'map'
            ]
        ];
    }

    /**
     * Get dashboards contents
     *
     * @return array|array[]
     */
    public static function getDashboardsContents(): array
    {
        /** @var Login $login */
        global $login;

        if ($login->isSuperAdmin()) {
            return [];
        }

        return [
            [
                'label' => _T("My localization", "maps"),
                'route' => [
                    'name' => 'maps_localize_member',
                    'args' => ["id" => $login->id]
                ],
                'icon' => 'map'
            ]
        ];
    }

    /**
     * Get actions contents
     *
     * @param Adherent $member Member instance
     *
     * @return array|array[]
     */
    public static function getListActionsContents(Adherent $member): array
    {
        return [
            [
                'label' => _T("Geolocalize", "maps"),
                'title' => str_replace(
                    '%membername',
                    $member->sname,
                    _T("Geolocalize %membername", "maps")
                ),
                'route' => [
                    'name' => 'maps_localize_member',
                    'args' => ['id' => $member->id]
                ],
                'icon' => 'map marker alternate grey'
            ],
        ];
    }

    /**
     * Get detailed actions contents
     *
     * @param Adherent $member Memebr instance
     *
     * @return array|array[]
     */
    public static function getDetailedActionsContents(Adherent $member): array
    {
        return static::getListActionsContents($member);
    }

    /**
     * Get batch actions contents
     *
     * @return array|array[]
     */
    public static function getBatchActionsContents(): array
    {
        return [];
    }
}
