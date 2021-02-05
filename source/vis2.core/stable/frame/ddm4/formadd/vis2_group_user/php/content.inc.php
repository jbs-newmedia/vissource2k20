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

$this->setAddElementStorage($element, []);

if (\osWFrame\Core\Settings::getAction()=='doadd') {
	$ar_tool_user=[];
	foreach (\VIS2\Core\Manager::getUsers() as $user_id=>$user_name) {
		if ((isset($_POST[$element.'_'.$user_id]))&&($_POST[$element.'_'.$user_id]==1)) {
			$ar_tool_user[$user_id]=1;
		} else {
			$ar_tool_user[$user_id]=0;
			if (!isset($_POST[$element.'_'.$user_id])) {
				$_POST[$element.'_'.$user_id]=0;
			}
		}
	}
	$this->setDoAddElementStorage($element, $ar_tool_user);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>