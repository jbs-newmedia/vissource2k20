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

$ar_tool_user=$this->getAddElementStorage(substr($element, 0, -6));
$ar_tool_user_do=$this->getDoAddElementStorage(substr($element, 0, -6));

$vis_time=time();
$vis_user_id=$this->getGroupOption('user_id', 'data');

if ($this->getFinishElementOption($element, 'manager')===true) {
	foreach (\VIS2\Core\Manager::getTools() as $tool_id=>$tool_name) {
		if (isset($ar_tool_user_do[$tool_id])) {
			foreach ($ar_tool_user_do[$tool_id] as $mandant_id=>$flag) {
				if (((!isset($ar_tool_user[$tool_id]))||(!isset($ar_tool_user[$tool_id][$mandant_id])))||($ar_tool_user[$tool_id][$mandant_id]!==$flag)) {
					if ($flag==1) {
						\VIS2\Core\Manager::addUserMandant($this->getIndexElementStorage(), $mandant_id, $tool_id, $vis_time, $vis_user_id);
					}
				}
			}
		}
	}
} else {
	if (isset($ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')])) {
		foreach ($ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')] as $mandant_id=>$flag) {
			if (((!isset($ar_tool_user[$this->getFinishElementOption($element, 'tool_id')]))||(!isset($ar_tool_user[$this->getFinishElementOption($element, 'tool_id')][$mandant_id])))||($ar_tool_user[$this->getFinishElementOption($element, 'tool_id')][$mandant_id]!==$flag)) {
				if ($flag==1) {
					\VIS2\Core\Manager::addUserMandant($this->getIndexElementStorage(), $mandant_id, $this->getFinishElementOption($element, 'tool_id'), $vis_time, $vis_user_id);
				}
			}
		}
	}
}

?>