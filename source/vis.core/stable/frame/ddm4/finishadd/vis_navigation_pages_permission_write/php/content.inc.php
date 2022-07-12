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

$ar_navigation_permission=$this->getAddElementStorage(substr($element, 0, -6));
$ar_navigation_permission_do=$this->getDoAddElementStorage(substr($element, 0, -6));

$vis_time=time();
$vis_user_id=$this->getGroupOption('user_id', 'data');

foreach ($ar_navigation_permission_do as $permission_flag=>$flag) {
	if ((!isset($ar_navigation_permission[$permission_flag]))||($ar_navigation_permission[$permission_flag]!==$flag)) {
		if ($flag==1) {
			\VIS\Core\Manager::addPagePermission($this->getIndexElementStorage(), $this->getFinishElementOption($element, 'tool_id'), $permission_flag, $vis_time, $vis_user_id);
		}
	}
}

?>