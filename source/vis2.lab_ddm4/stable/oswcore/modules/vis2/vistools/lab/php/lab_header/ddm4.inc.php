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
 * @var int $run_counter
 * @var string $header
 * @var \VIS2\Core\Main $VIS2_Main
 * @var \VIS2\Core\Navigation $VIS2_Navigation
 *
 */

use osWFrame\Core\Settings;
use VIS2\Core\Main;
use VIS2\Core\Navigation;

$data = [];
$data['navigation_id'] = $run_counter;
$data['custom'] = true;
$data['tool_id'] = $VIS2_Main->getToolId();
$data['navigation_parent_id'] = 0;
$data['navigation_title'] = 'DDM4';
$data['navigation_sortorder'] = $run_counter + 100;
$data['navigation_ispublic'] = 1;
$data['page_id'] = 'p1';
$data['page_name'] = 'header_ddm4';
$data['page_description'] = 'DDM4';
$data['page_ispublic'] = 0;
$data['navigation_path'] = '';
$data['navigation_path_array'] = [];
$data['permission'] = ['link'];
$VIS2_Navigation->addNavigationElement($data);

$header_counter = $run_counter;
foreach (glob(
    Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'lab_content' . \DIRECTORY_SEPARATOR . $header . \DIRECTORY_SEPARATOR . '*.inc.php'
) as $file_content) {
    $run_counter++;

    require_once $file_content;
}
foreach (glob(
    Settings::getStringVar(
        'settings_abspath'
    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'lab_content' . \DIRECTORY_SEPARATOR . $header . \DIRECTORY_SEPARATOR . '*.inc.php'
) as $file_content) {
    $run_counter++;

    require_once $file_content;
}
