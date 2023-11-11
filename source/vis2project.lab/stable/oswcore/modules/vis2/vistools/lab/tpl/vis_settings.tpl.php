<?php declare(strict_types=0);

/**
 * This file is part of the VIS2:Lab package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2:Lab
 * @link      https://oswframe.com
 * @license   MIT License
 */

use osWFrame\Core\Settings;

$file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'vis_settings.tpl.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'vis_settings.tpl.php';
if (file_exists($file)) {
    require_once $file;
} elseif (file_exists($file_core)) {
    require_once $file_core;
}
