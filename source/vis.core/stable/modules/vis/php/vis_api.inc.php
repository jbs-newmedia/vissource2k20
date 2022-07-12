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

\osWFrame\Core\Settings::setBoolVar('vis_api_die', true);

$dir=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'actions_api';
$file=$dir.DIRECTORY_SEPARATOR.strtolower(\osWFrame\Core\Settings::getAction()).'.inc.php';

if ((file_exists($file))&&(dirname(realpath($file))==$dir)) {
	$script=strtolower(\osWFrame\Core\Settings::getAction());
} else {
	$script='';
}

if ($script!='') {
	require_once $file;
} else {
	echo 'VISAPI v0.3';
}

if (\osWFrame\Core\Settings::getBoolVar('vis_api_die')===true) {
	\osWFrame\Core\Settings::dieScript();
}

?>