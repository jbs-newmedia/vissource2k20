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

$dir=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/tpl/actions_api';
$file=$dir.DIRECTORY_SEPARATOR.osW_Settings::getInstance()->getAction().'.tpl.php';

osW_Settings::getInstance()->vis_api_die=true;

if (file_exists($file)&&dirname(realpath($file))==$dir) {
	$script=osW_Settings::getInstance()->getAction();
} else {
	$script='';
}

if ($script!='') {
	include $file;
}

?>