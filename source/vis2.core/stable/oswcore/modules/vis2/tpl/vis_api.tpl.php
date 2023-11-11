<?php declare(strict_types=0);

/**
 * This file is part of the VIS2 package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2
 * @link      https://oswframe.com
 * @license   MIT License
 */

use osWFrame\Core\Settings;

Settings::setBoolVar('vis2_api_die', true);

$dir = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'actions_api';
$file = $dir . \DIRECTORY_SEPARATOR . strtolower(Settings::getAction()) . '.tpl.php';

if ((file_exists($file)) && (dirname(realpath($file)) === $dir)) {
    $script = strtolower(Settings::getAction());
} else {
    $script = '';
}

if ($script !== '') {
    require_once $file;
}

if (Settings::getBoolVar('vis2_api_die') === true) {
    Settings::dieScript();
}
