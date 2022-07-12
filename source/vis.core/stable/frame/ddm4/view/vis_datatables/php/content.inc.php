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

foreach ($this->getListElements() as $element=>$options) {
	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/list/'.$options['module'].'/php/content.inc.php';
	if (file_exists($file)) {
		include $file;
	}
}

?>