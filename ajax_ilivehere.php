<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * I live here
 * Make possible to search and select a member
 *
 * This page can't be loaded directly, only via ajax.
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
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2012-2013 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7.4dev - 2012-10-04
 */

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use GaletteMaps\Coordinates as Coordinates;

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';
require_once '_config.inc.php';

if ( !$login->isLogged() /*|| !$login->isAdmin() && !$login->isStaff()*/ ) {
    Analog::log(
        'Trying to display ajax_ilivehere.php without appropriate permissions',
        Analog::INFO
    );
    die();
}

$member = null;

if ( isset($_POST['id_adh'])
    && ($login->isSuperAdmin() || $login->isAdmin() || $login->isStaff())
) {
    $member = new Adherent((int)$_POST['id_adh']);
} else if ( $login->isSuperAdmin() ) {
    Analog::log(
        'SuperAdmin does note live anywhere!',
        Analog::INFO
    );
    die();
}

if ( $member === null ) {
    $member = new Adherent($login->login);
}

$coords = new Coordinates();
if ( isset($_POST['latitude']) && isset($_POST['longitude']) ) {
    $res = $coords->setCoords(
        $member->id,
        $_POST['latitude'],
        $_POST['longitude']
    );

    $message = '';
    if ( $res === true ) {
        $message = _T("New coordinates has been stored!");
    } else {
        $message = _T("Coordinates has not been stored :(");
    }

    die($message);
}

if ( isset($_POST['remove']) ) {
    $res = $coords->removeCoords($member->id);
    die(($res > 0) ? 'true' : 'false');
}

