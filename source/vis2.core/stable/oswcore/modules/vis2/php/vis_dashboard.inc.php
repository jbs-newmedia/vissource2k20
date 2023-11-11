<?php declare(strict_types=0);

/**
 * This file is part of the VIS2 package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var \VIS2\Core\Main $VIS2_Main
 * @var \osWFrame\Core\Template $osW_Template
 *
 */

use osWFrame\Core\Settings;
use osWFrame\Core\Template;
use VIS2\Core\Main;

$dashboard_files = [];
$dashboard_tpls = [];
$dashboard_files_g = glob(
    Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'dashboard' . \DIRECTORY_SEPARATOR . '*.php'
);
foreach ($dashboard_files_g as $file) {
    $dashboard_files[substr(basename($file), 0, 3)] = $file;
    $dashboard_tpls[substr(basename($file), 0, 3)] = str_replace(
        [\DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR, 'inc.php'],
        [\DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR, 'tpl.php'],
        $file
    );
}
$dashboard_files_g = glob(
    Settings::getStringVar(
        'settings_abspath'
    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'dashboard' . \DIRECTORY_SEPARATOR . '*.php'
);
foreach ($dashboard_files_g as $file) {
    $dashboard_files[substr(basename($file), 0, 3)] = $file;
    $dashboard_tpls[substr(basename($file), 0, 3)] = str_replace(
        [\DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR, 'inc.php'],
        [\DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR, 'tpl.php'],
        $file
    );
}
$dashboard_files_l = glob(
    Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'dashboard' . \DIRECTORY_SEPARATOR . '*.php'
);
foreach ($dashboard_files_l as $file) {
    $dashboard_files[substr(basename($file), 0, 3)] = $file;
    $dashboard_tpls[substr(basename($file), 0, 3)] = str_replace(
        [\DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR, 'inc.php'],
        [\DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR, 'tpl.php'],
        $file
    );
}
$dashboard_files_l = glob(
    Settings::getStringVar(
        'settings_abspath'
    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'dashboard' . \DIRECTORY_SEPARATOR . '*.php'
);
foreach ($dashboard_files_l as $file) {
    $dashboard_files[substr(basename($file), 0, 3)] = $file;
    $dashboard_tpls[substr(basename($file), 0, 3)] = str_replace(
        [\DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR, 'inc.php'],
        [\DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR, 'tpl.php'],
        $file
    );
}
ksort($dashboard_files);

$dashboard_run = 'init';
$navigation_links = [];
foreach ($dashboard_files as $file) {
    include $file;
}
$dashboard_run = 'run';

foreach ($dashboard_files as $file) {
    include $file;
}

$osW_Template->setVar('dashboard_tpls', $dashboard_tpls);
