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
 * @var \VIS2\Core\User $VIS2_User
 * @var \VIS2\Core\Navigation $VIS2_Navigation
 * @var \VIS2\Core\Main $VIS2_Main
 * @var \VIS2\Core\BreadCrumb $VIS2_BreadCrumb
 * @var \osWFrame\Core\DDM4 $osW_DDM4
 * @var \osWFrame\Core\Template $osW_Template
 *
 */

use osWFrame\Core\Navigation;
use osWFrame\Core\Network;
use osWFrame\Core\Session;
use osWFrame\Core\Settings;
use VIS2\Core\BreadCrumb;
use VIS2\Core\Mandant;
use VIS2\Core\Permission;

$VIS2_Mandant = new Mandant($VIS2_Main->getToolId());
$osW_Template->setVar('VIS2_Mandant', $VIS2_Mandant);
if ($VIS2_Main->getBoolVar('tool_use_mandant') === true) {
    $vis2_mandant_id = 0;
    if ($VIS2_Mandant->getId() === 0) {
        if (count($VIS2_User->getMandantenSelectArray()) === 1) {
            $vis2_mandant_id = array_key_first($VIS2_User->getMandantenSelectArray());
        } else {
            $vis2_mandant_id = (int)(Settings::catchValue('vis2_mandant_id', 0, 'gp'));
        }
    } else {
        if ($VIS2_User->checkMandantAccess($VIS2_Mandant->getId()) === true) {
            $vis2_mandant_id = (int)(Settings::catchValue('vis2_mandant_id', 0, 'gp'));
        } else {
            $VIS2_Mandant->setId(0);
        }
    }
    if ($vis2_mandant_id > 0) {
        $VIS2_Mandant->setId($vis2_mandant_id);
    }
}

$VIS2_Permission = new Permission($VIS2_Main->getToolId(), $VIS2_User->getId());
$VIS2_Navigation->setPermission($VIS2_Permission);

/*
 * Hook Header.
 */
$file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content_header.inc.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content_header.inc.php';
if (file_exists($file)) {
    require_once $file;
} elseif (file_exists($file_core)) {
    require_once $file_core;
}

/*
 * Hook Header für Tools.
 */
$file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content_header.inc.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content_header.inc.php';
if (file_exists($file)) {
    require_once $file;
} elseif (file_exists($file_core)) {
    require_once $file_core;
}

if ($VIS2_Navigation->getPage() === '') {
    $VIS2_Navigation->setPage(Settings::catchStringGetValue('vispage'));
}

if ($VIS2_Navigation->getFile() === '') {
    $file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . $VIS2_Navigation->getPage() . '.inc.php';
    $file_core = Settings::getStringVar(
        'settings_abspath'
    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . $VIS2_Navigation->getPage() . '.inc.php';
} else {
    $file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . $VIS2_Navigation->getFile() . '.inc.php';
    $file_core = Settings::getStringVar(
        'settings_abspath'
    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool(
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . $VIS2_Navigation->getFile() . '.inc.php';
}
if ((file_exists($file) || file_exists($file_core)) && ($VIS2_Permission->checkPermission(
    $VIS2_Navigation->getPage(),
    'view'
) === true)
) {
    if (file_exists($file)) {
        include $file;
    } elseif (file_exists($file_core)) {
        include $file_core;
    }
    $VIS2_BreadCrumb->add(
        $VIS2_Navigation->getNavigationTitle(),
        Settings::getStringVar('frame_current_module'),
        'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage()
    );
    if ($VIS2_Navigation->getFile() === '') {
        $osW_Template->setVarFromFile(
            'vis2content',
            $VIS2_Navigation->getPage(),
            Settings::getStringVar(
                'frame_current_module'
            ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool()
        );
    } else {
        $osW_Template->setVarFromFile(
            'vis2content',
            $VIS2_Navigation->getFile(),
            Settings::getStringVar(
                'frame_current_module'
            ) . \DIRECTORY_SEPARATOR . 'vistools' . \DIRECTORY_SEPARATOR . $VIS2_Main->getTool()
        );
    }
} else {
    $VIS2_Navigation->setPage($VIS2_Navigation->getDefaultPage());
    $VIS2_BreadCrumb->add(
        $VIS2_Navigation->getNavigationTitle(),
        Settings::getStringVar('frame_current_module'),
        'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage()
    );
    Network::directHeader(
        Navigation::buildUrl('current', 'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage())
    );
}

$VIS2_Main->addTemplateJSCode('head', 'var session_timeout=\'' . Session::getSessionLifetime() . '\';');
$VIS2_Main->addTemplateJSCode(
    'head',
    'var session_logout=\'' . Navigation::buildUrl(
        'current',
        'vistool=' . Settings::getStringVar('vis2_logout_module')
    ) . '\';'
);
