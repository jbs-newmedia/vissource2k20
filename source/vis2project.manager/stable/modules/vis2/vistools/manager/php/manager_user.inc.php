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

/**
 * DDM4 initialisieren
 */
$ddm4_object=[];
$ddm4_object['general']=[];
$ddm4_object['general']['engine']='vis2_datatables';
$ddm4_object['general']['cache']=\osWFrame\Core\Settings::catchValue('ddm_cache', '', 'pg');
$ddm4_object['general']['elements_per_page']=50;
$ddm4_object['general']['enable_log']=true;
$ddm4_object['general']['status_keys']=[];
$ddm4_object['general']['status_keys']['user_status']=[];
$ddm4_object['general']['status_keys']['user_status'][0]=['value'=>'0', 'class'=>'danger'];
$ddm4_object['data']=[];
$ddm4_object['data']['user_id']=$VIS2_User->getId();
$ddm4_object['data']['tool']=$VIS2_Main->getTool();
$ddm4_object['data']['page']=$VIS2_Navigation->getPage();
$ddm4_object['messages']=[];
$ddm4_object['messages']['createupdate_title']='Datensatzinformationen';
$ddm4_object['messages']['data_noresults']='Keine Benutzer vorhanden';
$ddm4_object['messages']['search_title']='Benutzer durchsuchen';
$ddm4_object['messages']['add_title']='Neuen Benutzer anlegen';
$ddm4_object['messages']['add_success_title']='Benutzer wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Benutzer konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Benutzer editieren';
$ddm4_object['messages']['edit_load_error_title']='Benutzer wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Benutzer wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Benutzer konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Benutzer löschen';
$ddm4_object['messages']['delete_load_error_title']='Benutzer wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Benutzer wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Benutzer konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='vis2_user';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='user_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['user_lastname']='asc';
$ddm4_object['database']['order']['user_firstname']='asc';
$ddm4_object['database']['order']['user_name']='asc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/**
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'vis2_user', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection($osW_DDM4->getGroupOption('connection', 'database'));
$QselectCount->prepare('SELECT count(user_id) AS counter FROM :table_vis2_user: WHERE user_status=:user_status:');
$QselectCount->bindTable(':table_vis2_user:', 'vis2_user');
$QselectCount->bindInt(':user_status:', 1);
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Aktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection($osW_DDM4->getGroupOption('connection', 'database'));
$QselectCount->prepare('SELECT count(user_id) AS counter FROM :table_vis2_user: WHERE user_status=:user_status:');
$QselectCount->bindTable(':table_vis2_user:', 'vis2_user');
$QselectCount->bindInt(':user_status:', 0);
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Inaktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection($osW_DDM4->getGroupOption('connection', 'database'));
$QselectCount->prepare('SELECT count(user_id) AS counter FROM :table_vis2_user: WHERE 1');
$QselectCount->bindTable(':table_vis2_user:', 'vis2_user');
$navigation_links[3]=['navigation_id'=>3, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Alle', 'counter'=>clone $QselectCount];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if (in_array($ddm_navigation_id, [1])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'user_status', 'operator'=>'=', 'value'=>1]]]], 'database');
}

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'user_status', 'operator'=>'=', 'value'=>0]]]], 'database');
}

if (in_array($ddm_navigation_id, [3])) {
	/* */
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
 * Data: Benutzername
 */
$ddm4_elements['data']['user_name']=[];
$ddm4_elements['data']['user_name']['module']='text';
$ddm4_elements['data']['user_name']['title']='Benutzername';
$ddm4_elements['data']['user_name']['name']='user_name';
$ddm4_elements['data']['user_name']['options']=[];
$ddm4_elements['data']['user_name']['options']['order']=true;
$ddm4_elements['data']['user_name']['options']['search']=true;
$ddm4_elements['data']['user_name']['options']['required']=true;
$ddm4_elements['data']['user_name']['validation']=[];
$ddm4_elements['data']['user_name']['validation']['module']='string';
$ddm4_elements['data']['user_name']['validation']['length_min']=2;
$ddm4_elements['data']['user_name']['validation']['length_max']=32;

/*
 * Data: Anrede
 */
$ddm4_elements['data']['user_form']=[];
$ddm4_elements['data']['user_form']['module']='select';
$ddm4_elements['data']['user_form']['title']='Anrede';
$ddm4_elements['data']['user_form']['name']='user_form';
$ddm4_elements['data']['user_form']['options']=[];
$ddm4_elements['data']['user_form']['options']['order']=true;
$ddm4_elements['data']['user_form']['options']['search']=true;
$ddm4_elements['data']['user_form']['options']['required']=true;
$ddm4_elements['data']['user_form']['options']['data']=['Herr'=>'Herr', 'Frau'=>'Frau'];
$ddm4_elements['data']['user_form']['validation']=[];
$ddm4_elements['data']['user_form']['validation']['module']='string';
$ddm4_elements['data']['user_form']['validation']['length_min']=4;
$ddm4_elements['data']['user_form']['validation']['length_max']=4;

/**
 * Data: Vorname
 */
$ddm4_elements['data']['user_firstname']=[];
$ddm4_elements['data']['user_firstname']['module']='text';
$ddm4_elements['data']['user_firstname']['title']='Vorname';
$ddm4_elements['data']['user_firstname']['name']='user_firstname';
$ddm4_elements['data']['user_firstname']['options']=[];
$ddm4_elements['data']['user_firstname']['options']['order']=true;
$ddm4_elements['data']['user_firstname']['options']['search']=true;
$ddm4_elements['data']['user_firstname']['options']['required']=true;
$ddm4_elements['data']['user_firstname']['validation']=[];
$ddm4_elements['data']['user_firstname']['validation']['module']='string';
$ddm4_elements['data']['user_firstname']['validation']['length_min']=2;
$ddm4_elements['data']['user_firstname']['validation']['length_max']=32;

/**
 * Data: Nachname
 */
$ddm4_elements['data']['user_lastname']=[];
$ddm4_elements['data']['user_lastname']['module']='text';
$ddm4_elements['data']['user_lastname']['title']='Nachname';
$ddm4_elements['data']['user_lastname']['name']='user_lastname';
$ddm4_elements['data']['user_lastname']['options']=[];
$ddm4_elements['data']['user_lastname']['options']['order']=true;
$ddm4_elements['data']['user_lastname']['options']['search']=true;
$ddm4_elements['data']['user_lastname']['options']['required']=true;
$ddm4_elements['data']['user_lastname']['validation']=[];
$ddm4_elements['data']['user_lastname']['validation']['module']='string';
$ddm4_elements['data']['user_lastname']['validation']['length_min']=2;
$ddm4_elements['data']['user_lastname']['validation']['length_max']=32;

/**
 * Data: Geschlecht
 */
$ddm4_elements['data']['user_gender']=[];
$ddm4_elements['data']['user_gender']['module']='select';
$ddm4_elements['data']['user_gender']['title']='Geschlecht';
$ddm4_elements['data']['user_gender']['name']='user_gender';
$ddm4_elements['data']['user_gender']['options']=[];
$ddm4_elements['data']['user_gender']['options']['order']=true;
$ddm4_elements['data']['user_gender']['options']['search']=true;
$ddm4_elements['data']['user_gender']['options']['required']=true;
$ddm4_elements['data']['user_gender']['options']['data']=['0'=>'Keine Angabe', '1'=>'Männlich', '2'=>'Weiblich'];
$ddm4_elements['data']['user_gender']['validation']=[];
$ddm4_elements['data']['user_gender']['validation']['module']='integer';
$ddm4_elements['data']['user_gender']['validation']['length_min']=1;
$ddm4_elements['data']['user_gender']['validation']['length_max']=2;
$ddm4_elements['data']['user_gender']['validation']['value_min']=1;
$ddm4_elements['data']['user_gender']['validation']['value_max']=2;
$ddm4_elements['data']['user_gender']['_list']=[];
$ddm4_elements['data']['user_gender']['_list']['enabled']=false;

/**
 * Data: E-Mail
 */
$ddm4_elements['data']['user_email']=[];
$ddm4_elements['data']['user_email']['module']='text';
$ddm4_elements['data']['user_email']['title']='E-Mail';
$ddm4_elements['data']['user_email']['name']='user_email';
$ddm4_elements['data']['user_email']['options']=[];
$ddm4_elements['data']['user_email']['options']['order']=true;
$ddm4_elements['data']['user_email']['options']['search']=true;
$ddm4_elements['data']['user_email']['options']['required']=true;
$ddm4_elements['data']['user_email']['validation']=[];
$ddm4_elements['data']['user_email']['validation']['module']='string';
$ddm4_elements['data']['user_email']['validation']['length_min']=5;
$ddm4_elements['data']['user_email']['validation']['length_max']=128;
$ddm4_elements['data']['user_email']['validation']['filter']=[];
$ddm4_elements['data']['user_email']['validation']['filter']['email_idna']=[];
$ddm4_elements['data']['user_email']['validation']['filter']['unique']=[];

/**
 * Data: Passwort
 */
$ddm4_elements['data']['user_password']=[];
$ddm4_elements['data']['user_password']['module']='password_double';
$ddm4_elements['data']['user_password']['title']='Passwort';
$ddm4_elements['data']['user_password']['name']='user_password';
$ddm4_elements['data']['user_password']['options']=[];
$ddm4_elements['data']['user_password']['options']['required']=true;
$ddm4_elements['data']['user_password']['options']['title_double']='Passwort (wdh)';
$ddm4_elements['data']['user_password']['validation']=[];
$ddm4_elements['data']['user_password']['validation']['module']='crypt';
$ddm4_elements['data']['user_password']['validation']['length_min']=\osWFrame\Core\Settings::getIntVar('vis2_user_password_length_min');
$ddm4_elements['data']['user_password']['validation']['length_max']=\osWFrame\Core\Settings::getIntVar('vis2_user_password_length_max');
$ddm4_elements['data']['user_password']['validation']['filter']=[];
$ddm4_elements['data']['user_password']['validation']['filter']['password_double']=[];
$ddm4_elements['data']['user_password']['_list']=[];
$ddm4_elements['data']['user_password']['_list']['enabled']=false;

/**
 * Data: Telefon
 */
$ddm4_elements['data']['user_phone']=[];
$ddm4_elements['data']['user_phone']['module']='text';
$ddm4_elements['data']['user_phone']['title']='Telefon';
$ddm4_elements['data']['user_phone']['name']='user_phone';
$ddm4_elements['data']['user_phone']['options']=[];
$ddm4_elements['data']['user_phone']['options']['order']=true;
$ddm4_elements['data']['user_phone']['options']['search']=true;
$ddm4_elements['data']['user_phone']['validation']=[];
$ddm4_elements['data']['user_phone']['validation']['module']='string';
$ddm4_elements['data']['user_phone']['validation']['length_min']=0;
$ddm4_elements['data']['user_phone']['validation']['length_max']=32;
$ddm4_elements['data']['user_phone']['validation']['preg']=\osWFrame\Core\Settings::getStringVar('vis2_user_phone_preg');
$ddm4_elements['data']['user_phone']['_list']=[];
$ddm4_elements['data']['user_phone']['_list']['enabled']=false;

/**
 * Data: Fax
 */
$ddm4_elements['data']['user_fax']=[];
$ddm4_elements['data']['user_fax']['module']='text';
$ddm4_elements['data']['user_fax']['title']='Telefax';
$ddm4_elements['data']['user_fax']['name']='user_fax';
$ddm4_elements['data']['user_fax']['options']=[];
$ddm4_elements['data']['user_fax']['options']['order']=true;
$ddm4_elements['data']['user_fax']['options']['search']=true;
$ddm4_elements['data']['user_fax']['validation']=[];
$ddm4_elements['data']['user_fax']['validation']['module']='string';
$ddm4_elements['data']['user_fax']['validation']['length_min']=0;
$ddm4_elements['data']['user_fax']['validation']['length_max']=32;
$ddm4_elements['data']['user_fax']['validation']['preg']=\osWFrame\Core\Settings::getStringVar('vis2_user_fax_preg');
$ddm4_elements['data']['user_fax']['_list']=[];
$ddm4_elements['data']['user_fax']['_list']['enabled']=false;

/**
 * Data: Mobile
 */
$ddm4_elements['data']['user_mobile']=[];
$ddm4_elements['data']['user_mobile']['module']='text';
$ddm4_elements['data']['user_mobile']['title']='Mobile';
$ddm4_elements['data']['user_mobile']['name']='user_mobile';
$ddm4_elements['data']['user_mobile']['options']=[];
$ddm4_elements['data']['user_mobile']['options']['order']=true;
$ddm4_elements['data']['user_mobile']['options']['search']=true;
$ddm4_elements['data']['user_mobile']['validation']=[];
$ddm4_elements['data']['user_mobile']['validation']['module']='string';
$ddm4_elements['data']['user_mobile']['validation']['length_min']=0;
$ddm4_elements['data']['user_mobile']['validation']['length_max']=32;
$ddm4_elements['data']['user_mobile']['validation']['preg']=\osWFrame\Core\Settings::getStringVar('vis2_user_mobile_preg');
$ddm4_elements['data']['user_mobile']['_list']=[];
$ddm4_elements['data']['user_mobile']['_list']['enabled']=false;

/**
 * Data: Status
 */
$ddm4_elements['data']['user_status']=[];
$ddm4_elements['data']['user_status']['module']='yesno';
$ddm4_elements['data']['user_status']['title']='Status';
$ddm4_elements['data']['user_status']['name']='user_status';
$ddm4_elements['data']['user_status']['options']=[];
$ddm4_elements['data']['user_status']['options']['order']=true;
$ddm4_elements['data']['user_status']['options']['search']=true;
$ddm4_elements['data']['user_status']['options']['required']=true;
$ddm4_elements['data']['user_status']['options']['text_yes']='Aktiviert';
$ddm4_elements['data']['user_status']['options']['text_no']='Deaktiviert';
$ddm4_elements['data']['user_status']['_list']=[];
$ddm4_elements['data']['user_status']['_list']['module']='hidden';

/**
 * Data: Tools
 */
$ddm4_elements['data']['vis2_user_tool']=[];
$ddm4_elements['data']['vis2_user_tool']['module']='vis2_user_tool';
$ddm4_elements['data']['vis2_user_tool']['title']='Tools';
$ddm4_elements['data']['vis2_user_tool']['options']=[];
$ddm4_elements['data']['vis2_user_tool']['options']['manager']=true;

/**
 * Data: Gruppen
 */
$ddm4_elements['data']['vis2_user_group']=[];
$ddm4_elements['data']['vis2_user_group']['module']='vis2_user_group';
$ddm4_elements['data']['vis2_user_group']['title']='Gruppen';
$ddm4_elements['data']['vis2_user_group']['options']=[];
$ddm4_elements['data']['vis2_user_group']['options']['manager']=true;

/*
 * Data: Gruppen
 */
$ddm4_elements['data']['vis2_user_mandant']=[];
$ddm4_elements['data']['vis2_user_mandant']['module']='vis2_user_mandant';
$ddm4_elements['data']['vis2_user_mandant']['title']='Mandanten';
$ddm4_elements['data']['vis2_user_mandant']['options']=[];
$ddm4_elements['data']['vis2_user_mandant']['options']['manager']=true;

/**
 * Data: VIS2_CreateUpdate
 */
$ddm4_elements['data']['vis2_createupdatestatus']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['options']['order']=true;
$ddm4_elements['data']['vis2_createupdatestatus']['options']['search']=true;
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='user_';
$ddm4_elements['data']['vis2_createupdatestatus']['options']['time']=time();
$ddm4_elements['data']['vis2_createupdatestatus']['options']['user_id']=$VIS2_User->getId();
$ddm4_elements['data']['vis2_createupdatestatus']['options']['text_yes']='Aktiviert';
$ddm4_elements['data']['vis2_createupdatestatus']['options']['text_no']='Deaktiviert';
$ddm4_elements['data']['vis2_createupdatestatus']['_list']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']['display_create_time']=false;
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']['display_create_user']=false;

/**
 * Data: Optionen
 */
$ddm4_elements['data']['options']=[];
$ddm4_elements['data']['options']['module']='options';
$ddm4_elements['data']['options']['title']='Optionen';

/**
 * Finish: VIS2_Store_Form_Data
 */
$ddm4_elements['finish']['vis2_store_form_data']=[];
$ddm4_elements['finish']['vis2_store_form_data']['module']='vis2_store_form_data';
$ddm4_elements['finish']['vis2_store_form_data']['options']=[];
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='user_';

/**
 * Finish: VIS2_User_Tool_Write
 */
$ddm4_elements['finish']['vis2_user_tool_write']=[];
$ddm4_elements['finish']['vis2_user_tool_write']['module']='vis2_user_tool_write';
$ddm4_elements['finish']['vis2_user_tool_write']['options']=[];
$ddm4_elements['finish']['vis2_user_tool_write']['options']['createupdatestatus_prefix']='user_';
$ddm4_elements['finish']['vis2_user_tool_write']['options']['manager']=true;

/**
 * Finish: VIS2_User_Group_Write
 */
$ddm4_elements['finish']['vis2_user_group_write']=[];
$ddm4_elements['finish']['vis2_user_group_write']['module']='vis2_user_group_write';
$ddm4_elements['finish']['vis2_user_group_write']['options']=[];
$ddm4_elements['finish']['vis2_user_group_write']['options']['createupdatestatus_prefix']='user_';
$ddm4_elements['finish']['vis2_user_group_write']['options']['manager']=true;

/*
 * Finish: VIS2_User_Mandant_Write
 */
$ddm4_elements['finish']['vis2_user_mandant_write']=[];
$ddm4_elements['finish']['vis2_user_mandant_write']['module']='vis2_user_mandant_write';
$ddm4_elements['finish']['vis2_user_mandant_write']['options']=[];
$ddm4_elements['finish']['vis2_user_mandant_write']['options']['createupdatestatus_prefix']='user_';
$ddm4_elements['finish']['vis2_user_mandant_write']['options']['manager']=true;

/**
 * Finish: VIS2_User_Delete
 */
$ddm4_elements['finish']['vis2_user_delete']=[];
$ddm4_elements['finish']['vis2_user_delete']['module']='vis2_user_delete';

/**
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

/**
 * DDM4-Objekt Runtime
 */
$osW_DDM4->runDDMPHP();

/**
 * DDM4-Objekt an Template übergeben
 */
$osW_Template->setVar('osW_DDM4', $osW_DDM4);

?>