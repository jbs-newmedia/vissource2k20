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

use osWFrame\Core\Bootstrap5;
use osWFrame\Core\DB;
use osWFrame\Core\FavIcon;
use osWFrame\Core\FontAwesome6;
use osWFrame\Core\jQuery3;
use osWFrame\Core\Navigation;
use osWFrame\Core\Network;
use osWFrame\Core\Settings;
use osWFrame\Core\Template;

Settings::setStringVar('frame_current_module', Settings::getStringVar('frame_default_module'));

$osW_Template = new Template();

$osW_jQuery3 = new jQuery3($osW_Template);
$osW_jQuery3->load();

$osW_Bootstrap5 = new Bootstrap5($osW_Template);
if ((Settings::getStringVar('vis2_theme') !== null) && (Settings::getStringVar('vis2_theme') !== '')) {
    $osW_Bootstrap5->setTheme(Settings::getStringVar('vis2_theme'));
}
if ((Settings::getStringVar('vis2_theme_color') !== null) && (Settings::getStringVar('vis2_theme_color') !== '')) {
    $osW_Bootstrap5->setCustom('blue', Settings::getStringVar('vis2_theme_color'));
}
if ((Settings::getStringVar('vis2_theme_font') !== null) && (Settings::getStringVar('vis2_theme_font') !== '')) {
    $osW_Bootstrap5->setCustom('font-family-sans-serif', Settings::getStringVar('vis2_theme_font'));
}
$osW_Bootstrap5->load();

$osW_FontAwesome6 = new FontAwesome6($osW_Template);
$osW_FontAwesome6->load();

$osW_Bootstrap5->loadPlugin('jbsadmin');
$osW_Bootstrap5->loadPlugin('avalynx');
$osW_Bootstrap5->loadPlugin('select');
$osW_Bootstrap5->loadPlugin('datatables');
$osW_Bootstrap5->loadPlugin('datatables_responsive');
$osW_Bootstrap5->loadPlugin('datepicker');
$osW_Bootstrap5->loadPlugin('notify');

Network::sendHeader('Content-Type: text/html; charset=utf-8');
$osW_Template->addVoidTag('base', [
    'href' => Settings::getStringVar('project_domain_full'),
]);
$osW_Template->addVoidTag('meta', [
    'charset' => 'utf-8',
]);
$osW_Template->addVoidTag('meta', [
    'http-equiv' => 'X-UA-Compatible',
    'content' => 'IE=edge',
]);
$osW_Template->addVoidTag('meta', [
    'name' => 'viewport',
    'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no',
]);

if (DB::getConnection('default') === null) {
    DB::addConnectionMYSQL(
        Settings::getStringVar('database_server'),
        Settings::getStringVar('database_username'),
        Settings::getStringVar('database_password'),
        Settings::getStringVar('database_db'),
        Settings::getStringVar('database_character'),
        'default',
        Settings::getIntVar('database_port')
    );
    DB::connect();
}

if (Settings::getStringVar('vis2_logo_favicon_name_path') !== null) {
    $osW_FavIcon = new FavIcon(Settings::getStringVar('vis2_logo_favicon_name_path'), $osW_Template);
    $osW_FavIcon->setIcons2Template();
} else {
    /** ToDo path */
    $osW_FavIcon = new FavIcon(
        'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
            'frame_current_module'
        ) . \DIRECTORY_SEPARATOR . 'img' . \DIRECTORY_SEPARATOR . Settings::getStringVar('vis2_logo_favicon_name'),
        $osW_Template
    );
    $osW_FavIcon->setIcons2Template();
}

$file = Settings::getStringVar('settings_abspath') . 'oswproject' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
if (file_exists($file)) {
    include_once $file;

    Navigation::checkUrl();
} elseif (file_exists($file_core)) {
    include_once $file_core;

    Navigation::checkUrl();
} else {
    Settings::setStringVar('frame_current_module', Settings::getStringVar('errorlogger_module'));
    $_GET['error_status'] = 404;

    $file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
    $file_core = Settings::getStringVar(
        'settings_abspath'
    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
    if (file_exists($file)) {
        include_once $file;
    } elseif (file_exists($file_core)) {
        include_once $file_core;
    }
}
