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

$ar_tool_user=$this->getAddElementStorage(substr($element, 0, -6));
$ar_tool_user_do=$this->getDoAddElementStorage(substr($element, 0, -6));

$vis_time=time();
$vis_user_id=$this->getGroupOption('user_id', 'data');

if ($this->getFinishElementOption($element, 'manager')===true) {
	foreach (\VIS\Core\Manager::getTools() as $tool_id=>$tool_name) {
		if ((!isset($ar_tool_user[$tool_id]))||($ar_tool_user[$tool_id]!==$ar_tool_user_do[$tool_id])) {
			if ((isset($ar_tool_user_do[$tool_id]))&&($ar_tool_user_do[$tool_id]==1)) {
				\VIS\Core\Manager::addUserTool($this->getIndexElementStorage(), $tool_id, $vis_time, $vis_user_id);
			}
		}
	}
} else {
	if ((!isset($ar_tool_user[$this->getFinishElementOption($element, 'tool_id')]))||($ar_tool_user[$this->getFinishElementOption($element, 'tool_id')]!==$ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')])) {
		if ((isset($ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')]))&&($ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')]==1)) {
			\VIS\Core\Manager::addUserTool($this->getIndexElementStorage(), $this->getFinishElementOption($element, 'tool_id'), $vis_time, $vis_user_id);
		}
	}
}

?>