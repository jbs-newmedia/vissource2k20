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
 * @var \VIS2\Core\Main $VIS2_Main
 * @var \VIS2\Core\User $VIS2_User
 *
 */

use osWFrame\Core\Navigation;
use osWFrame\Core\Settings;

if ($VIS2_User->doLogout() === true) {
    osWFrame\Core\SessionMessageStack::addMessage('session', 'success', [
        'msg' => 'Sie wurden erfolgreich ausgeloggt.',
    ]);
} else {
    osWFrame\Core\SessionMessageStack::addMessage('session', 'alert', [
        'msg' => 'Sie konnten nicht ausgeloggt werden.',
    ]);
}
$VIS2_Main->setTool(Settings::getStringVar('vis2_logout_module'));

osWFrame\Core\Network::directHeader(
    Navigation::buildUrl(Settings::getStringVar('frame_default_module'), 'vistool=' . $VIS2_Main->getTool())
);
