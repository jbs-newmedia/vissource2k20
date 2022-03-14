<?php

/**
 * This file is part of the VIS2:Manager package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:Manager
 * @link https://oswframe.com
 * @license MIT License
 */

$VIS2_Manager_Main=new \VIS2\Manager\Main($osW_Template);

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'vis_content.inc.php';
if (file_exists($file)) {
	require_once $file;
}

?>