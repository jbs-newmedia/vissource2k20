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

$this->setAddElementStorage($element, []);

if (\osWFrame\Core\Settings::getAction()=='doadd') {
	$ar_navigation_permission=[];
	foreach (\VIS2\Core\Manager::getPermissionTextList($this->getAddElementOption($element, 'tool_id')) as $permission_flag=>$permission_name) {
		if ((isset($_POST[$element.'_'.$permission_flag]))&&($_POST[$element.'_'.$permission_flag]==1)) {
			$ar_navigation_permission[$permission_flag]=1;
		} else {
			$ar_navigation_permission[$permission_flag]=0;
			if (!isset($_POST[$element.'_'.$permission_flag])) {
				$_POST[$element.'_'.$permission_flag]=0;
			}
		}
	}
	$this->setDoAddElementStorage($element, $ar_navigation_permission);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>