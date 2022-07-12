<?php

/**
 * This file is part of the VIS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS
 * @link https://oswframe.com
 * @license MIT License
 */

if ($this->getListElementOption($element, 'display_create_time')==true) {
	$_columns[$options['options']['prefix'].'create_time']=['name'=>$options['options']['prefix'].'create_time', 'order'=>(isset($_order[$options['options']['prefix'].'create_time']))?true:false, 'search'=>false,];
}

if ($this->getListElementOption($element, 'display_create_user')==true) {
	$_columns[$options['options']['prefix'].'create_user_id']=['name'=>$options['options']['prefix'].'create_user_id', 'order'=>(isset($_order[$options['options']['prefix'].'create_user_id']))?true:false, 'search'=>false,];
}

if ($this->getListElementOption($element, 'display_update_time')==true) {
	$_columns[$options['options']['prefix'].'update_time']=['name'=>$options['options']['prefix'].'update_time', 'order'=>(isset($_order[$options['options']['prefix'].'update_time']))?true:false, 'search'=>false,];
}

if ($this->getListElementOption($element, 'display_update_user')==true) {
	$_columns[$options['options']['prefix'].'update_user_id']=['name'=>$options['options']['prefix'].'update_user_id', 'order'=>(isset($_order[$options['options']['prefix'].'update_user_id']))?true:false, 'search'=>false,];
}

$this->setOrderElementName($this->getEditElementOption($element, 'prefix').'create_time', $this->getEditElementOption($element, 'text_create_time'));
$this->incCounter('list_view_elements');
$this->setOrderElementName($this->getEditElementOption($element, 'prefix').'create_user_id', $this->getEditElementOption($element, 'text_create_user'));
$this->incCounter('list_view_elements');

$this->setOrderElementName($this->getEditElementOption($element, 'prefix').'update_time', $this->getEditElementOption($element, 'text_update_time'));
$this->incCounter('list_view_elements');
$this->setOrderElementName($this->getEditElementOption($element, 'prefix').'update_user_id', $this->getEditElementOption($element, 'text_update_user'));
$this->incCounter('list_view_elements');

?>