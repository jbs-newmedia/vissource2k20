<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

if (\osWFrame\Core\Settings::getAction()=='dologin') {
	$vis2_login_email=\osWFrame\Core\Settings::catchStringPostValue('vis2_login_email');
	if (\VIS2\Core\User::validateEmail($vis2_login_email)===true) {
		if ($VIS2_User->loadUserDetailsByEMail($vis2_login_email)===true) {
			$vis2_login_password=\osWFrame\Core\Settings::catchStringPostValue('vis2_login_password');
			if (strlen($vis2_login_password)==0) {
				$osW_Template->Form()->addErrorMessage('vis2_login_password', 'Bitte geben Sie Ihr Passwort ein.');
			} else {

				if ($VIS2_User->getIntVar('user_status')==1) {
					if (\VIS2\Core\Protect::isBlocked($VIS2_User->getId())!==true) {
						if (\VIS2\Core\User::validatePassword($vis2_login_password, $VIS2_User->getStringVar('user_password'))!==true) {
							$osW_Template->Form()->addErrorMessage('vis2_login_password', 'Ihr Passwort ist falsch.');
							\VIS2\Core\Protect::addEntry($VIS2_User->getId());
						}
					} else {
						$time=\VIS2\Core\Protect::getTime($VIS2_User->getId());
						if ($time==0) {
							$osW_Template->Form()->addErrorMessage('vis2_login_email', 'Sie sind gesperrt.');
						} else {
							$osW_Template->Form()->addErrorMessage('vis2_login_email', 'Sie sind bis zum '.date('d.m.Y H:i:s', $time).' Uhr gesperrt.');
						}
					}
				} elseif ($VIS2_User->getIntVar('user_status')==0) {
					$osW_Template->Form()->addErrorMessage('vis2_login_email', 'Sie sind nicht aktiviert.');
				} elseif ($VIS2_User->getIntVar('user_status')==2) {
					$osW_Template->Form()->addErrorMessage('vis2_login_email', 'Sie sind blockiert.');
				}
			}
		} else {
			$osW_Template->Form()->addErrorMessage('vis2_login_email', 'Ihre E-Mail-Adresse existiert nicht.');
		}
	} else {
		$osW_Template->Form()->addErrorMessage('vis2_login_email', 'Ihre E-Mail-Adresse ist ungültig.');
	}

	if ($osW_Template->Form()->hasErrorMessages()===true) {
		osWFrame\Core\Settings::setAction('');
	} else {
		if ($VIS2_User->createLogin()===true) {
			osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'Sie wurden erfolgreich eingeloggt.']);

			\VIS2\Core\Protect::clearEntries($VIS2_User->getId());

			osWFrame\Core\Cookie::setCookie('vis2_login_email', $vis2_login_email, osWFrame\Core\Settings::getIntVar('vis2_login_cookie_lifetime'));

			$vis2_login_link=\osWFrame\Core\Settings::catchValue('vis2_login_link', '', 's');
			if ((strlen($vis2_login_link)>0)&&($vis2_login_link!='/vis2')&&($vis2_login_link!='/vis2/')&&($vis2_login_link!='/vis2/'.\osWFrame\Core\Settings::getStringVar('vis2_login_tool').'/')) {
				osWFrame\Core\Session::removeVar('vis2_login_link');
				osWFrame\Core\Network::directHeader($vis2_login_link);
			} else {
				if (\osWFrame\Core\Settings::getStringVar('vis2_default_tool')!='') {
					$VIS2_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis2_login_tool'));
				} else {
					$toolsdata=$VIS2_User->getTools();
					if (count($toolsdata)===1) {
						$VIS2_Main->setTool(array_key_first($toolsdata));
					} else {
						$VIS2_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis2_chtool_module'));
					}
				}
				osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl(\osWFrame\Core\Settings::getStringVar('frame_default_module'), 'vistool='.$VIS2_Main->getTool()));
			}
		} else {
			$osW_Template->Form()->addErrorMessage('vis2_login_email', 'Sie konnten nicht eingeloggt werden.');
		}
	}
}

if (isset($_COOKIE['vis2_login_email'])) {
	$_POST['vis2_login_email']=$_COOKIE['vis2_login_email'];
}

?>