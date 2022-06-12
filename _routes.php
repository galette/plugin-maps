<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Maps routes
 *
 * PHP version 5
 *
 * Copyright © 2015-2020 The Galette Team
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
 * @copyright 2015-2020 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @link      http://galette.tuxfamily.org
 * @since     0.9dev 2015-10-28
 */

use GaletteMaps\Controllers\MapsController;
use Analog\Analog;
use Galette\Entity\Adherent;
use GaletteMaps\NominatimTowns;
use GaletteMaps\Coordinates;

//Constants and classes from plugin
require_once $module['root'] . '/_config.inc.php';

$this->add(function ($request, $response, $next) {
    //check if JS has been generated
    if (!file_exists(__DIR__ . '/webroot/maps-main.bundle.min.js')) {
        $this->flash->addMessageNow(
            'error_detected',
            _T('Javascript libraries has not been built!', 'maps')
        );
    }
    return $next($request, $response);
});

$this->get(
    '/localize-member/{id:\d+}',
    [MapsController::class, 'localizeMember']
)->setName('maps_localize_member')->add($authenticate);

//member self localization
$this->get(
    '/localize-me',
    [MapsController::class, 'localizeMember']
)->setName('maps_mymap')->add($authenticate);

//global map page
$this->get(
    '/map',
    [MapsController::class, 'map']
)->setName('maps_map');

$this->post(
    '/i-live-here[/{id:\d+}]',
    [MapsController::class, 'ILiveHere']
)->setName('maps_ilivehere')->add($authenticate);
