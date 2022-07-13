<?php

/**
 * This file is part of the VIS:Manager package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS:Manager
 * @link https://oswframe.com
 * @license MIT License
 */

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'vis_dashboard.tpl.php';
if (file_exists($file)) {
	require_once $file;
}

?>