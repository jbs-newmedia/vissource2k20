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

$dashboard_files=[];
$dashboard_tpls=[];
$dashboard_files_g=glob(\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.'vis2'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'dashboard'.DIRECTORY_SEPARATOR.'*.php');
foreach ($dashboard_files_g as $file) {
	$dashboard_files[substr(basename($file), 0, 3)]=$file;
	$dashboard_tpls[substr(basename($file), 0, 3)]=str_replace([DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR, 'inc.php'], [DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR, 'tpl.php'], $file);
}
$dashboard_files_l=glob(\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.'vis2'.DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'dashboard'.DIRECTORY_SEPARATOR.'*.php');
foreach ($dashboard_files_l as $file) {
	$dashboard_files[substr(basename($file), 0, 3)]=$file;
	$dashboard_tpls[substr(basename($file), 0, 3)]=str_replace([DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR, 'inc.php'], [DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR, 'tpl.php'], $file);
}
ksort($dashboard_files);

$dashboard_run='init';
$navigation_links=[];
foreach ($dashboard_files as $file) {
	include $file;
}
$dashboard_run='run';

foreach ($dashboard_files as $file) {
	include $file;
}

$osW_Template->setVar('dashboard_tpls', $dashboard_tpls);

?>