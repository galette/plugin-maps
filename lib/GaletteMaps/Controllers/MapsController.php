<?php

/**
 * Copyright © 2003-2024 The Galette Team
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

namespace GaletteMaps\Controllers;

use DI\Attribute\Inject;
use Galette\Controllers\AbstractPluginController;
use Galette\Entity\Adherent;
use GaletteMaps\NominatimTowns;
use GaletteMaps\Coordinates;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Analog\Analog;

/**
 * Galette maps controller
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 */

class MapsController extends AbstractPluginController
{
    /**
     * @var array<string, mixed>
     */
    #[Inject("Plugin Galette Maps")]
    protected array $module_info;

    /**
     * Main route
     *
     * @param Request  $request  PSR Request
     * @param Response $response PSR Response
     *
     * @return Response
     */
    public function map(Request $request, Response $response): Response
    {
        $login = $this->login;
        if (!$this->preferences->showPublicPages($login)) {
            //public pages are not actives
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->routeparser->urlFor('slash'));
        }

        $coords = new Coordinates();
        $list = $coords->listCoords();

        $params = [
            'require_dialog'    => true,
            'page_title'        => _T('Maps', 'maps'),
            'module_id'         => $this->getModuleId()
        ];

        if (!$login->isLogged()) {
            $params['is_public'] = true;
        }

        if ($list !== false) {
            $params['list'] = $list;
        } else {
            $this->flash->addMessage(
                'error_detected',
                _T('Coordinates has not been loaded. Maybe plugin tables does not exists in the database?', 'maps')
            );
        }

        // display page
        $this->view->render(
            $response,
            $this->getTemplate('maps'),
            $params
        );
        return $response;
    }

    /**
     * Member localization
     *
     * @param Request  $request  PSR Request
     * @param Response $response PSR Response
     * @param ?integer $id       Member ID
     *
     * @return Response
     */
    public function localizeMember(Request $request, Response $response, int $id = null): Response
    {
        if ($id === null) {
            $id = (int)$this->login->id;
        }
        $deps = array(
            'picture'   => false,
            'groups'    => false,
            'dues'      => false
        );
        $member = new Adherent($this->zdb, $id, $deps);

        if (
            $this->login->id != $id
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
                //FIXME: silent fallback is maybe not the best to do
                $member->load($this->login->id);
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
            'module_id'         => $this->getModuleId()
        ];

        if ($towns !== false) {
            $params['towns'] = $towns;
        } elseif (count($mcoords) > 0) {
            $params['town'] = $mcoords;
        }

        if ($member->login == $this->login->login) {
            $params['mymap'] = true;
        }

        // display page
        $this->view->render(
            $response,
            $this->getTemplate('mymap'),
            $params
        );
        return $response;
    }

    /**
     * Change member localization
     *
     * @param Request  $request  PSR Request
     * @param Response $response PSR Response
     * @param ?integer $id       Member ID
     *
     * @return Response
     */
    public function ILiveHere(Request $request, Response $response, int $id = null): Response
    {
        $error = null;
        $message = null;

        if ($id === null && $this->login->isSuperAdmin()) {
            Analog::log(
                'SuperAdmin does not live anywhere!',
                Analog::INFO
            );
            $error = _T('Superadmin cannot be localized.', 'maps');
        } elseif ($id === null) {
            $member = new Adherent($this->zdb, $this->login->login);
            $id = $member->id;
        } elseif (
            !$this->login->isSuperAdmin()
            && !$this->login->isAdmin()
            && !$this->login->isStaff()
            && $this->login->isGroupManager()
        ) {
            $member = new Adherent($this->zdb, $id);
            //check if current logged-in user can manage loaded member
            $groups = $member->groups;
            $can_manage = false;
            foreach ($groups as $group) {
                if ($this->login->isGroupManager($group->getId())) {
                    $can_manage = true;
                    break;
                }
            }
            if ($can_manage !== true) {
                Analog::log(
                    'Logged in member ' . $this->login->login .
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
            } elseif (
                isset($post['latitude'])
                && isset($post['longitude'])
            ) {
                $res = $coords->setCoords(
                    $id,
                    (float)$post['latitude'],
                    (float)$post['longitude']
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
            'message'   => ($error ?? $message)
        ];

        $body = $response->getBody();
        $body->write(json_encode($res));

        return $response;
    }
}
