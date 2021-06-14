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

if (\osWFrame\Core\Settings::getAction()=='doadd') {
	$this->setDoAddElementStorage($this->getAddElementOption($element, 'prefix').'create_time', $this->getAddElementOption($element, 'time'));
	$this->setDoAddElementStorage($this->getAddElementOption($element, 'prefix').'create_user_id', $this->getAddElementOption($element, 'user_id'));
	$this->setDoAddElementStorage($this->getAddElementOption($element, 'prefix').'update_time', $this->getAddElementOption($element, 'time'));
	$this->setDoAddElementStorage($this->getAddElementOption($element, 'prefix').'update_user_id', $this->getAddElementOption($element, 'user_id'));

	$this->addDataElement($this->getAddElementOption($element, 'prefix').'create_time', ['module'=>'hidden', 'name'=>$this->getAddElementOption($element, 'prefix').'create_time', 'options'=>['default_value'=>$this->getDoAddElementStorage($element, $this->getAddElementOption($element, 'prefix').'create_time')], 'validation'=>['module'=>'integer']]);
	$this->addDataElement($this->getAddElementOption($element, 'prefix').'create_user_id', ['module'=>'hidden', 'name'=>$this->getAddElementOption($element, 'prefix').'create_user_id', 'options'=>['default_value'=>$this->getDoAddElementStorage($element, $this->getAddElementOption($element, 'prefix').'create_user_id')], 'validation'=>['module'=>'integer']]);
	$this->addDataElement($this->getAddElementOption($element, 'prefix').'update_time', ['module'=>'hidden', 'name'=>$this->getAddElementOption($element, 'prefix').'update_time', 'options'=>['default_value'=>$this->getDoAddElementStorage($element, $this->getAddElementOption($element, 'prefix').'update_time')], 'validation'=>['module'=>'integer']]);
	$this->addDataElement($this->getAddElementOption($element, 'prefix').'update_user_id', ['module'=>'hidden', 'name'=>$this->getAddElementOption($element, 'prefix').'update_user_id', 'options'=>['default_value'=>$this->getDoAddElementStorage($element, $this->getAddElementOption($element, 'prefix').'update_user_id')], 'validation'=>['module'=>'integer']]);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>