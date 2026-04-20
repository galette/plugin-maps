<?php

/**
 * Copyright © 2003-2026 The Galette Team
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

use DI\Attribute\Inject;
use Galette\Core\Db;
use Galette\Core\Login;
use Galette\Core\Plugins\InstallableInterface;
use Galette\Core\Plugins\MenuProviderInterface;
use Galette\Core\Plugins\DashboardProviderInterface;
use Galette\Core\Plugins\MemberActionProviderInterface;
use Galette\Entity\Adherent;
use Galette\Core\GalettePlugin;

/**
 * Members GPS coordinates
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 */

class PluginGaletteMaps extends GalettePlugin implements InstallableInterface, MenuProviderInterface, DashboardProviderInterface, MemberActionProviderInterface
{
    #[Inject]
    private readonly Db $zdb; //@phpstan-ignore-line injected from DI

    /**
     * Extra menus entries
     *
     * @return array<string|int, string|array<string,mixed>>
     */
    public function getMenus(): array
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
    public function getPublicMenus(): array
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
    public function getMyDashboards(): array
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
    public function getDashboards(): array
    {
        return [];
    }

    /**
     * Get actions contents
     *
     * @param Adherent $member Member instance
     *
     * @return array<int, string|array<string,mixed>>
     */
    public function getListActions(Adherent $member): array
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
    public function getDetailedActions(Adherent $member): array
    {
        return $this->getListActions($member);
    }

    /**
     * Get batch actions contents
     *
     * @return array<int, string|array<string,mixed>>
     */
    public function getBatchActions(): array
    {
        return [];
    }

    /**
     * Is the plugin fully installed (including database, extra configuration, etc.)?
     */
    public function isInstalled(): bool
    {
        return $this->zdb->tableExists(MAPS_PREFIX . Coordinates::TABLE);
    }
}
