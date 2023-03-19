<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license MIT License
 */

if (\osWFrame\Core\Settings::getAction()=='dosend') {
	$this->setDoSendElementStorage($this->getSendElementOption($element, 'prefix').'create_time', $this->getSendElementOption($element, 'time'));
	$this->addSendElement($this->getSendElementOption($element, 'prefix').'create_time', ['module'=>'hidden', 'name'=>$this->getSendElementOption($element, 'prefix').'create_time']);
	$this->setDoSendElementStorage($this->getSendElementOption($element, 'prefix').'create_user_id', $this->getSendElementOption($element, 'user_id'));
	$this->addSendElement($this->getSendElementOption($element, 'prefix').'create_user_id', ['module'=>'hidden', 'name'=>$this->getSendElementOption($element, 'prefix').'create_user_id']);
	$this->setDoSendElementStorage($this->getSendElementOption($element, 'prefix').'update_time', $this->getSendElementOption($element, 'time'));
	$this->addSendElement($this->getSendElementOption($element, 'prefix').'update_time', ['module'=>'hidden', 'name'=>$this->getSendElementOption($element, 'prefix').'update_time']);
	$this->setDoSendElementStorage($this->getSendElementOption($element, 'prefix').'update_user_id', $this->getSendElementOption($element, 'user_id'));
	$this->addSendElement($this->getSendElementOption($element, 'prefix').'update_user_id', ['module'=>'hidden', 'name'=>$this->getSendElementOption($element, 'prefix').'update_user_id']);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>