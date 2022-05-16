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

$ajax=[];
$ajax['init']=['index', 'header', 'ajaxfunc_send', 'ajaxfunc', 'navigation_id'];
$ajax['logic']['group_send']['view']=['ajaxfunc_send_name', 'ajaxfunc_send_email'];
$ajax['logic']['group_send']['rule']=['ajaxfunc_send'=>[1]];
$ajax['logic']['group_form']['view']=['ajaxfunc_form'];
$ajax['logic']['group_form']['rule']=['ajaxfunc_send'=>[0, 1]];
$ajax['logic']['group_form_kreis']['view']=['ajaxfunc_form_durchmesser'];
$ajax['logic']['group_form_kreis']['rule']=['ajaxfunc_form'=>['kreis']];
$ajax['logic']['group_form_rechteck']['view']=['ajaxfunc_form_laenge', 'ajaxfunc_form_breite'];
$ajax['logic']['group_form_rechteck']['rule']=['ajaxfunc_form'=>['rechteck']];
$ajax['logic']['group_form_quadrat']['view']=['ajaxfunc_form_seite'];
$ajax['logic']['group_form_quadrat']['rule']=['ajaxfunc_form'=>['quadrat']];

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
$ddm4_object['database']['table']='vis2_lab_ddm4_ajaxfunc';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='ajaxfunc_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['ajaxfunc_id']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'vis2_lab_ddm4_ajaxfunc', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Default'];

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
		 * Data: E-Mail senden
		 */
		$ddm4_elements['data']['ajaxfunc_send']=[];
		$ddm4_elements['data']['ajaxfunc_send']['module']='yesno';
		$ddm4_elements['data']['ajaxfunc_send']['title']='E-Mail senden';
		$ddm4_elements['data']['ajaxfunc_send']['name']='ajaxfunc_send';

		/*
		 * Data: Name
		 */
		$ddm4_elements['data']['ajaxfunc_send_name']=[];
		$ddm4_elements['data']['ajaxfunc_send_name']['module']='text';
		$ddm4_elements['data']['ajaxfunc_send_name']['title']='Name';
		$ddm4_elements['data']['ajaxfunc_send_name']['name']='ajaxfunc_send_name';
		$ddm4_elements['data']['ajaxfunc_send_name']['options']=[];
		$ddm4_elements['data']['ajaxfunc_send_name']['options']['order']=true;
		$ddm4_elements['data']['ajaxfunc_send_name']['options']['required']=true;
		$ddm4_elements['data']['ajaxfunc_send_name']['validation']=[];
		$ddm4_elements['data']['ajaxfunc_send_name']['validation']['length_min']=5;
		$ddm4_elements['data']['ajaxfunc_send_name']['validation']['length_max']=128;
		$ddm4_elements['data']['ajaxfunc_send_name']['_list']=[];

		/*
		 * Data: E-Mail
		 */
		$ddm4_elements['data']['ajaxfunc_send_email']=[];
		$ddm4_elements['data']['ajaxfunc_send_email']['module']='text';
		$ddm4_elements['data']['ajaxfunc_send_email']['title']='E-Mail';
		$ddm4_elements['data']['ajaxfunc_send_email']['name']='ajaxfunc_send_email';
		$ddm4_elements['data']['ajaxfunc_send_email']['options']=[];
		$ddm4_elements['data']['ajaxfunc_send_email']['options']['order']=true;
		$ddm4_elements['data']['ajaxfunc_send_email']['options']['required']=true;
		$ddm4_elements['data']['ajaxfunc_send_email']['validation']=[];
		$ddm4_elements['data']['ajaxfunc_send_email']['validation']['length_min']=5;
		$ddm4_elements['data']['ajaxfunc_send_email']['validation']['length_max']=128;
		$ddm4_elements['data']['ajaxfunc_send_email']['validation']['filter']=[];
		$ddm4_elements['data']['ajaxfunc_send_email']['validation']['filter']['email']=[];
		$ddm4_elements['data']['ajaxfunc_send_email']['_list']=[];

		/*
		 * Data: Form
		 */
		$ddm4_elements['data']['ajaxfunc_form']=[];
		$ddm4_elements['data']['ajaxfunc_form']['module']='select';
		$ddm4_elements['data']['ajaxfunc_form']['title']='Form';
		$ddm4_elements['data']['ajaxfunc_form']['name']='ajaxfunc_form';
		$ddm4_elements['data']['ajaxfunc_form']['options']=[];
		$ddm4_elements['data']['ajaxfunc_form']['options']['order']=true;
		$ddm4_elements['data']['ajaxfunc_form']['options']['required']=true;
		$ddm4_elements['data']['ajaxfunc_form']['options']['data']=[];
		$ddm4_elements['data']['ajaxfunc_form']['options']['data']['']='Bitte wählen';
		$ddm4_elements['data']['ajaxfunc_form']['options']['data']['kreis']='Kreis';
		$ddm4_elements['data']['ajaxfunc_form']['options']['data']['rechteck']='Rechteck';
		$ddm4_elements['data']['ajaxfunc_form']['options']['data']['quadrat']='Quadrat';
		$ddm4_elements['data']['ajaxfunc_form']['validation']=[];
		$ddm4_elements['data']['ajaxfunc_form']['validation']['length_min']=1;
		$ddm4_elements['data']['ajaxfunc_form']['validation']['length_max']=16;
		$ddm4_elements['data']['ajaxfunc_form']['_list']=[];

		/*
		 * Data: Durchmesser
		 */
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']=[];
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['module']='text';
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['title']='Durchmesser';
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['name']='ajaxfunc_form_durchmesser';
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['options']=[];
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['options']['required']=true;
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['validation']=[];
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['validation']['module']='integer';
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['validation']['length_min']=1;
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['validation']['length_max']=3;
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['validation']['value_min']=1;
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['validation']['value_max']=250;
		$ddm4_elements['data']['ajaxfunc_form_durchmesser']['_list']=[];

		/*
		 * Data: Länge
		 */
		$ddm4_elements['data']['ajaxfunc_form_laenge']=[];
		$ddm4_elements['data']['ajaxfunc_form_laenge']['module']='text';
		$ddm4_elements['data']['ajaxfunc_form_laenge']['title']='Länge';
		$ddm4_elements['data']['ajaxfunc_form_laenge']['name']='ajaxfunc_form_laenge';
		$ddm4_elements['data']['ajaxfunc_form_laenge']['options']=[];
		$ddm4_elements['data']['ajaxfunc_form_laenge']['options']['required']=true;
		$ddm4_elements['data']['ajaxfunc_form_laenge']['validation']=[];
		$ddm4_elements['data']['ajaxfunc_form_laenge']['validation']['module']='integer';
		$ddm4_elements['data']['ajaxfunc_form_laenge']['validation']['length_min']=1;
		$ddm4_elements['data']['ajaxfunc_form_laenge']['validation']['length_max']=3;
		$ddm4_elements['data']['ajaxfunc_form_laenge']['validation']['value_min']=1;
		$ddm4_elements['data']['ajaxfunc_form_laenge']['validation']['value_max']=250;
		$ddm4_elements['data']['ajaxfunc_form_laenge']['_list']=[];

		/*
		 * Data: Breite
		 */
		$ddm4_elements['data']['ajaxfunc_form_breite']=[];
		$ddm4_elements['data']['ajaxfunc_form_breite']['module']='text';
		$ddm4_elements['data']['ajaxfunc_form_breite']['title']='Breite';
		$ddm4_elements['data']['ajaxfunc_form_breite']['name']='ajaxfunc_form_breite';
		$ddm4_elements['data']['ajaxfunc_form_breite']['options']=[];
		$ddm4_elements['data']['ajaxfunc_form_breite']['options']['required']=true;
		$ddm4_elements['data']['ajaxfunc_form_breite']['validation']=[];
		$ddm4_elements['data']['ajaxfunc_form_breite']['validation']['module']='integer';
		$ddm4_elements['data']['ajaxfunc_form_breite']['validation']['length_min']=1;
		$ddm4_elements['data']['ajaxfunc_form_breite']['validation']['length_max']=3;
		$ddm4_elements['data']['ajaxfunc_form_breite']['validation']['value_min']=1;
		$ddm4_elements['data']['ajaxfunc_form_breite']['validation']['value_max']=250;
		$ddm4_elements['data']['ajaxfunc_form_breite']['_list']=[];

		/*
		 * Data: Seite
		 */
		$ddm4_elements['data']['ajaxfunc_form_seite']=[];
		$ddm4_elements['data']['ajaxfunc_form_seite']['module']='text';
		$ddm4_elements['data']['ajaxfunc_form_seite']['title']='Seite';
		$ddm4_elements['data']['ajaxfunc_form_seite']['name']='ajaxfunc_form_seite';
		$ddm4_elements['data']['ajaxfunc_form_seite']['options']=[];
		$ddm4_elements['data']['ajaxfunc_form_seite']['options']['required']=true;
		$ddm4_elements['data']['ajaxfunc_form_seite']['validation']=[];
		$ddm4_elements['data']['ajaxfunc_form_seite']['validation']['module']='integer';
		$ddm4_elements['data']['ajaxfunc_form_seite']['validation']['length_min']=1;
		$ddm4_elements['data']['ajaxfunc_form_seite']['validation']['length_max']=3;
		$ddm4_elements['data']['ajaxfunc_form_seite']['validation']['value_min']=1;
		$ddm4_elements['data']['ajaxfunc_form_seite']['validation']['value_max']=250;
		$ddm4_elements['data']['ajaxfunc_form_seite']['_list']=[];

		/*
		 * Data: ajaxfunc
		 */
		$ddm4_elements['data']['ajaxfunc']=[];
		$ddm4_elements['data']['ajaxfunc']['module']='ajaxfunc';
		$ddm4_elements['data']['ajaxfunc']['title']='ajaxfunc';
		$ddm4_elements['data']['ajaxfunc']['options']=[];
		$ddm4_elements['data']['ajaxfunc']['options']['data']=$ajax;
		break;
}

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