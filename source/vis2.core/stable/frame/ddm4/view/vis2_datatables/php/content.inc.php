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

foreach ($this->getListElements() as $element=>$options) {
	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/list/'.$options['module'].'/php/content.inc.php';
	if (file_exists($file)) {
		include $file;
	}
}

?>