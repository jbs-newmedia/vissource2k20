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

$ar_permission=$this->getEditElementStorage(substr($element, 0, -6));
$ar_permission_do=$this->getDoEditElementStorage(substr($element, 0, -6));

$element_storage='vis2_store_form_data';
$element_current='vis2_group_permission';

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

$ar_data=[];
$ar_level=[];
$ar_data[0]='-';
foreach (\VIS2\Core\Manager::getNavigationReal(0, $this->getGroupOption('navigation_level'), $this->getFinishElementOption($element, 'tool_id')) as $navigation_element_1) {
	$ar_level[$navigation_element_1['info']['page_name_intern']]=0;
	$ar_data[$navigation_element_1['info']['page_name_intern']]=$navigation_element_1['info']['navigation_title'];
	if ($navigation_element_1['links']!=[]) {
		foreach ($navigation_element_1['links'] as $navigation_element_2) {
			$ar_level[$navigation_element_2['info']['page_name_intern']]=1;
			$ar_data[$navigation_element_2['info']['page_name_intern']]=$navigation_element_1['info']['navigation_title'].' ➥ '.$navigation_element_2['info']['navigation_title'];
			if ($navigation_element_2['links']!=[]) {
				foreach ($navigation_element_2['links'] as $navigation_element_3) {
					$ar_level[$navigation_element_3['info']['page_name_intern']]=2;
					$ar_data[$navigation_element_3['info']['page_name_intern']]=$navigation_element_1['info']['navigation_title'].' ➥ '.$navigation_element_2['info']['navigation_title'].' ➥ '.$navigation_element_3['info']['navigation_title'];
				}
			}
		}
	}
}
if (isset($ar_data['vis_api'])) {
	unset($ar_data['vis_api']);
}

$permission_list=\VIS2\Core\Manager::getPermissionTextList($this->getFinishElementOption($element, 'tool_id'));
foreach ($ar_permission_do as $permission_page=>$permissions) {
	foreach ($permissions as $permission=>$flag) {
		if (((!isset($ar_permission[$permission_page]))||(!isset($ar_permission[$permission_page][$permission])))||($ar_permission[$permission_page][$permission]!==$flag)) {
			if ($flag==1) {
				\VIS2\Core\Manager::addGroupPermission($this->getIndexElementStorage(), $permission_page, $permission, $vis_time, $vis_user_id);
				if ($this->getGroupOption('enable_log')===true) {
					if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'])) {
							\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#0# '.$ar_data[$permission_page].' : '.$permission_list[$permission], '#1# '.$ar_data[$permission_page].' : '.$permission_list[$permission], $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'));
						}
					} else {
						\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#0# '.$ar_data[$permission_page].' : '.$permission_list[$permission], '#1# '.$ar_data[$permission_page].' : '.$permission_list[$permission]);
					}
					\osWFrame\Core\DDM4_Log::writeValues($group, $this->getGroupOption('index', 'database'), $this->getIndexElementStorage());
				}
			} elseif (((isset($ar_permission[$permission_page]))&&(isset($ar_permission[$permission_page][$permission])))&&($flag==0)) {
				\VIS2\Core\Manager::delGroupPermission($this->getIndexElementStorage(), $permission_page, $permission);
				if ($this->getGroupOption('enable_log')===true) {
					if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'])) {
							\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#1# '.$ar_data[$permission_page].' : '.$permission_list[$permission], '#0# '.$ar_data[$permission_page].' : '.$permission_list[$permission], $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'));
						}
					} else {
						\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#1# '.$ar_data[$permission_page].' : '.$permission_list[$permission], '#0# '.$ar_data[$permission_page].' : '.$permission_list[$permission]);
					}
					\osWFrame\Core\DDM4_Log::writeValues($group, $this->getGroupOption('index', 'database'), $this->getIndexElementStorage());
				}
			}
		}
	}
}

?>