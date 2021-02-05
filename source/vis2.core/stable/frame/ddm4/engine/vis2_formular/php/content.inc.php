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

$this->readParameters();

switch (\osWFrame\Core\Settings::getAction()) {
	case 'dosend':
		osWFrame\Core\Settings::setAction('dosend');
		break;
	default:
		osWFrame\Core\Settings::setAction('send');
		break;
}

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchValue('ddm_navigation_id', $this->getParameter('ddm_navigation_id'), 'pg'));

// Send
if ((\osWFrame\Core\Settings::getAction()=='send')||(\osWFrame\Core\Settings::getAction()=='dosend')) {
	foreach ($this->getSendElements() as $element=>$element_details) {
		$this->setSendElementStorage($element, $this->getSendElementOption($element, 'default_value'));
	}

	foreach ($this->getSendElements() as $element=>$options) {
		$this->parseFormSendElementPHP($element, $options);
	}

	if (\osWFrame\Core\Settings::getAction()=='dosend') {
		if (strlen(\osWFrame\Core\Settings::catchValue('btn_ddm_cancel', '', 'p'))>0) {
			osWFrame\Core\Settings::setAction('');
			$_POST=[];
		}
	}

	if ((\osWFrame\Core\Settings::getAction()=='send')||(\osWFrame\Core\Settings::getAction()=='dosend')) {
		foreach ($this->getSendElements() as $element=>$element_details) {
			if ((isset($element_details['name']))&&($element_details['name']!='')) {
				$this->setSendElementStorage($element, $this->getSendElementOption($element, 'default_value'));
			}
		}

		if (\osWFrame\Core\Settings::getAction()=='dosend') {
			foreach ($this->getSendElements() as $element=>$options) {
				$options=$this->getSendElementValue($element, 'validation');
				if ($options!='') {
					$this->parseParserSendElementPHP($element, $options);
				}
			}

			if ($this->getTemplate()->Form()->hasErrorMessages()===true) {
				osWFrame\Core\Settings::setAction('send');
				osWFrame\Core\SessionMessageStack::addMessage('session', 'danger', ['msg'=>$this->getGroupMessage('validation_error')]);
			} else {
				foreach ($this->getSendElements() as $element=>$options) {
					$this->parseFinishSendElementPHP($element, $options);
				}

				foreach ($this->getFinishElements() as $element=>$options) {
					$this->parseFinishSendElementPHP($element, $options);
				}

				foreach ($this->getAfterFinishElements() as $element=>$options) {
					$this->parseFinishSendElementPHP($element, $options);
				}
			}
		}
	} else {
		osWFrame\Core\SessionMessageStack::addMessage('session', 'danger', ['msg'=>$this->getGroupMessage('send_load_error_title')]);
		$this->direct($this->getDirectModule(), $this->getDirectParameters());
	}
}

$this->storeParameters();

?>