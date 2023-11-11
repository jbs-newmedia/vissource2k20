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
 * @var \osWFrame\Core\DDM4 $this
 * @var \osWFrame\Core\Template $osW_Template
 * @var \VIS2\Core\Main $VIS2_Main
 * @var \VIS2\Core\User $VIS2_User
 *
 */

use osWFrame\Core\Navigation;
use osWFrame\Core\Session;
use osWFrame\Core\Settings;
use VIS2\Core\Protect;
use VIS2\Core\User;

if (Settings::getAction() === 'dologin') {
    $vis2_login_email = Settings::catchStringPostValue('vis2_login_email');
    if (User::validateEmail($vis2_login_email) === true) {
        if ($VIS2_User->loadUserDetailsByEMail($vis2_login_email) === true) {
            $vis2_login_password = Settings::catchStringPostValue('vis2_login_password');
            if ($vis2_login_password === '') {
                $osW_Template->Form()->addErrorMessage('vis2_login_password', 'Bitte geben Sie Ihr Passwort ein.');
            } else {
                if ($VIS2_User->getIntVar('user_status') === 1) {
                    if (Protect::isBlocked($VIS2_User->getId()) !== true) {
                        if (User::validatePassword(
                            $vis2_login_password,
                            $VIS2_User->getStringVar('user_password')
                        ) !== true
                        ) {
                            $osW_Template->Form()->addErrorMessage('vis2_login_password', 'Ihr Passwort ist falsch.');
                            Protect::addEntry($VIS2_User->getId());
                        }
                    } else {
                        $time = Protect::getTime($VIS2_User->getId());
                        if ($time === 0) {
                            $osW_Template->Form()->addErrorMessage('vis2_login_email', 'Sie sind gesperrt.');
                        } else {
                            $osW_Template->Form()->addErrorMessage(
                                'vis2_login_email',
                                'Sie sind bis zum ' . date('d.m.Y H:i:s', $time) . ' Uhr gesperrt.'
                            );
                        }
                    }
                } elseif ($VIS2_User->getIntVar('user_status') === 0) {
                    $osW_Template->Form()->addErrorMessage('vis2_login_email', 'Sie sind nicht aktiviert.');
                } elseif ($VIS2_User->getIntVar('user_status') === 2) {
                    $osW_Template->Form()->addErrorMessage('vis2_login_email', 'Sie sind blockiert.');
                }
            }
        } else {
            $osW_Template->Form()->addErrorMessage('vis2_login_email', 'Ihre E-Mail-Adresse existiert nicht.');
        }
    } else {
        $osW_Template->Form()->addErrorMessage('vis2_login_email', 'Ihre E-Mail-Adresse ist ungültig.');
    }

    if ($osW_Template->Form()->hasErrorMessages() === true) {
        osWFrame\Core\Settings::setAction('');
    } else {
        if ($VIS2_User->createLogin(Session::getId(), Settings::catchBoolPostValue('vis2_login_remember')) === true) {
            osWFrame\Core\SessionMessageStack::addMessage('session', 'success', [
                'msg' => 'Sie wurden erfolgreich eingeloggt.',
            ]);

            Protect::clearEntries($VIS2_User->getId());

            $vis2_login_link = Settings::catchValue(Settings::getStringVar('vis2_path') . '_login_link', '', 's');
            if (($vis2_login_link !== '') && ($vis2_login_link !== '/' . Settings::getStringVar(
                'vis2_path'
            )) && ($vis2_login_link !== '/' . Settings::getStringVar(
                'vis2_path'
            ) . '/') && ($vis2_login_link !== '/' . Settings::getStringVar('vis2_path') . '/' . Settings::getStringVar(
                'vis2_login_tool'
            ) . '/')
            ) {
                osWFrame\Core\Session::removeVar(Settings::getStringVar('vis2_path') . '_login_link');
                osWFrame\Core\Network::directHeader($vis2_login_link);
            } else {
                if (Settings::getStringVar('vis2_default_tool') !== '') {
                    $VIS2_Main->setTool(Settings::getStringVar('vis2_login_tool'));
                } else {
                    $toolsdata = $VIS2_User->getTools();
                    if (count($toolsdata) === 1) {
                        $VIS2_Main->setTool(array_key_first($toolsdata));
                    } else {
                        $VIS2_Main->setTool(Settings::getStringVar('vis2_chtool_module'));
                    }
                }

                osWFrame\Core\Network::directHeader(
                    Navigation::buildUrl(
                        Settings::getStringVar('frame_default_module'),
                        'vistool=' . $VIS2_Main->getTool()
                    )
                );
            }
        } else {
            $osW_Template->Form()->addErrorMessage('vis2_login_email', 'Sie konnten nicht eingeloggt werden.');
        }
    }
}
