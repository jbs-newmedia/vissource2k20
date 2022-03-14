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

$data=['mm'=>'Michael Muster', 'am'=>'Annabelle Muster', 'jm'=>'Jürgen Müster',];

/*
 * DDM4 initialisieren
 */
$ddm4_object=[];
$ddm4_object['general']=[];
$ddm4_object['general']['engine']='vis2_datatables';
$ddm4_object['general']['cache']=\osWFrame\Core\Settings::catchValue('ddm_cache', '', 'pg');
$ddm4_object['general']['elements_per_page']=50;
$ddm4_object['general']['enable_log']=true;
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
$ddm4_object['database']['table']='vis2_lab_ddm4_multicheckbox';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='multicheckbox_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['multicheckbox_id']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'vis2_lab_ddm4_text', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Default'];
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Horizontal'];
$navigation_links[99]=['navigation_id'=>99, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'ReadOnly'];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if ($ddm_navigation_id<99) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'navigation_id', 'operator'=>'=', 'value'=>$ddm_navigation_id]]]], 'database');
}

/*
 * PreView: VIS2_Navigation
 */
$ddm4_elements['preview']['vis2_navigation']=[];
$ddm4_elements['preview']['vis2_navigation']['module']='vis2_navigation';
$ddm4_elements['preview']['vis2_navigation']['options']=[];
$ddm4_elements['preview']['vis2_navigation']['options']['data']=$navigation_links;

/*
 * View: VIS2_Datatables
 */
$ddm4_elements['view']['vis2_datatables']=[];
$ddm4_elements['view']['vis2_datatables']['module']='vis2_datatables';

/*
 * Elemente nach Navigation
 */
switch ($ddm_navigation_id) {
	case 1:
		/*
		 * Data: MultiCheckBox
		 */
		$ddm4_elements['data']['multicheckbox_example']=[];
		$ddm4_elements['data']['multicheckbox_example']['module']='multicheckbox';
		$ddm4_elements['data']['multicheckbox_example']['title']='MultiCheckBox';
		$ddm4_elements['data']['multicheckbox_example']['name']='multicheckbox_example';
		$ddm4_elements['data']['multicheckbox_example']['options']=[];
		$ddm4_elements['data']['multicheckbox_example']['options']['notice']='Default, ohne irgendwelche Einstellungen.';
		$ddm4_elements['data']['multicheckbox_example']['options']['ajax_enabled']=false;
		$ddm4_elements['data']['multicheckbox_example']['options']['data']=$data;
		break;
	case 2:
		/*
		 * Data: MultiCheckBox
		 */
		$ddm4_elements['data']['multicheckbox_example']=[];
		$ddm4_elements['data']['multicheckbox_example']['module']='multicheckbox';
		$ddm4_elements['data']['multicheckbox_example']['title']='MultiCheckBox';
		$ddm4_elements['data']['multicheckbox_example']['name']='multicheckbox_example';
		$ddm4_elements['data']['multicheckbox_example']['options']=[];
		$ddm4_elements['data']['multicheckbox_example']['options']['orientation']='horizontal';
		$ddm4_elements['data']['multicheckbox_example']['options']['notice']='Default, ohne irgendwelche Einstellungen.';
		$ddm4_elements['data']['multicheckbox_example']['options']['ajax_enabled']=false;
		$ddm4_elements['data']['multicheckbox_example']['options']['data']=$data;
		break;
	case 99:
		/*
		 * Data: MultiCheckBox
		 */
		$ddm4_elements['data']['multicheckbox_example']=[];
		$ddm4_elements['data']['multicheckbox_example']['module']='multicheckbox';
		$ddm4_elements['data']['multicheckbox_example']['title']='MultiCheckBox';
		$ddm4_elements['data']['multicheckbox_example']['name']='multicheckbox_example';
		$ddm4_elements['data']['multicheckbox_example']['options']=[];
		$ddm4_elements['data']['multicheckbox_example']['options']['read_only']=true;
		$ddm4_elements['data']['multicheckbox_example']['options']['data']=$data;
		$ddm4_elements['data']['multicheckbox_example']['options']['notice']='Nur lesend zugreifen';
		break;
}

/*
 * Data: Required
 */
$ddm4_elements['data']['multicheckbox_check']=[];
$ddm4_elements['data']['multicheckbox_check']['module']='text';
$ddm4_elements['data']['multicheckbox_check']['title']='Required';
$ddm4_elements['data']['multicheckbox_check']['name']='multicheckbox_check';
$ddm4_elements['data']['multicheckbox_check']['options']=[];
$ddm4_elements['data']['multicheckbox_check']['options']['required']=true;
$ddm4_elements['data']['multicheckbox_check']['validation']=[];
$ddm4_elements['data']['multicheckbox_check']['validation']['module']='string';
$ddm4_elements['data']['multicheckbox_check']['validation']['length_min']=1;
$ddm4_elements['data']['multicheckbox_check']['validation']['length_max']=16;

/*
 * Data: NavigationId
 */
$ddm4_elements['data']['navigation_id']=[];
$ddm4_elements['data']['navigation_id']['module']='hidden';
$ddm4_elements['data']['navigation_id']['name']='navigation_id';
$ddm4_elements['data']['navigation_id']['options']=[];
$ddm4_elements['data']['navigation_id']['options']['default_value']=$ddm_navigation_id;
$ddm4_elements['data']['navigation_id']['validation']=[];
$ddm4_elements['data']['navigation_id']['validation']['module']='integer';
$ddm4_elements['data']['navigation_id']['validation']['length_min']=1;
$ddm4_elements['data']['navigation_id']['validation']['length_max']=11;
$ddm4_elements['data']['navigation_id']['_view']=[];
$ddm4_elements['data']['navigation_id']['_view']['enabled']=false;
$ddm4_elements['data']['navigation_id']['_search']=[];
$ddm4_elements['data']['navigation_id']['_search']['enabled']=false;
$ddm4_elements['data']['navigation_id']['_edit']=[];
$ddm4_elements['data']['navigation_id']['_edit']['enabled']=false;
$ddm4_elements['data']['navigation_id']['_delete']=[];
$ddm4_elements['data']['navigation_id']['_delete']['enabled']=false;

/*
 * Data: VIS2_CreateUpdate
 */
$ddm4_elements['data']['vis2_createupdatestatus']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='element_';
$ddm4_elements['data']['vis2_createupdatestatus']['options']['time']=time();
$ddm4_elements['data']['vis2_createupdatestatus']['options']['user_id']=$VIS2_User->getId();
$ddm4_elements['data']['vis2_createupdatestatus']['_list']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']['display_create_time']=false;
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']['display_create_user']=false;

/*
 * Data: Optionen
 */
$ddm4_elements['data']['options']=[];
$ddm4_elements['data']['options']['module']='options';
$ddm4_elements['data']['options']['title']='Optionen';

/*
 * Finish: VIS2_Store_Form_Data
 */
$ddm4_elements['finish']['vis2_store_form_data']=[];
$ddm4_elements['finish']['vis2_store_form_data']['module']='vis2_store_form_data';
$ddm4_elements['finish']['vis2_store_form_data']['options']=[];
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='element_';

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