<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Current user localization
 *
 * This page can be loaded directly, or via ajax.
 * Via ajax, we do not have a full html page, but only
 * that will be displayed using javascript on another page
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
 * @since     Available since 0.7.4dev - 2012-10-03
 */

use Galette\Entity\Adherent as Adherent;
use GaletteMaps\Towns as Towns;
use GaletteMaps\Coordinates as Coordinates;

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

if ( !$login->isLogged()
    && !$login->isAdmin()
    && !$login->isSatffMember()
    && !$login->isSuperAdmin()
) {
    header('location: ../../index.php');
    die();
}

//Constants and classes from plugin
require_once '_config.inc.php';

$member = new Adherent((int)$_GET['id_adh']);
$coords = new Coordinates();
$mcoords = $coords->getCoords($member->id);

$towns = false;
if ( count($mcoords) === 0 ) {
    if ( $member->town != '') {
        $t = new Towns();
        $towns = $t->search($member->town);
    }
}

$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$tpl->compile_id = MAPS_SMARTY_PREFIX;
//set util paths
$plugin_dir = basename(dirname($_SERVER['SCRIPT_NAME']));
$tpl->assign(
    'galette_url',
    'http://' . $_SERVER['HTTP_HOST'] .
    preg_replace(
        "/\/plugins\/" . $plugin_dir . '/',
        "/",
        dirname($_SERVER['SCRIPT_NAME'])
    )
);
$tpl->assign(
    'plugin_url',
    'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/'
);
$tpl->assign(
    'page_title',
    _T("Maps") . ' - ' . str_replace(
        '%member',
        $member->sfullname,
        _T("%member geographic position")
    )
);
if ( $towns !== false ) {
    $tpl->assign('towns', $towns);
}
$tpl->assign('member', $member);
if ( count($mcoords) > 0 ) {
    $tpl->assign('town', $mcoords);
}
$tpl->assign('require_dialog', true);
$tpl->assign('adhmap', true);
$content = $tpl->fetch('mymap.tpl', MAPS_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', MAPS_SMARTY_PREFIX);

