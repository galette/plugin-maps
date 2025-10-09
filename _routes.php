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

use GaletteMaps\Controllers\MapsController;

//Constants and classes from plugin
require_once $module['root'] . '/_config.inc.php';

$check_js_middleware = function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler) use ($container) {
    //check if JS has been generated
    if (!file_exists(__DIR__ . '/webroot/maps-main.bundle.min.js')) {
        $container->get('flash')->addMessageNow(
            'error_detected',
            _T('Javascript libraries has not been built!', 'maps')
        );
    }
    return $handler->handle($request);
};

/*$app->addMiddleware(function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler) use ($container) {
    //check if JS has been generated
    if (!file_exists(__DIR__ . '/webroot/maps-main.bundle.min.js')) {
        $container->get('flash')->addMessageNow(
            'error_detected',
            _T('Javascript libraries has not been built!', 'maps')
        );
    }
    return $handler->handle($request);
});*/

$app->get(
    '/localize-member/{id:\d+}',
    [MapsController::class, 'localizeMember']
)->setName('maps_localize_member')->add($authenticate)->add($check_js_middleware);

//member self localization
$app->get(
    '/localize-me',
    [MapsController::class, 'localizeMember']
)->setName('maps_mymap')->add($authenticate)->add($check_js_middleware);

//global map page
$app->get(
    '/map',
    [MapsController::class, 'map']
)->setName('maps_map')->add($check_js_middleware)->add(\Galette\Middleware\PublicPages::class);

$app->post(
    '/i-live-here[/{id:\d+}]',
    [MapsController::class, 'ILiveHere']
)->setName('maps_ilivehere')->add($authenticate)->add($check_js_middleware);
