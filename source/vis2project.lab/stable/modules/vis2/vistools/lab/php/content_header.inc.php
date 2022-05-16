<?php

/**
 * This file is part of the VIS2:Lab package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:Lab
 * @link https://oswframe.com
 * @license MIT License
 */

$run_counter=0;

foreach (glob(\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'lab_header'.DIRECTORY_SEPARATOR.'*.inc.php') as $file_header) {
	$run_counter++;
	$header=str_replace('.inc.php', '', basename($file_header));
	require_once $file_header;
}

?>