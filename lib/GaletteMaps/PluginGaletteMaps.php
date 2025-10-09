<?php

/**
 * Copyright © 2003-2025 The Galette Team
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

use Galette\Core\Login;
use Galette\Entity\Adherent;
use Galette\Core\GalettePlugin;

/**
 * Members GPS coordinates
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 */

class PluginGaletteMaps extends GalettePlugin
{
    /**
     * Extra menus entries
     *
     * @return array<string|int, string|array<string,mixed>>
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
     * @return array<int, string|array<string,mixed>>
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
     * Get current logged-in user dashboards contents
     *
     * @return array<int, string|array<string,mixed>>
     */
    public static function getMyDashboardsContents(): array
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
     * Get dashboards contents
     *
     * @return array<int, string|array<string,mixed>>
     */
    public static function getDashboardsContents(): array
    {
        return [];
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
     * @param Adherent $member Member instance
     *
     * @return array<int, string|array<string,mixed>>
     */
    public static function getDetailedActionsContents(Adherent $member): array
    {
        return static::getListActionsContents($member);
    }

    /**
     * Get batch actions contents
     *
     * @return array<int, string|array<string,mixed>>
     */
    public static function getBatchActionsContents(): array
    {
        return [];
    }
}
