<?php declare(strict_types=0);

/**
 * This file is part of the VIS2:Lab package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2:Lab
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var \VIS2\Core\Main $VIS2_Main
 *
 */

use osWFrame\Core\Settings;
use VIS2\Core\Main;

$run_counter = 0;

foreach (glob(
    Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'lab_header' . \DIRECTORY_SEPARATOR . '*.inc.php'
) as $file_header) {
    $run_counter++;
    $header = str_replace('.inc.php', '', basename($file_header));
    require_once $file_header;
}


foreach (glob(
    Settings::getStringVar(
        'settings_abspath'
    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'lab_header' . \DIRECTORY_SEPARATOR . '*.inc.php'
) as $file_header) {
    $run_counter++;
    $header = str_replace('.inc.php', '', basename($file_header));
    require_once $file_header;
}
