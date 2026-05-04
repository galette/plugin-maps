<?php

/**
 * This file is part of Galette Maps plugin (https://galette.eu).
 * SPDX-FileCopyrightText: Copyright © 2012-2026 The Galette Team
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

declare(strict_types=1);

/**
 * Bootstrap tests file for Galette Auto plugin
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 */

define('GALETTE_PLUGINS_PATH', __DIR__ . '/../../');
$basepath = '../../../galette/'; // phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable -- used from Core testBootstrap

include_once '../../../tests/TestsBootstrap.php';
require_once __DIR__ . '/../_config.inc.php';
