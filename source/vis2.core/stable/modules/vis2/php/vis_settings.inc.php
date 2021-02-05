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

$settings_files=[];
$settings_files_g=glob(\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'settings'.DIRECTORY_SEPARATOR.'*.php');
foreach ($settings_files_g as $file) {
	$settings_files[substr(basename($file), 0, 3)]=$file;
}
$settings_files_l=glob(\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'settings'.DIRECTORY_SEPARATOR.'*.php');
foreach ($settings_files_l as $file) {
	$settings_files[substr(basename($file), 0, 3)]=$file;
}
ksort($settings_files);

/*
 * DDM4 initialisieren
 */
$ddm4_object=[];
$ddm4_object['general']=[];
$ddm4_object['general']['engine']='vis2_datatables';
$ddm4_object['general']['cache']=\osWFrame\Core\Settings::catchValue('ddm_cache', '', 'pg');
$ddm4_object['general']['elements_per_page']=50;
$ddm4_object['data']=[];
$ddm4_object['data']['user_id']=$VIS2_User->getId();
$ddm4_object['data']['tool']=$VIS2_Main->getTool();
$ddm4_object['data']['page']=$VIS2_Navigation->getPage();
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index_type']='integer';

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new \osWFrame\Core\DDM4($osW_Template, 'vis2_user', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Files initialisieren
 */
$settings_run='init';
$navigation_links=[];
foreach ($settings_files as $file) {
	include $file;
}
$settings_run='run';

/*
 * Navigationpunkte anlegen
 */

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));

if (!isset($settings_files[$ddm_navigation_id])) {
	reset($settings_files);
	$ddm_navigation_id=key($settings_files);
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

$osW_DDM4->setIndexElementStorage($VIS2_User->getId());

/*
* PreView: VIS2_Navigation
*/
$ddm4_elements['send']['vis2_navigation']=[];
$ddm4_elements['send']['vis2_navigation']['module']='vis2_navigation';
$ddm4_elements['send']['vis2_navigation']['options']=[];
$ddm4_elements['send']['vis2_navigation']['options']['data']=$navigation_links;

if (isset($settings_files[$ddm_navigation_id])) {
	$settings_run='run';
	include $settings_files[$ddm_navigation_id];
	$osW_DDM4->getTemplate()->setVar('ddm_navigation_id', $ddm_navigation_id);
}

/*
* Send: Submit
*/
$ddm4_elements['send']['submit']=[];
$ddm4_elements['send']['submit']['module']='submit';

/*
 * AfterFinish: VIS2_Direct
 */
$ddm4_elements['afterfinish']['vis2_direct']=[];
$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';

/*
 * Datenelemente hinzufügen
 */
foreach ($ddm4_elements as $key=>$ddm4_key_elements) {
	if ($ddm4_key_elements!==[]) {
		foreach ($ddm4_key_elements as $element_name=>$element_options) {
			$osW_DDM4->addElement($key, $element_name, $element_options);
		}
	}
}

/*
 * DDM4-Objekt Runtime
 */
$osW_DDM4->runDDMPHP();

/*
 * DDM4-Objekt an Template übergeben
 */
$osW_Template->setVar('osW_DDM4', $osW_DDM4);

?>