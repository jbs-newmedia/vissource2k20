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
	$ar_permission=[];
	foreach (\VIS\Core\Manager::getNavigationRealUnsorted($this->getAddElementOption($element, 'tool_id')) as $navigation_element) {
		if (count($navigation_element['permission'])>0) {
			foreach ($navigation_element['permission'] as $flag) {
				if ((isset($_POST['page_'.$navigation_element['page_name_intern'].'_'.$flag]))&&($_POST['page_'.$navigation_element['page_name_intern'].'_'.$flag]==1)) {
					$ar_permission[$navigation_element['page_name_intern']][$flag]=1;
				} else {
					$ar_permission[$navigation_element['page_name_intern']][$flag]=0;
					if (!isset($_POST['page_'.$navigation_element['page_name_intern'].'_'.$flag])) {
						$_POST['page_'.$navigation_element['page_name_intern'].'_'.$flag]=0;
					}
				}
			}
		}
	}
	$this->setDoAddElementStorage($element, $ar_permission);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>