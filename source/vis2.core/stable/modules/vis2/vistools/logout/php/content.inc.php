<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license MIT License
 *
 * @var $osW_Template \osWFrame\Core\Template
 * @var $VIS2_User \VIS2\Core\User
 * @var $VIS2_Main \VIS2\Core\Main
 *
 */


use osWFrame\Core\Cookie;
use osWFrame\Core\Settings;

if ($VIS2_User->doLogout()===true) {
	osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'Sie wurden erfolgreich ausgeloggt.']);
} else {
	osWFrame\Core\SessionMessageStack::addMessage('session', 'alert', ['msg'=>'Sie konnten nicht ausgeloggt werden.']);
}
$VIS2_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis2_logout_module'));

osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl(\osWFrame\Core\Settings::getStringVar('frame_default_module'), 'vistool='.$VIS2_Main->getTool()));

?>