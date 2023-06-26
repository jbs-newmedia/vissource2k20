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

$ar_tool_user=$this->getEditElementStorage(substr($element, 0, -6));
$ar_tool_user_do=$this->getDoEditElementStorage(substr($element, 0, -6));

$element_storage='vis2_store_form_data';
$element_current='vis2_user_tool';

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

if ($this->getFinishElementOption($element, 'manager')===true) {
	foreach (\VIS2\Core\Manager::getTools() as $tool_id=>$tool_name) {
		if ((!isset($ar_tool_user[$tool_id]))||($ar_tool_user[$tool_id]!==$ar_tool_user_do[$tool_id])) {
			if ((isset($ar_tool_user_do[$tool_id]))&&($ar_tool_user_do[$tool_id]==1)) {
				\VIS2\Core\Manager::addUserTool($this->getIndexElementStorage(), $tool_id, $vis_time, $vis_user_id);
				if ($this->getGroupOption('enable_log')===true) {
					if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'])) {
							\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#0# '.$tool_name, '#1# '.$tool_name, $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'), $vis_user_id, $vis_time);
						}
					} else {
						\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#0# '.$tool_name, '#1# '.$tool_name);
					}
					\osWFrame\Core\DDM4_Log::writeValues($group, $this->getGroupOption('index', 'database'), $this->getIndexElementStorage(), $this->getGroupOption('connection_log', 'database'));
				}
			} elseif ((isset($ar_tool_user[$tool_id]))&&($ar_tool_user_do[$tool_id]==0)) {
				\VIS2\Core\Manager::delUserTool($this->getIndexElementStorage(), $tool_id);
				if ($this->getGroupOption('enable_log')===true) {
					if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'])) {
							\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#1# '.$tool_name, '#0# '.$tool_name, $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'), $vis_user_id, $vis_time);
						}
					} else {
						\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#1# '.$tool_name, '#0# '.$tool_name);
					}
					\osWFrame\Core\DDM4_Log::writeValues($group, $this->getGroupOption('index', 'database'), $this->getIndexElementStorage(), $this->getGroupOption('connection_log', 'database'));
				}
			}
		}
	}
} else {
	if ((!isset($ar_tool_user[$this->getFinishElementOption($element, 'tool_id')]))||($ar_tool_user[$this->getFinishElementOption($element, 'tool_id')]!==$ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')])) {
		if ((isset($ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')]))&&($ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')]==1)) {
			\VIS2\Core\Manager::addUserTool($this->getIndexElementStorage(), $this->getFinishElementOption($element, 'tool_id'), $vis_time, $vis_user_id);
			if ($this->getGroupOption('enable_log')===true) {
				if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'])) {
						\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#0# '.$this->getFinishElementOption($element, 'tool_name'), '#1# '.$this->getFinishElementOption($element, 'tool_name'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'), $vis_user_id, $vis_time);
					}
				} else {
					\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#0# '.$this->getFinishElementOption($element, 'tool_name'), '#1# '.$this->getFinishElementOption($element, 'tool_name'));
				}
				\osWFrame\Core\DDM4_Log::writeValues($group, $this->getGroupOption('index', 'database'), $this->getIndexElementStorage(), $this->getGroupOption('connection_log', 'database'));
			}
		} elseif ((isset($ar_tool_user[$this->getFinishElementOption($element, 'tool_id')]))&&($ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')]==0)) {
			\VIS2\Core\Manager::delUserTool($this->getIndexElementStorage(), $this->getFinishElementOption($element, 'tool_id'));
			if ($this->getGroupOption('enable_log')===true) {
				if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'])) {
						\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#1# '.$this->getFinishElementOption($element, 'tool_name'), '#0# '.$this->getFinishElementOption($element, 'tool_name'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix').'update_time'), $vis_user_id, $vis_time);
					}
				} else {
					\osWFrame\Core\DDM4_Log::addValue($group, $element_current, $this->getFinishElementValue($element, 'module'), '#1# '.$this->getFinishElementOption($element, 'tool_name'), '#0# '.$this->getFinishElementOption($element, 'tool_name'));
				}
				\osWFrame\Core\DDM4_Log::writeValues($group, $this->getGroupOption('index', 'database'), $this->getIndexElementStorage(), $this->getGroupOption('connection_log', 'database'));
			}
		}
	}
}

?>