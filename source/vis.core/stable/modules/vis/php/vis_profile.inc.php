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

$profile_files=[];
$profile_files_g=glob(\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'profile'.DIRECTORY_SEPARATOR.'*.php');
foreach ($profile_files_g as $file) {
	$profile_files[substr(basename($file), 0, 3)]=$file;
}
$profile_files_l=glob(\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'profile'.DIRECTORY_SEPARATOR.'*.php');
foreach ($profile_files_l as $file) {
	$profile_files[substr(basename($file), 0, 3)]=$file;
}
ksort($profile_files);

/*
 * DDM4 initialisieren
 */
$ddm4_object=[];
$ddm4_object['general']=[];
$ddm4_object['general']['engine']='vis_datatables';
$ddm4_object['general']['cache']=\osWFrame\Core\Settings::catchValue('ddm_cache', '', 'pg');
$ddm4_object['general']['elements_per_page']=50;
$ddm4_object['data']=[];
$ddm4_object['data']['user_id']=$VIS_User->getId();
$ddm4_object['data']['tool']=$VIS_Main->getTool();
$ddm4_object['data']['page']=$VIS_Navigation->getPage();
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index_type']='integer';

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new \osWFrame\Core\DDM4($osW_Template, 'vis_user', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Files initialisieren
 */
$profile_run='init';
$navigation_links=[];
foreach ($profile_files as $file) {
	include $file;
}
$profile_run='run';

/*
 * Navigationpunkte anlegen
 */

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));

if (($profile_files!=[])&&(!isset($profile_files[$ddm_navigation_id]))) {
	reset($profile_files);
	$ddm_navigation_id=key($profile_files);
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

$osW_DDM4->setIndexElementStorage($VIS_User->getId());

/*
* PreView: VIS_Navigation
*/
$ddm4_elements['send']['vis_navigation']=[];
$ddm4_elements['send']['vis_navigation']['module']='vis_navigation';
$ddm4_elements['send']['vis_navigation']['options']=[];
$ddm4_elements['send']['vis_navigation']['options']['data']=$navigation_links;

if (isset($profile_files[$ddm_navigation_id])) {
	$profile_run='run';
	include $profile_files[$ddm_navigation_id];
	$osW_DDM4->getTemplate()->setVar('ddm_navigation_id', $ddm_navigation_id);
}

/*
* Send: Submit
*/
$ddm4_elements['send']['submit']=[];
$ddm4_elements['send']['submit']['module']='submit';

/*
 * AfterFinish: VIS_Direct
 */
$ddm4_elements['afterfinish']['vis_direct']=[];
$ddm4_elements['afterfinish']['vis_direct']['module']='vis_direct';

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