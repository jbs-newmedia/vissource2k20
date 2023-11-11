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
 *
 */

use osWFrame\Core\Settings;

$this->readParameters();

switch (Settings::getAction()) {
    case 'dosend':
        osWFrame\Core\Settings::setAction('dosend');

        break;
    default:
        osWFrame\Core\Settings::setAction('send');

        break;
}

$ddm_navigation_id = (int)(Settings::catchValue('ddm_navigation_id', $this->getParameter('ddm_navigation_id'), 'pg'));

// Send
if ((Settings::getAction() === 'send') || (Settings::getAction() === 'dosend')) {
    foreach ($this->getSendElements() as $element => $element_details) {
        $this->setSendElementStorage($element, $this->getSendElementOption($element, 'default_value'));
    }

    foreach ($this->getSendElements() as $element => $options) {
        $this->parseFormSendElementPHP($element, $options);
    }

    if (Settings::getAction() === 'dosend') {
        if (Settings::catchValue('btn_ddm_cancel', '', 'p') !== '') {
            osWFrame\Core\Settings::setAction('');
            $_POST = [];
        }
    }

    if ((Settings::getAction() === 'send') || (Settings::getAction() === 'dosend')) {
        foreach ($this->getSendElements() as $element => $element_details) {
            $this->setSendElementStorage($element, $this->getSendElementOption($element, 'default_value'));
        }

        if (Settings::getAction() === 'dosend') {
            foreach ($this->getSendElements() as $element => $options) {
                $options = $this->getSendElementValue($element, 'validation');
                if ($options !== '') {
                    $this->parseParserSendElementPHP($element, $options);
                }
            }

            if ($this->getTemplate()->Form()->hasErrorMessages() === true) {
                osWFrame\Core\Settings::setAction('send');
                osWFrame\Core\SessionMessageStack::addMessage('session', 'danger', [
                    'msg' => $this->getGroupMessage('validation_error'),
                ]);
            } else {
                foreach ($this->getSendElements() as $element => $options) {
                    $this->parseFinishSendElementPHP($element, $options);
                }

                foreach ($this->getFinishElements() as $element => $options) {
                    $this->parseFinishSendElementPHP($element, $options);
                }

                foreach ($this->getAfterFinishElements() as $element => $options) {
                    $this->parseFinishSendElementPHP($element, $options);
                }
            }
        }
    } else {
        osWFrame\Core\SessionMessageStack::addMessage('session', 'danger', [
            'msg' => $this->getGroupMessage('send_load_error_title'),
        ]);
        $this->direct($this->getDirectModule(), $this->getDirectParameters());
    }
}

$this->storeParameters();
