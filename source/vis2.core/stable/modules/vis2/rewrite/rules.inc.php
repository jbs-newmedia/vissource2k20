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

$check_parameters=false;
$parameters=$ar_parameters;
$go_default=false;

if (isset($parameters['vistool'])) {
	$base_uri.='vis2'.DIRECTORY_SEPARATOR.$parameters['vistool'].DIRECTORY_SEPARATOR;
} else {
	$base_uri.='vis2'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('vis2_login_module').DIRECTORY_SEPARATOR;
}

if ($seowrite_inpage==true) {
	global $VIS2_Navigation;
	$base_uri.=$VIS2_Navigation->getPage();
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