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
 * @var \osWFrame\Core\Template $osW_Template
 */

/*
 * Hook Configure.
 */

use osWFrame\Core\BreadCrumb;
use osWFrame\Core\Network;
use osWFrame\Core\Session;
use osWFrame\Core\SessionMessageStack;
use osWFrame\Core\Settings;
use osWFrame\Core\Template;
use VIS2\Core\Main;
use VIS2\Core\Navigation;
use VIS2\Core\User;

$file = Settings::getStringVar('settings_abspath') . 'oswproject' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content_header_config.inc.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content_header_config.inc.php';
if (file_exists($file)) {
    require_once $file;
} elseif (file_exists($file_core)) {
    require_once $file_core;
}

$VIS2_Main = new Main();
$VIS2_Main->setEnvironment($osW_Template);
$osW_Template->setVar('VIS2_Main', $VIS2_Main);

$VIS2_User = new User();
$osW_Template->setVar('VIS2_User', $VIS2_User);

if ($VIS2_User->isLoginSessionToken() === true) {
    $VIS2_User->doLoginByToken();
}

if ($VIS2_User->isLoggedIn() !== true) {
    if ($VIS2_User->isLoginCookieToken() === true) {
        $VIS2_User->doLoginByToken($VIS2_User->getLoginCookieToken());
    }
}

$VIS2_Main->setTool(
    Settings::catchStringGetValue(
        'vistool',
        Settings::catchStringSessionValue(Settings::getStringVar('vis2_path') . '_tool')
    )
);
if ($VIS2_User->isLoggedIn() === true) {
    if ($VIS2_User->checkToolAccess($VIS2_Main->getTool()) !== true) {
        $VIS2_Main->setTool(Settings::getStringVar('vis2_chtool_module'));
        Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool=' . $VIS2_Main->getTool()));
    }
    if (Settings::getAction() !== 'ajax') {
        if ($VIS2_Main->getTool() === Settings::getStringVar('vis2_login_module')) {
            $VIS2_Main->setTool(Settings::getStringVar('vis2_chtool_module'));
            Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool=' . $VIS2_Main->getTool()));
        }
    }
} else {
    $request_uri = $_SERVER['REQUEST_URI'];
    if (defined('SID')) {
        $request_uri = preg_replace('/oswsid=([a-z0-9]+)/', SID, $request_uri);
    } else {
        $request_uri = preg_replace('/oswsid=([a-z0-9]+)(&?)/', '', $request_uri);
    }

    if (((!stristr(
        $request_uri,
        Settings::getStringVar('vis2_path') . '/' . Settings::getStringVar('vis2_login_module') . '/'
    )) && (strlen($request_uri) > strlen(
        Settings::getStringVar('vis2_path') . '/' . Settings::getStringVar('vis2_login_module') . '/'
    ))) && ((!stristr(
        $request_uri,
        Settings::getStringVar('vis2_path') . '/' . Settings::getStringVar('vis2_logout_module') . '/'
    )) && (strlen($request_uri) > strlen(
        Settings::getStringVar('vis2_path') . '/' . Settings::getStringVar('vis2_logout_module') . '/'
    )))
    ) {
        $vis2_login_link = Settings::catchStringSessionValue(Settings::getStringVar('vis2_path') . '_login_link', '');
        if ($vis2_login_link === '') {
            Session::setStringVar(Settings::getStringVar('vis2_path') . '_login_link', $request_uri);
        }
    }

    if ($VIS2_Main->getTool() !== Settings::getStringVar('vis2_login_module')) {
        $VIS2_Main->setTool(Settings::getStringVar('vis2_login_module'));
        Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool=' . $VIS2_Main->getTool()));
    }
}

$VIS2_Navigation = new Navigation($VIS2_Main->getToolId());
$osW_Template->setVar('VIS2_Navigation', $VIS2_Navigation);

$VIS2_User->setToolId($VIS2_Main->getToolId());

/**
 * BreadCrumb erstellen
 */
$VIS2_BreadCrumb = new BreadCrumb();

$VIS2_BreadCrumb->add('VIS', Settings::getStringVar('frame_current_module'));
if (Settings::getStringVar('vis2_tool_' . $VIS2_Main->getTool() . '_title') !== null) {
    if (Settings::getStringVar('vis2_tool_' . $VIS2_Main->getTool() . '_title') !== '') {
        $VIS2_BreadCrumb->add(
            $VIS2_Main->getToolName(),
            Settings::getStringVar('frame_current_module'),
            'vistool=' . $VIS2_Main->getTool()
        );
    }
} else {
    $VIS2_BreadCrumb->add(
        $VIS2_Main->getToolName(),
        Settings::getStringVar('frame_current_module'),
        'vistool=' . $VIS2_Main->getTool()
    );
}

/**
 * Inhalt VIS-Tool verarbeiten
 */
$file = Settings::getStringVar('settings_abspath') . 'oswproject' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
if (file_exists($file)) {
    require_once $file;
} elseif (file_exists($file_core)) {
    require_once $file_core;
}

$osW_Template->setVarFromFile(
    'vis2content',
    'content',
    Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(),
    'modules'
);

$osW_Template->setVarFromFile('content', 'content', Settings::getStringVar('frame_current_module'), 'modules');

/*
 * Hook Footer.
 */
$file = Settings::getStringVar('settings_abspath') . 'oswproject' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content_footer_pre.inc.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content_footer_pre.inc.php';
if (file_exists($file)) {
    require_once $file;
} elseif (file_exists($file_core)) {
    require_once $file_core;
}

/*
 * Titel für Browser aus BreadCrumb bauen
 */
$titles = [];
foreach ($VIS2_BreadCrumb->getReverse() as $item) {
    $titles[] = $item['name'];
}
$osW_Template->addStringTag('title', implode(Settings::getStringVar('vis2_navigation_char'), $titles));

/*
 * Session-Nachrichten aufbauen
 */
$_messages = [];
if (SessionMessageStack::getMessages('session') !== []) {
    foreach (SessionMessageStack::getMessages('session') as $type => $messages) {
        foreach ($messages as $message) {
            $_messages[] = 'window.parent.vis2_notify("' . $message['msg'] . '", "' . $type . '");';
        }
    }
    SessionMessageStack::clearMessages('session');
}

/*
 * Session-Nachrichten ausgeben
 */
$VIS2_Main->addJSCodeHead('$(function() {' . implode("\n", $_messages) . '});');

$osW_Template->addVoidTag('base', [
    'href' => Settings::getBaseUrl(),
]);
