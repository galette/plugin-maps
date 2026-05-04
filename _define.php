<?php

/**
 * This file is part of Galette Maps plugin (https://galette.eu).
 * SPDX-FileCopyrightText: Copyright © 2012-2026 The Galette Team
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

declare(strict_types=1);

/** @var \Galette\Core\Plugins $this */
$this->register(
    name: 'Galette Maps',     //Name
    desc: 'Maps features',    //Short description
    author: 'Johan Cwiklinski', //Author
    version: '2.2.1',            //Version
    compver:  '1.2.0',            //Galette compatible version
    route: 'maps',             //routing name and translation domain
    date: '2025-12-08',       //Release date
    acls: [   //Permissions needed
        'maps_localize_member'  => 'member',
        'maps_mymap'            => 'member',
        'maps_ilivehere'        => 'member'
    ]
);
