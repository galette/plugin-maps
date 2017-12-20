<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

use Analog\Analog;
use Galette\Entity\Adherent;
use GaletteMaps\NominatimTowns;
use GaletteMaps\Coordinates;

/**
 * Maps routes
 *
 * PHP version 5
 *
 * Copyright © 2015 The Galette Team
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
 * @copyright 2015-2016 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     0.9dev 2015-10-28
 */

//Constants and classes from plugin
require_once $module['root'] . '/_config.inc.php';

$this->get(
    __('/localize-member', 'maps_routes') . '/{id:\d+}',
    function ($request, $response, $args) use ($module, $module_id) {
        $id = $args['id'];
        $member = new Adherent($this->zdb, (int)$id);

        if ($this->login->id != $id
            && !$this->login->isAdmin()
            && !$this->login->isStaff()
            && $this->login->isGroupManager()
        ) {
            //check if requested member is part of managed groups
            $groups = $member->groups;
            $is_managed = false;
            foreach ($groups as $g) {
                if ($this->login->isGroupManager($g->getId())) {
                    $is_managed = true;
                    break;
                }
            }
            if ($is_managed !== true) {
                //requested member is not part of managed groups, fall back to logged
                //in member
                $member->load($this->login->id);
                $id = $this->login->id;
            }
        }

        $coords = new Coordinates();
        $mcoords = $coords->getCoords($member->id);

        $towns = false;
        if (count($mcoords) === 0) {
            if ($member->town != '') {
                $t = new NominatimTowns($this->preferences);
                $towns = $t->search(
                    $member->town,
                    $member->country
                );
            }
        }

        $params = [
            'page_title'        => _T('Maps', 'maps') . ' - ' . str_replace(
                '%member',
                $member->sfullname,
                _T('%member geographic position', 'maps')
            ),
            'member'            => $member,
            'require_dialog'    => true,
            'adh_map'           => true,
            'module_id'         => $module_id
        ];

        if ($towns !== false) {
            $params['towns'] = $towns;
        }

        if ($mcoords === false) {
            $this->flash->addMessage(
                'error_detected',
                _T('Coordinates has not been loaded. Maybe plugin tables does not exists in the datatabase?', 'maps')
            );
        } elseif (count($mcoords) > 0) {
            $params['town'] = $mcoords;
        }

        if ($member->login == $this->login->login) {
            $params['mymap'] = true;
        }

        // display page
        $this->view->render(
            $response,
            'file:[' . $module['route'] . ']mymap.tpl',
            $params
        );
        return $response;
    }
)->setName('maps_localize_member')->add($authenticate);

//member self localization
$this->get(
    __('/mymap', 'maps_routes'),
    function ($request, $response) {
        $deps = array(
            'picture'   => false,
            'groups'    => false,
            'dues'      => false
        );
        $member = new Adherent($this->zdb, $this->login->login, $deps);
        return $response
            ->withStatus(301)
            ->withHeader('Location', $this->router->pathFor('maps_localize_member', ['id' => $member->id]));
    }
)->setName('maps_mymap')->add($authenticate);

//global map page
$this->get(
    __('/map', 'maps_routes'),
    function ($request, $response) use ($module, $module_id) {
        $login = $this->login;
        if (!$this->preferences->showPublicPages($login)) {
            //public pages are not actives
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->router->pathFor('slash'));
        }

        $coords = new Coordinates();
        $list = $coords->listCoords();

        $params = [
            'require_dialog'    => true,
            'page_title'        => _T('Maps', 'maps'),
            'module_id'         => $module_id
        ];

        if (!$login->isLogged()) {
            $params['is_public'] = true;
        }

        if ($list !== false) {
            $params['list'] = $list;
        } else {
            $this->flash->addMessage(
                'error_detected',
                _T('Coordinates has not been loaded. Maybe plugin tables does not exists in the datatabase?', 'maps')
            );
        }

        // display page
        $this->view->render(
            $response,
            'file:[' . $module['route'] . ']maps.tpl',
            $params
        );
        return $response;
    }
)->setName('maps_map');

$this->post(
    __('/i-live-here', 'maps_routes') . '[/{id:\d+}]',
    function ($request, $response, $args) {
        $id = null;
        if (isset($args['id'])) {
            $id = $args['id'];
        }
        $login = $this->login;
        $error = null;
        $message = null;

        if ($id === null && $login->isSuperAdmin()) {
            Analog::log(
                'SuperAdmin does note live anywhere!',
                Analog::INFO
            );
            $error = _T('Superadmin cannot be localized.', 'maps');
        } elseif ($id === null) {
            $member = new Adherent($this->zdb, $login->login);
            $id = $member->id;
        } elseif (!$login->isSuperAdmin()
            && !$login->isAdmin()
            && !$login->isStaff()
            && $login->isGroupManager()
        ) {
            $member = new Adherent($this->zdb, (int)$id);
            //check if current logged in user can manage loaded member
            $groups = $member->groups;
            $can_manage = false;
            foreach ($groups as $group) {
                if ($login->isGroupManager($group->getId())) {
                    $can_manage = true;
                    break;
                }
            }
            if ($can_manage !== true) {
                Analog::log(
                    'Logged in member ' . $login->login .
                    ' has tried to load member #' . $id .
                    ' but do not manage any groups he belongs to.',
                    Analog::WARNING
                );
                $error = _T('Coordinates has not been removed :(', 'maps');
            }
        }

        if ($error === null) {
            $post = $request->getParsedBody();
            $coords = new Coordinates();
            if (isset($post['remove'])) {
                $res = $coords->removeCoords($id);
                if ($res > 0) {
                    $message = _T('Coordinates has been removed!', 'maps');
                } else {
                    $error = _T('Coordinates has not been removed :(', 'maps');
                }
            } elseif (isset($post['latitude'])
                && isset($post['longitude'])
            ) {
                $res = $coords->setCoords(
                    $id,
                    $post['latitude'],
                    $post['longitude']
                );

                if ($res === true) {
                    $message = _T('New coordinates has been stored!', 'maps');
                } else {
                    $error = _T('Coordinates has not been stored :(', 'maps');
                }
            } else {
                $error = _T('Something went wrong :(', 'maps');
            }
        }

        $response = $response->withHeader('Content-type', 'application/json');

        $res = [
            'res'       => $error === null,
            'message'   => ($error === null ? $message : $error)
        ];

        $body = $response->getBody();
        $body->write(json_encode($res));

        return $response;
    }
)->setName('maps_ilivehere')->add($authenticate);
