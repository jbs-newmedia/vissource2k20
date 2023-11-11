<?php declare(strict_types=0);

/**
 * This file is part of the VIS2:Manager package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2:Manager
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var \osWFrame\Core\Template $osW_Template
 *
 */

use osWFrame\Core\Settings;
use osWFrame\Core\Template;
use VIS2\Lab\Main;

$VIS2_Lab_Main = new Main($osW_Template);

$file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'vis_content.inc.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'vis_content.inc.php';

if (file_exists($file)) {
    require_once $file;
} elseif (file_exists($file_core)) {
    require_once $file_core;
}
