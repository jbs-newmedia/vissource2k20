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

$check_parameters=false;
$parameters=$ar_parameters;
$go_default=false;

if (isset($parameters['vistool'])) {
	$base_uri.=\osWFrame\Core\Settings::getStringVar('vis_path').'/'.$parameters['vistool'].'/';
} else {
	$base_uri.=\osWFrame\Core\Settings::getStringVar('vis_path').'/'.\osWFrame\Core\Settings::getStringVar('vis_login_module').'/';
}

if ($seowrite_inpage==true) {
	global $VIS_Navigation;
	$base_uri.=$VIS_Navigation->getPage();
} else {
	if (isset($parameters['vispage'])) {
		$base_uri.=$parameters['vispage'];
	}
}

if (isset($parameters['module'])) {
	unset($parameters['module']);
}
if (isset($parameters['vistool'])) {
	unset($parameters['vistool']);
}
if (isset($parameters['vispage'])) {
	unset($parameters['vispage']);
}

$base_uri.='?';
foreach ($parameters as $key=>$value) {
	$base_uri.=$key.'='.$value.'&';
}
$base_uri=substr($base_uri, 0, -1);

if (isset($extend[1])) {
	$base_uri.='#'.$extend[1];
}

?>