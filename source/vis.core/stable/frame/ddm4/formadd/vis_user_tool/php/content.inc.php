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

$this->setAddElementStorage($element, []);

if (\osWFrame\Core\Settings::getAction()=='doadd') {
	$ar_user_tool=[];
	if ($this->getAddElementOption($element, 'manager')===true) {
		foreach (\VIS\Core\Manager::getTools() as $tool_id=>$tool_name) {
			if ((isset($_POST[$element.'_'.$tool_id]))&&($_POST[$element.'_'.$tool_id]==1)) {
				$ar_user_tool[$tool_id]=1;
			} else {
				$ar_user_tool[$tool_id]=0;
				if (!isset($_POST[$element.'_'.$tool_id])) {
					$_POST[$element.'_'.$tool_id]=0;
				}
			}
		}
	} else {
		if ((isset($_POST[$element.'_'.$this->getAddElementOption($element, 'tool_id')]))&&($_POST[$element.'_'.$this->getAddElementOption($element, 'tool_id')]==1)) {
			$ar_user_tool[$this->getAddElementOption($element, 'tool_id')]=1;
		} else {
			$ar_user_tool[$this->getAddElementOption($element, 'tool_id')]=0;
			if (!isset($_POST[$element.'_'.$this->getAddElementOption($element, 'tool_id')])) {
				$_POST[$element.'_'.$this->getAddElementOption($element, 'tool_id')]=0;
			}
		}
	}
	$this->setDoAddElementStorage($element, $ar_user_tool);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>