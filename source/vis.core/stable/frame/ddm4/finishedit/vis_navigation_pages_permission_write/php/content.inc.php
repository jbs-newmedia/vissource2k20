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

$ar_navigation_permission=$this->getEditElementStorage(substr($element, 0, -6));
$ar_navigation_permission_do=$this->getDoEditElementStorage(substr($element, 0, -6));

$vis_time=time();
$vis_user_id=$this->getGroupOption('user_id', 'data');

$element_storage='vis_store_form_data';
$element_current='vis_navigation_pages_permission';

if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix')!='') {
	$vis_user_id=$this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id');
	$vis_time=$this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time');
} else {
	$vis_time=time();
	$vis_user_id=$this->getGroupOption('user_id', 'data');
}

if ($this->getFinishElementOption($element, 'group')!='') {
	$group=$this->getFinishElementOption($element, 'group');
} else {
	$group=$this->getGroupOption('table', 'database');
}

$permission_list=\VIS\Core\Manager::getPermissionTextList($this->getFinishElementOption($element, 'tool_id'));
foreach ($ar_navigation_permission_do as $permission_flag=>$flag) {
	if ((!isset($ar_navigation_permission[$permission_flag]))||($ar_navigation_permission[$permission_flag]!==$flag)) {
		if ($flag==1) {
			\VIS\Core\Manager::addPagePermission($this->getIndexElementStorage(), $this->getFinishElementOption($element, 'tool_id'), $permission_flag, $vis_time, $vis_user_id);
			if ($this->getGroupOption('enable_log')===true) {
				if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'])) {
						\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#0# '.$permission_list[$permission_flag], '#1# '.$permission_list[$permission_flag], $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#0# '.$permission_list[$permission_flag], '#1# '.$permission_list[$permission_flag]);
				}
				\osWFrame\Core\DDM4_Log::writeValues($group, $this->getGroupOption('index', 'database'), $this->getIndexElementStorage(), $this->getGroupOption('connection_log', 'database'));
			}
		} elseif ((isset($ar_navigation_permission[$permission_flag]))&&($flag==0)) {
			\VIS\Core\Manager::delPagePermission($this->getIndexElementStorage(), $this->getFinishElementOption($element, 'tool_id'), $permission_flag);
			if ($this->getGroupOption('enable_log')===true) {
				if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'])) {
						\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#1# '.$permission_list[$permission_flag], '#0# '.$permission_list[$permission_flag], $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#1# '.$permission_list[$permission_flag], '#0# '.$permission_list[$permission_flag]);
				}
				\osWFrame\Core\DDM4_Log::writeValues($group, $this->getGroupOption('index', 'database'), $this->getIndexElementStorage(), $this->getGroupOption('connection_log', 'database'));
			}
		}
	}
}

?>