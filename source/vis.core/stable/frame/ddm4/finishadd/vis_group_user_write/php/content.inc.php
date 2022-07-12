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

foreach ($ar_tool_user_do as $user_id=>$flag) {
	if ((!isset($ar_tool_user[$user_id]))||($ar_tool_user[$user_id]!==$flag)) {
		if ($flag==1) {
			\VIS\Core\Manager::addUserGroup($user_id, $this->getIndexElementStorage(), $this->getFinishElementOption($element, 'tool_id'), $vis_time, $vis_user_id);
		}
	}
}

?>