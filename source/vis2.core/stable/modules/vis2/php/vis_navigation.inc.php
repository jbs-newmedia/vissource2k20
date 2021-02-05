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

/*
 * DDM4 initialisieren
 */
$ddm4_object=[];
$ddm4_object['general']=[];
$ddm4_object['general']['engine']='ddm4_formular';
$ddm4_object['general']['cache']=\osWFrame\Core\Settings::catchValue('ddm_cache', '', 'pg');
$ddm4_object['general']['elements_per_page']=50;
$ddm4_object['general']['enable_log']=true;
$ddm4_object['data']=[];
$ddm4_object['data']['user_id']=$VIS2_User->getId();
$ddm4_object['data']['tool']=$VIS2_Main->getTool();
$ddm4_object['data']['page']=$VIS2_Navigation->getPage();
$ddm4_object['messages']=[];
$ddm4_object['messages']['send_title']='Programm wählen';
$ddm4_object['messages']['send_success_title']='Programm wurde erfolgreich angelegt';
$ddm4_object['messages']['send_error_title']='Programm konnte nicht angelegt werden';
$ddm4_object['messages']['send_title']='Programm wählen';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='vis2_group';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='group_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['group_name_intern']='asc';

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new \osWFrame\Core\DDM4($osW_Template, 'vis2_navigation', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$tool_details=$VIS2_Main->getToolDetails();
$navigation_links=[];
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Rechte',];
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Seiten',];
$navigation_links[3]=['navigation_id'=>3, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Navigation',];
$navigation_links[4]=['navigation_id'=>4, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Gruppen',];
if ($tool_details['tool_use_mandant']==1) {
	$navigation_links[5]=['navigation_id'=>5, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Mandanten',];
}

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=3;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

/*
 * Rechte
 */
if (in_array($ddm_navigation_id, [1])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'vis2_permission', 'database');
	$osW_DDM4->setGroupOption('index', 'permission_id', 'database');
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'tool_id', 'operator'=>'=', 'value'=>$VIS2_Main->getToolId()]]]], 'database');
	$osW_DDM4->setGroupOption('order', ['permission_flag'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['permission_ispublic'=>[['value'=>'Deaktiviert', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine Rechte vorhanden';
	$messages['search_title']='Rechte durchsuchen';
	$messages['add_title']='Neues Recht anlegen';
	$messages['add_success_title']='Recht wurde erfolgreich angelegt';
	$messages['add_error_title']='Recht konnte nicht angelegt werden';
	$messages['edit_title']='Recht editieren';
	$messages['edit_load_error_title']='Recht wurde nicht gefunden';
	$messages['edit_success_title']='Recht wurde erfolgreich editiert';
	$messages['edit_error_title']='Recht konnte nicht editiert werden';
	$messages['delete_title']='Recht löschen';
	$messages['delete_load_error_title']='Recht wurde nicht gefunden';
	$messages['delete_success_title']='Recht wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Recht konnte nicht gelöscht werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

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
	 * Data: Flag
	 */
	$ddm4_elements['data']['permission_flag']=[];
	$ddm4_elements['data']['permission_flag']['module']='text';
	$ddm4_elements['data']['permission_flag']['title']='Flag';
	$ddm4_elements['data']['permission_flag']['name']='permission_flag';
	$ddm4_elements['data']['permission_flag']['options']=[];
	$ddm4_elements['data']['permission_flag']['options']['order']=true;
	$ddm4_elements['data']['permission_flag']['options']['required']=true;
	$ddm4_elements['data']['permission_flag']['options']['search']=true;
	$ddm4_elements['data']['permission_flag']['validation']=[];
	$ddm4_elements['data']['permission_flag']['validation']['module']='string';
	$ddm4_elements['data']['permission_flag']['validation']['length_min']=2;
	$ddm4_elements['data']['permission_flag']['validation']['length_max']=16;
	$ddm4_elements['data']['permission_flag']['validation']['filter']=[];
	$ddm4_elements['data']['permission_flag']['validation']['filter']['unique_filter']=[];

	/*
	 * Data: Titel
	 */
	$ddm4_elements['data']['permission_title']=[];
	$ddm4_elements['data']['permission_title']['module']='text';
	$ddm4_elements['data']['permission_title']['title']='Titel';
	$ddm4_elements['data']['permission_title']['name']='permission_title';
	$ddm4_elements['data']['permission_title']['options']=[];
	$ddm4_elements['data']['permission_title']['options']['order']=true;
	$ddm4_elements['data']['permission_title']['options']['required']=true;
	$ddm4_elements['data']['permission_title']['options']['search']=true;
	$ddm4_elements['data']['permission_title']['validation']=[];
	$ddm4_elements['data']['permission_title']['validation']['module']='string';
	$ddm4_elements['data']['permission_title']['validation']['length_min']=2;
	$ddm4_elements['data']['permission_title']['validation']['length_max']=128;

	/*
	 * Data: Status
	 */
	$ddm4_elements['data']['permission_ispublic']=[];
	$ddm4_elements['data']['permission_ispublic']['module']='yesno';
	$ddm4_elements['data']['permission_ispublic']['title']='Status';
	$ddm4_elements['data']['permission_ispublic']['name']='permission_ispublic';
	$ddm4_elements['data']['permission_ispublic']['options']=[];
	$ddm4_elements['data']['permission_ispublic']['options']['default_value']=1;
	$ddm4_elements['data']['permission_ispublic']['options']['required']=true;
	$ddm4_elements['data']['permission_ispublic']['options']['order']=true;
	$ddm4_elements['data']['permission_ispublic']['options']['text_yes']='Aktiviert';
	$ddm4_elements['data']['permission_ispublic']['options']['text_no']='Deaktiviert';

	/*
	 * Data: ToolId
	 */
	$ddm4_elements['data']['tool_id']=[];
	$ddm4_elements['data']['tool_id']['module']='hidden';
	$ddm4_elements['data']['tool_id']['title']='ToolId';
	$ddm4_elements['data']['tool_id']['name']='tool_id';
	$ddm4_elements['data']['tool_id']['options']=[];
	$ddm4_elements['data']['tool_id']['options']['default_value']=$VIS2_Main->getToolId();
	$ddm4_elements['data']['tool_id']['validation']=[];
	$ddm4_elements['data']['tool_id']['validation']['module']='integer';
	$ddm4_elements['data']['tool_id']['validation']['length_min']=1;
	$ddm4_elements['data']['tool_id']['validation']['length_max']=11;
	$ddm4_elements['data']['tool_id']['_view']=[];
	$ddm4_elements['data']['tool_id']['_view']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_search']=[];
	$ddm4_elements['data']['tool_id']['_search']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_edit']=[];
	$ddm4_elements['data']['tool_id']['_edit']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_delete']=[];
	$ddm4_elements['data']['tool_id']['_delete']['enabled']=false;

	/*
	 * Data: VIS2_CreateUpdate
	 */
	$ddm4_elements['data']['vis2_createupdatestatus']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
	$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
	$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='permission_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='permission_';

	/*
	 * Finish: VIS2_Navigation_Permission_Delete
	 */
	$ddm4_elements['finish']['vis2_navigation_permission_delete']=[];
	$ddm4_elements['finish']['vis2_navigation_permission_delete']['module']='vis2_navigation_permission_delete';
	$ddm4_elements['finish']['vis2_navigation_permission_delete']['options']=[];
	$ddm4_elements['finish']['vis2_navigation_permission_delete']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Seiten
 */
if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'vis2_page', 'database');
	$osW_DDM4->setGroupOption('index', 'page_id', 'database');
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'tool_id', 'operator'=>'=', 'value'=>$VIS2_Main->getToolId()]]]], 'database');
	$osW_DDM4->setGroupOption('order', ['page_name_intern'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['page_ispublic'=>[['value'=>'Deaktiviert', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine Seiten vorhanden';
	$messages['search_title']='Seiten durchsuchen';
	$messages['add_title']='Neue Seite anlegen';
	$messages['add_success_title']='Seite wurde erfolgreich angelegt';
	$messages['add_error_title']='Seite konnte nicht angelegt werden';
	$messages['edit_title']='Seite editieren';
	$messages['edit_load_error_title']='Seite wurde nicht gefunden';
	$messages['edit_success_title']='Seite wurde erfolgreich editiert';
	$messages['edit_error_title']='Seite konnte nicht editiert werden';
	$messages['delete_title']='Seite löschen';
	$messages['delete_load_error_title']='Seite wurde nicht gefunden';
	$messages['delete_success_title']='Seite wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Seite konnte nicht gelöscht werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

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
	 * Data: Name
	 */
	$ddm4_elements['data']['page_name']=[];
	$ddm4_elements['data']['page_name']['module']='text';
	$ddm4_elements['data']['page_name']['title']='Name';
	$ddm4_elements['data']['page_name']['name']='page_name';
	$ddm4_elements['data']['page_name']['options']=[];
	$ddm4_elements['data']['page_name']['options']['order']=true;
	$ddm4_elements['data']['page_name']['options']['required']=true;
	$ddm4_elements['data']['page_name']['options']['search']=true;
	$ddm4_elements['data']['page_name']['validation']=[];
	$ddm4_elements['data']['page_name']['validation']['module']='string';
	$ddm4_elements['data']['page_name']['validation']['length_min']=2;
	$ddm4_elements['data']['page_name']['validation']['length_max']=32;

	/*
	 * Data: Name Intern
	 */
	$ddm4_elements['data']['page_name_intern']=[];
	$ddm4_elements['data']['page_name_intern']['module']='text';
	$ddm4_elements['data']['page_name_intern']['title']='Name Intern';
	$ddm4_elements['data']['page_name_intern']['name']='page_name_intern';
	$ddm4_elements['data']['page_name_intern']['options']=[];
	$ddm4_elements['data']['page_name_intern']['options']['order']=true;
	$ddm4_elements['data']['page_name_intern']['options']['required']=true;
	$ddm4_elements['data']['page_name_intern']['options']['search']=true;
	$ddm4_elements['data']['page_name_intern']['options']['notice']='Nur a-z, 0-9 und "_". Nach Speichern nicht änderbar.';
	$ddm4_elements['data']['page_name_intern']['validation']=[];
	$ddm4_elements['data']['page_name_intern']['validation']['module']='string';
	$ddm4_elements['data']['page_name_intern']['validation']['length_min']=2;
	$ddm4_elements['data']['page_name_intern']['validation']['length_max']=32;
	$ddm4_elements['data']['page_name_intern']['validation']['preg']='/^[a-z0-9_]+$/';
	$ddm4_elements['data']['page_name_intern']['validation']['filter']=[];
	$ddm4_elements['data']['page_name_intern']['validation']['filter']['unique_filter']=[];
	$ddm4_elements['data']['page_name_intern']['_edit']=[];
	$ddm4_elements['data']['page_name_intern']['_edit']['options']=[];
	$ddm4_elements['data']['page_name_intern']['_edit']['options']['read_only']=true;
	$ddm4_elements['data']['page_name_intern']['_edit']['options']['required']=false;
	$ddm4_elements['data']['page_name_intern']['_edit']['options']['notice']='';
	$ddm4_elements['data']['page_name_intern']['_delete']=[];
	$ddm4_elements['data']['page_name_intern']['_delete']['options']=[];
	$ddm4_elements['data']['page_name_intern']['_delete']['options']['notice']='';

	/*
	 * Data: Beschreibung
	 */
	$ddm4_elements['data']['page_description']=[];
	$ddm4_elements['data']['page_description']['module']='text';
	$ddm4_elements['data']['page_description']['title']='Beschreibung';
	$ddm4_elements['data']['page_description']['name']='page_description';
	$ddm4_elements['data']['page_description']['options']=[];
	$ddm4_elements['data']['page_description']['options']['order']=true;
	$ddm4_elements['data']['page_description']['options']['required']=true;
	$ddm4_elements['data']['page_description']['options']['search']=true;
	$ddm4_elements['data']['page_description']['validation']=[];
	$ddm4_elements['data']['page_description']['validation']['module']='string';
	$ddm4_elements['data']['page_description']['validation']['length_min']=0;
	$ddm4_elements['data']['page_description']['validation']['length_max']=64;

	/*
	 * Data: Rechte
	 */
	$ddm4_elements['data']['vis2_navigation_pages_permission']=[];
	$ddm4_elements['data']['vis2_navigation_pages_permission']['module']='vis2_navigation_pages_permission';
	$ddm4_elements['data']['vis2_navigation_pages_permission']['title']='Rechte';
	$ddm4_elements['data']['vis2_navigation_pages_permission']['options']=[];
	$ddm4_elements['data']['vis2_navigation_pages_permission']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * Data: Status
	 */
	$ddm4_elements['data']['page_ispublic']=[];
	$ddm4_elements['data']['page_ispublic']['module']='yesno';
	$ddm4_elements['data']['page_ispublic']['title']='Status';
	$ddm4_elements['data']['page_ispublic']['name']='page_ispublic';
	$ddm4_elements['data']['page_ispublic']['options']=[];
	$ddm4_elements['data']['page_ispublic']['options']['default_value']=1;
	$ddm4_elements['data']['page_ispublic']['options']['required']=true;
	$ddm4_elements['data']['page_ispublic']['options']['order']=true;
	$ddm4_elements['data']['page_ispublic']['options']['text_yes']='Aktiviert';
	$ddm4_elements['data']['page_ispublic']['options']['text_no']='Deaktiviert';

	/*
	 * Data: ToolId
	 */
	$ddm4_elements['data']['tool_id']=[];
	$ddm4_elements['data']['tool_id']['module']='hidden';
	$ddm4_elements['data']['tool_id']['title']='ToolId';
	$ddm4_elements['data']['tool_id']['name']='tool_id';
	$ddm4_elements['data']['tool_id']['options']=[];
	$ddm4_elements['data']['tool_id']['options']['default_value']=$VIS2_Main->getToolId();
	$ddm4_elements['data']['tool_id']['validation']=[];
	$ddm4_elements['data']['tool_id']['validation']['module']='integer';
	$ddm4_elements['data']['tool_id']['validation']['length_min']=1;
	$ddm4_elements['data']['tool_id']['validation']['length_max']=11;
	$ddm4_elements['data']['tool_id']['_view']=[];
	$ddm4_elements['data']['tool_id']['_view']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_search']=[];
	$ddm4_elements['data']['tool_id']['_search']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_edit']=[];
	$ddm4_elements['data']['tool_id']['_edit']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_delete']=[];
	$ddm4_elements['data']['tool_id']['_delete']['enabled']=false;

	/*
	 * Data: Datensatzinformationen
	 */
	$ddm4_elements['data']['vis2_createupdatestatus']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
	$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
	$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='page_';
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['time']=1597931923;
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='page_';

	/*
	 * Finish: VIS2_Navigation_Pages_Permission_Write
	 */
	$ddm4_elements['finish']['vis2_navigation_pages_permission_write']=[];
	$ddm4_elements['finish']['vis2_navigation_pages_permission_write']['module']='vis2_navigation_pages_permission_write';
	$ddm4_elements['finish']['vis2_navigation_pages_permission_write']['options']=[];
	$ddm4_elements['finish']['vis2_navigation_pages_permission_write']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * Finish: VIS2_Navigation_Pages_Permission_Delete
	 */
	$ddm4_elements['finish']['vis2_navigation_pages_permission_delete']=[];
	$ddm4_elements['finish']['vis2_navigation_pages_permission_delete']['module']='vis2_navigation_pages_permission_delete';
	$ddm4_elements['finish']['vis2_navigation_pages_permission_delete']['options']=[];
	$ddm4_elements['finish']['vis2_navigation_pages_permission_delete']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';

}

/*
 * Navigation
 */
if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('index_parent', 'navigation_parent_id');
	$osW_DDM4->setGroupOption('navigation_level', '3');
	$osW_DDM4->setGroupOption('table', 'vis2_navigation', 'database');
	$osW_DDM4->setGroupOption('index', 'navigation_id', 'database');
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'tool_id', 'operator'=>'=', 'value'=>$VIS2_Main->getToolId()]]]], 'database');
	$osW_DDM4->setGroupOption('order', ['navigation_intern_sortorder'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['navigation_ispublic'=>[['value'=>'Deaktiviert', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine Navigation vorhanden';
	$messages['search_title']='Navigation durchsuchen';
	$messages['add_title']='Neue Navigation anlegen';
	$messages['add_success_title']='Navigation wurde erfolgreich angelegt';
	$messages['add_error_title']='Navigation konnte nicht angelegt werden';
	$messages['edit_title']='Navigation editieren';
	$messages['edit_load_error_title']='Navigation wurde nicht gefunden';
	$messages['edit_success_title']='Navigation wurde erfolgreich editiert';
	$messages['edit_error_title']='Navigation konnte nicht editiert werden';
	$messages['delete_title']='Navigation löschen';
	$messages['delete_load_error_title']='Navigation wurde nicht gefunden';
	$messages['delete_success_title']='Navigation wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Navigation konnte nicht gelöscht werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

	$ar_data=[];
	$ar_level=[];
	$ar_data[0]='-';
	foreach (\VIS2\Core\Manager::getNavigationReal(0, $osW_DDM4->getGroupOption('navigation_level'), $VIS2_Main->getToolId()) as $navigation_element_1) {
		$ar_level[$navigation_element_1['info']['navigation_id']]=0;
		$ar_data[$navigation_element_1['info']['navigation_id']]=$navigation_element_1['info']['navigation_title'];
		if ($navigation_element_1['links']!=[]) {
			foreach ($navigation_element_1['links'] as $navigation_element_2) {
				$ar_level[$navigation_element_2['info']['navigation_id']]=1;
				$ar_data[$navigation_element_2['info']['navigation_id']]=$navigation_element_1['info']['navigation_title'].' ➥ '.$navigation_element_2['info']['navigation_title'];
				if ($navigation_element_2['links']!=[]) {
					foreach ($navigation_element_2['links'] as $navigation_element_3) {
						$ar_level[$navigation_element_3['info']['navigation_id']]=2;
					}
				}
			}
		}
	}
	if (isset($ar_data['vis_api'])) {
		unset($ar_data['vis_api']);
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
	 * Data: Überseite
	 */
	$ddm4_elements['data']['navigation_parent_id']=[];
	$ddm4_elements['data']['navigation_parent_id']['module']='select';
	$ddm4_elements['data']['navigation_parent_id']['title']='Überseite';
	$ddm4_elements['data']['navigation_parent_id']['name']='navigation_parent_id';
	$ddm4_elements['data']['navigation_parent_id']['options']=[];
	$ddm4_elements['data']['navigation_parent_id']['options']['required']=true;
	$ddm4_elements['data']['navigation_parent_id']['options']['data']=$ar_data;
	$ddm4_elements['data']['navigation_parent_id']['options']['blank_value']=false;
	$ddm4_elements['data']['navigation_parent_id']['validation']=[];
	$ddm4_elements['data']['navigation_parent_id']['validation']['module']='integer';
	$ddm4_elements['data']['navigation_parent_id']['validation']['length_min']=0;
	$ddm4_elements['data']['navigation_parent_id']['validation']['length_max']=11;
	$ddm4_elements['data']['navigation_parent_id']['validation']['value_min']=0;
	$ddm4_elements['data']['navigation_parent_id']['validation']['value_max']=999999;
	$ddm4_elements['data']['navigation_parent_id']['_edit']=[];
	$ddm4_elements['data']['navigation_parent_id']['_edit']['validation']=[];
	$ddm4_elements['data']['navigation_parent_id']['_edit']['validation']['filter']=[];
	$ddm4_elements['data']['navigation_parent_id']['_edit']['validation']['filter']['vis2_navigation_check_parent_id']=[];
	$ddm4_elements['data']['navigation_parent_id']['_list']=[];
	$ddm4_elements['data']['navigation_parent_id']['_list']['module']='hidden';

	/*
	 * Data: Titel
	 */
	$ddm4_elements['data']['navigation_title']=[];
	$ddm4_elements['data']['navigation_title']['module']='texttree';
	$ddm4_elements['data']['navigation_title']['title']='Titel';
	$ddm4_elements['data']['navigation_title']['name']='navigation_title';
	$ddm4_elements['data']['navigation_title']['options']=[];
	$ddm4_elements['data']['navigation_title']['options']['required']=true;
	$ddm4_elements['data']['navigation_title']['options']['search']=true;
	$ddm4_elements['data']['navigation_title']['options']['data_level']=$ar_level;
	$ddm4_elements['data']['navigation_title']['options']['index_key']='navigation_id';
	$ddm4_elements['data']['navigation_title']['validation']=[];
	$ddm4_elements['data']['navigation_title']['validation']['module']='string';
	$ddm4_elements['data']['navigation_title']['validation']['length_min']=2;
	$ddm4_elements['data']['navigation_title']['validation']['length_max']=32;

	/*
	 * Data: Seite
	 */
	$ddm4_elements['data']['page_id']=[];
	$ddm4_elements['data']['page_id']['module']='select';
	$ddm4_elements['data']['page_id']['title']='Seite';
	$ddm4_elements['data']['page_id']['name']='page_id';
	$ddm4_elements['data']['page_id']['options']=[];
	$ddm4_elements['data']['page_id']['options']['search']=true;
	$ddm4_elements['data']['page_id']['options']['data']=\VIS2\Core\Manager::getPagesByToolId($VIS2_Main->getToolId());
	$ddm4_elements['data']['page_id']['validation']=[];
	$ddm4_elements['data']['page_id']['validation']['module']='integer';
	$ddm4_elements['data']['page_id']['validation']['length_min']=0;
	$ddm4_elements['data']['page_id']['validation']['length_max']=11;
	$ddm4_elements['data']['page_id']['validation']['filter']=[];
	$ddm4_elements['data']['page_id']['validation']['filter']['unique_filter']=[];

	/*
	 * Data: Sortierung
	 */
	$ddm4_elements['data']['navigation_sortorder']=[];
	$ddm4_elements['data']['navigation_sortorder']['module']='text';
	$ddm4_elements['data']['navigation_sortorder']['title']='Sortierung';
	$ddm4_elements['data']['navigation_sortorder']['name']='navigation_sortorder';
	$ddm4_elements['data']['navigation_sortorder']['validation']=[];
	$ddm4_elements['data']['navigation_sortorder']['validation']['module']='string';
	$ddm4_elements['data']['navigation_sortorder']['validation']['length_min']=1;
	$ddm4_elements['data']['navigation_sortorder']['validation']['length_max']=11;

	/*
	 * Data: Status
	 */
	$ddm4_elements['data']['navigation_ispublic']=[];
	$ddm4_elements['data']['navigation_ispublic']['module']='yesno';
	$ddm4_elements['data']['navigation_ispublic']['title']='Status';
	$ddm4_elements['data']['navigation_ispublic']['name']='navigation_ispublic';
	$ddm4_elements['data']['navigation_ispublic']['options']=[];
	$ddm4_elements['data']['navigation_ispublic']['options']['default_value']=1;
	$ddm4_elements['data']['navigation_ispublic']['options']['required']=true;
	$ddm4_elements['data']['navigation_ispublic']['options']['text_yes']='Aktiviert';
	$ddm4_elements['data']['navigation_ispublic']['options']['text_no']='Deaktiviert';

	/*
	 * Data: ToolId
	 */
	$ddm4_elements['data']['tool_id']=[];
	$ddm4_elements['data']['tool_id']['module']='hidden';
	$ddm4_elements['data']['tool_id']['title']='ToolId';
	$ddm4_elements['data']['tool_id']['name']='tool_id';
	$ddm4_elements['data']['tool_id']['options']=[];
	$ddm4_elements['data']['tool_id']['options']['default_value']=$VIS2_Main->getToolId();
	$ddm4_elements['data']['tool_id']['validation']=[];
	$ddm4_elements['data']['tool_id']['validation']['module']='integer';
	$ddm4_elements['data']['tool_id']['validation']['length_min']=1;
	$ddm4_elements['data']['tool_id']['validation']['length_max']=11;
	$ddm4_elements['data']['tool_id']['_view']=[];
	$ddm4_elements['data']['tool_id']['_view']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_search']=[];
	$ddm4_elements['data']['tool_id']['_search']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_edit']=[];
	$ddm4_elements['data']['tool_id']['_edit']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_delete']=[];
	$ddm4_elements['data']['tool_id']['_delete']['enabled']=false;

	/*
	 * Data: Sortierung
	 */
	$ddm4_elements['data']['navigation_intern_sortorder']=[];
	$ddm4_elements['data']['navigation_intern_sortorder']['module']='text';
	$ddm4_elements['data']['navigation_intern_sortorder']['title']='Sortierung';
	$ddm4_elements['data']['navigation_intern_sortorder']['name']='navigation_intern_sortorder';
	$ddm4_elements['data']['navigation_intern_sortorder']['options']=[];
	$ddm4_elements['data']['navigation_intern_sortorder']['options']['order']=true;
	$ddm4_elements['data']['navigation_intern_sortorder']['options']['search']=true;
	$ddm4_elements['data']['navigation_intern_sortorder']['options']['default_value']=1;
	$ddm4_elements['data']['navigation_intern_sortorder']['options']['required']=true;
	$ddm4_elements['data']['navigation_intern_sortorder']['options']['text_yes']='Aktiviert';
	$ddm4_elements['data']['navigation_intern_sortorder']['options']['text_no']='Deaktiviert';
	$ddm4_elements['data']['navigation_intern_sortorder']['_list']=[];
	$ddm4_elements['data']['navigation_intern_sortorder']['_list']['module']='hidden';
	$ddm4_elements['data']['navigation_intern_sortorder']['_search']=[];
	$ddm4_elements['data']['navigation_intern_sortorder']['_search']['enabled']=false;
	$ddm4_elements['data']['navigation_intern_sortorder']['_add']=[];
	$ddm4_elements['data']['navigation_intern_sortorder']['_add']['enabled']=false;
	$ddm4_elements['data']['navigation_intern_sortorder']['_edit']=[];
	$ddm4_elements['data']['navigation_intern_sortorder']['_edit']['enabled']=false;
	$ddm4_elements['data']['navigation_intern_sortorder']['_delete']=[];
	$ddm4_elements['data']['navigation_intern_sortorder']['_delete']['enabled']=false;

	/*
	 * Data: VIS2_CreateUpdate
	 */
	$ddm4_elements['data']['vis2_createupdatestatus']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
	$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
	$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='navigation_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='navigation_';

	/*
	 * Finish: VIS2_Navigation_Sort
	 */
	$ddm4_elements['finish']['vis2_navigation_sort']=[];
	$ddm4_elements['finish']['vis2_navigation_sort']['module']='vis2_navigation_sort';
	$ddm4_elements['finish']['vis2_navigation_sort']['options']=[];
	$ddm4_elements['finish']['vis2_navigation_sort']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * Finish: VIS2_Navigation_Delete
 	*/
	$ddm4_elements['finish']['vis2_navigation_delete']=[];
	$ddm4_elements['finish']['vis2_navigation_delete']['module']='vis2_navigation_delete';
	$ddm4_elements['finish']['vis2_navigation_delete']['options']=[];
	$ddm4_elements['finish']['vis2_navigation_delete']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Gruppen
 */
if (in_array($ddm_navigation_id, [4])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'vis2_group', 'database');
	$osW_DDM4->setGroupOption('index', 'group_id', 'database');
	$osW_DDM4->setGroupOption('index_parent', 'navigation_parent_id');
	$osW_DDM4->setGroupOption('navigation_level', '3');
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'tool_id', 'operator'=>'=', 'value'=>$VIS2_Main->getToolId()]]]], 'database');
	$osW_DDM4->setGroupOption('order', ['group_name_intern'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['group_ispublic'=>[['value'=>'Deaktiviert', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine Gruppen vorhanden';
	$messages['search_title']='Gruppen durchsuchen';
	$messages['add_title']='Neue Gruppe anlegen';
	$messages['add_success_title']='Gruppe wurde erfolgreich angelegt';
	$messages['add_error_title']='Gruppe konnte nicht angelegt werden';
	$messages['edit_title']='Gruppe editieren';
	$messages['edit_load_error_title']='Gruppe wurde nicht gefunden';
	$messages['edit_success_title']='Gruppe wurde erfolgreich editiert';
	$messages['edit_error_title']='Gruppe konnte nicht editiert werden';
	$messages['delete_title']='Gruppe löschen';
	$messages['delete_load_error_title']='Gruppe wurde nicht gefunden';
	$messages['delete_success_title']='Gruppe wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Gruppe konnte nicht gelöscht werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

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
	 * Data: Name
	 */
	$ddm4_elements['data']['group_name']=[];
	$ddm4_elements['data']['group_name']['module']='text';
	$ddm4_elements['data']['group_name']['title']='Name';
	$ddm4_elements['data']['group_name']['name']='group_name';
	$ddm4_elements['data']['group_name']['options']=[];
	$ddm4_elements['data']['group_name']['options']['order']=true;
	$ddm4_elements['data']['group_name']['options']['required']=true;
	$ddm4_elements['data']['group_name']['options']['search']=true;
	$ddm4_elements['data']['group_name']['validation']=[];
	$ddm4_elements['data']['group_name']['validation']['module']='string';
	$ddm4_elements['data']['group_name']['validation']['length_min']=2;
	$ddm4_elements['data']['group_name']['validation']['length_max']=32;

	/*
	 * Data: Name Intern
	 */
	$ddm4_elements['data']['group_name_intern']=[];
	$ddm4_elements['data']['group_name_intern']['module']='text';
	$ddm4_elements['data']['group_name_intern']['title']='Name Intern';
	$ddm4_elements['data']['group_name_intern']['name']='group_name_intern';
	$ddm4_elements['data']['group_name_intern']['options']=[];
	$ddm4_elements['data']['group_name_intern']['options']['order']=true;
	$ddm4_elements['data']['group_name_intern']['options']['required']=true;
	$ddm4_elements['data']['group_name_intern']['options']['search']=true;
	$ddm4_elements['data']['group_name_intern']['options']['notice']='Nur a-z, 0-9 und "_". Nach Speichern nicht änderbar.';
	$ddm4_elements['data']['group_name_intern']['validation']=[];
	$ddm4_elements['data']['group_name_intern']['validation']['module']='string';
	$ddm4_elements['data']['group_name_intern']['validation']['length_min']=2;
	$ddm4_elements['data']['group_name_intern']['validation']['length_max']=32;
	$ddm4_elements['data']['group_name_intern']['validation']['preg']='/^[a-z0-9_]+$/';
	$ddm4_elements['data']['group_name_intern']['validation']['filter']=[];
	$ddm4_elements['data']['group_name_intern']['validation']['filter']['unique']=[];
	$ddm4_elements['data']['group_name_intern']['_edit']=[];
	$ddm4_elements['data']['group_name_intern']['_edit']['options']=[];
	$ddm4_elements['data']['group_name_intern']['_edit']['options']['read_only']=true;
	$ddm4_elements['data']['group_name_intern']['_edit']['options']['required']=false;
	$ddm4_elements['data']['group_name_intern']['_edit']['options']['notice']='';
	$ddm4_elements['data']['group_name_intern']['_delete']=[];
	$ddm4_elements['data']['group_name_intern']['_delete']['options']=[];
	$ddm4_elements['data']['group_name_intern']['_delete']['options']['notice']='';

	/*
	 * Data: Beschreibung
	 */
	$ddm4_elements['data']['group_description']=[];
	$ddm4_elements['data']['group_description']['module']='text';
	$ddm4_elements['data']['group_description']['title']='Beschreibung';
	$ddm4_elements['data']['group_description']['name']='group_description';
	$ddm4_elements['data']['group_description']['options']=[];
	$ddm4_elements['data']['group_description']['options']['search']=true;
	$ddm4_elements['data']['group_description']['validation']=[];
	$ddm4_elements['data']['group_description']['validation']['module']='string';
	$ddm4_elements['data']['group_description']['validation']['length_min']=0;
	$ddm4_elements['data']['group_description']['validation']['length_max']=64;

	/*
	 * Data: Rechte
	 */
	$ddm4_elements['data']['vis2_group_permission']=[];
	$ddm4_elements['data']['vis2_group_permission']['module']='vis2_group_permission';
	$ddm4_elements['data']['vis2_group_permission']['title']='Rechte';
	$ddm4_elements['data']['vis2_group_permission']['options']=[];
	$ddm4_elements['data']['vis2_group_permission']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * Data: Benutzer
	 */
	$ddm4_elements['data']['vis2_group_user']=[];
	$ddm4_elements['data']['vis2_group_user']['module']='vis2_group_user';
	$ddm4_elements['data']['vis2_group_user']['title']='Benutzer';
	$ddm4_elements['data']['vis2_group_user']['options']=[];
	$ddm4_elements['data']['vis2_group_user']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * Data: Status
	 */
	$ddm4_elements['data']['group_ispublic']=[];
	$ddm4_elements['data']['group_ispublic']['module']='yesno';
	$ddm4_elements['data']['group_ispublic']['title']='Status';
	$ddm4_elements['data']['group_ispublic']['name']='group_ispublic';
	$ddm4_elements['data']['group_ispublic']['options']=[];
	$ddm4_elements['data']['group_ispublic']['options']['order']=true;
	$ddm4_elements['data']['group_ispublic']['options']['default_value']=1;
	$ddm4_elements['data']['group_ispublic']['options']['required']=true;
	$ddm4_elements['data']['group_ispublic']['options']['text_yes']='Aktiviert';
	$ddm4_elements['data']['group_ispublic']['options']['text_no']='Deaktiviert';

	/*
	 * Data: ToolId
	 */
	$ddm4_elements['data']['tool_id']=[];
	$ddm4_elements['data']['tool_id']['module']='hidden';
	$ddm4_elements['data']['tool_id']['title']='ToolId';
	$ddm4_elements['data']['tool_id']['name']='tool_id';
	$ddm4_elements['data']['tool_id']['options']=[];
	$ddm4_elements['data']['tool_id']['options']['default_value']=$VIS2_Main->getToolId();
	$ddm4_elements['data']['tool_id']['validation']=[];
	$ddm4_elements['data']['tool_id']['validation']['module']='integer';
	$ddm4_elements['data']['tool_id']['validation']['length_min']=1;
	$ddm4_elements['data']['tool_id']['validation']['length_max']=11;
	$ddm4_elements['data']['tool_id']['_view']=[];
	$ddm4_elements['data']['tool_id']['_view']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_search']=[];
	$ddm4_elements['data']['tool_id']['_search']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_edit']=[];
	$ddm4_elements['data']['tool_id']['_edit']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_delete']=[];
	$ddm4_elements['data']['tool_id']['_delete']['enabled']=false;

	/*
	 * Data: VIS2_CreateUpdate
	 */
	$ddm4_elements['data']['vis2_createupdatestatus']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
	$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
	$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='group_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='group_';

	/*
	 * Finish: VIS2_Group_Permission_Write
	 */
	$ddm4_elements['finish']['vis2_group_permission_write']=[];
	$ddm4_elements['finish']['vis2_group_permission_write']['module']='vis2_group_permission_write';
	$ddm4_elements['finish']['vis2_group_permission_write']['options']=[];
	$ddm4_elements['finish']['vis2_group_permission_write']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * Finish: VIS2_Group_User_Write
	 */
	$ddm4_elements['finish']['vis2_group_user_write']=[];
	$ddm4_elements['finish']['vis2_group_user_write']['module']='vis2_group_user_write';
	$ddm4_elements['finish']['vis2_group_user_write']['options']=[];
	$ddm4_elements['finish']['vis2_group_user_write']['options']['tool_id']=$VIS2_Main->getToolId();
	$ddm4_elements['finish']['vis2_group_user_write']['options']['tool_name']=$VIS2_Main->getToolName();

	/*
	 * Finish: VIS2_Group_Delete
	 */
	$ddm4_elements['finish']['vis2_group_delete']=[];
	$ddm4_elements['finish']['vis2_group_delete']['module']='vis2_group_delete';
	$ddm4_elements['finish']['vis2_group_delete']['options']=[];
	$ddm4_elements['finish']['vis2_group_delete']['options']['tool_id']=$VIS2_Main->getToolId();

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 *  Mandanten
 */
if (in_array($ddm_navigation_id, [5])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'vis2_mandant', 'database');
	$osW_DDM4->setGroupOption('index', 'mandant_id', 'database');
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'tool_id', 'operator'=>'=', 'value'=>$VIS2_Main->getToolId()]]]], 'database');
	$osW_DDM4->setGroupOption('order', ['mandant_name'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['mandant_ispublic'=>[['value'=>'Deaktiviert', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine Mandanten vorhanden';
	$messages['search_title']='Mandanten durchsuchen';
	$messages['add_title']='Neuen Mandant anlegen';
	$messages['add_success_title']='Mandant wurde erfolgreich angelegt';
	$messages['add_error_title']='Mandant konnte nicht angelegt werden';
	$messages['edit_title']='Mandant editieren';
	$messages['edit_load_error_title']='Mandant wurde nicht gefunden';
	$messages['edit_success_title']='Mandant wurde erfolgreich editiert';
	$messages['edit_error_title']='Mandant konnte nicht editiert werden';
	$messages['delete_title']='Mandant löschen';
	$messages['delete_load_error_title']='Mandant wurde nicht gefunden';
	$messages['delete_success_title']='Mandant wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Mandant konnte nicht gelöscht werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

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
	 * Data: Nummer
	 */
	$ddm4_elements['data']['mandant_number']=[];
	$ddm4_elements['data']['mandant_number']['module']='text';
	$ddm4_elements['data']['mandant_number']['title']='Nummer';
	$ddm4_elements['data']['mandant_number']['name']='mandant_number';
	$ddm4_elements['data']['mandant_number']['options']=[];
	$ddm4_elements['data']['mandant_number']['options']['order']=true;
	$ddm4_elements['data']['mandant_number']['options']['required']=true;
	$ddm4_elements['data']['mandant_number']['options']['search']=true;
	$ddm4_elements['data']['mandant_number']['validation']=[];
	$ddm4_elements['data']['mandant_number']['validation']['module']='integer';
	$ddm4_elements['data']['mandant_number']['validation']['length_min']=1;
	$ddm4_elements['data']['mandant_number']['validation']['length_max']=11;
	$ddm4_elements['data']['mandant_number']['validation']['filter']=[];
	$ddm4_elements['data']['mandant_number']['validation']['filter']['unique_filter']=[];

	/*
	 * Data: Name Intern
	 */
	$ddm4_elements['data']['mandant_name_intern']=[];
	$ddm4_elements['data']['mandant_name_intern']['module']='text';
	$ddm4_elements['data']['mandant_name_intern']['title']='Name Intern';
	$ddm4_elements['data']['mandant_name_intern']['name']='mandant_name_intern';
	$ddm4_elements['data']['mandant_name_intern']['options']=[];
	$ddm4_elements['data']['mandant_name_intern']['options']['order']=true;
	$ddm4_elements['data']['mandant_name_intern']['options']['required']=true;
	$ddm4_elements['data']['mandant_name_intern']['options']['search']=true;
	$ddm4_elements['data']['mandant_name_intern']['options']['notice']='Nur a-z, 0-9 und "_". Nach Speichern nicht änderbar.';
	$ddm4_elements['data']['mandant_name_intern']['validation']=[];
	$ddm4_elements['data']['mandant_name_intern']['validation']['module']='string';
	$ddm4_elements['data']['mandant_name_intern']['validation']['length_min']=2;
	$ddm4_elements['data']['mandant_name_intern']['validation']['length_max']=32;
	$ddm4_elements['data']['mandant_name_intern']['validation']['preg']='/^[a-z0-9_]+$/';
	$ddm4_elements['data']['mandant_name_intern']['validation']['filter']=[];
	$ddm4_elements['data']['mandant_name_intern']['validation']['filter']['unique']=[];
	$ddm4_elements['data']['mandant_name_intern']['_edit']=[];
	$ddm4_elements['data']['mandant_name_intern']['_edit']['options']=[];
	$ddm4_elements['data']['mandant_name_intern']['_edit']['options']['read_only']=true;
	$ddm4_elements['data']['mandant_name_intern']['_edit']['options']['required']=false;
	$ddm4_elements['data']['mandant_name_intern']['_edit']['options']['notice']='';
	$ddm4_elements['data']['mandant_name_intern']['_delete']=[];
	$ddm4_elements['data']['mandant_name_intern']['_delete']['options']=[];
	$ddm4_elements['data']['mandant_name_intern']['_delete']['options']['notice']='';

	/*
	 * Data: Name
	 */
	$ddm4_elements['data']['mandant_name']=[];
	$ddm4_elements['data']['mandant_name']['module']='text';
	$ddm4_elements['data']['mandant_name']['title']='Name';
	$ddm4_elements['data']['mandant_name']['name']='mandant_name';
	$ddm4_elements['data']['mandant_name']['options']=[];
	$ddm4_elements['data']['mandant_name']['options']['order']=true;
	$ddm4_elements['data']['mandant_name']['options']['required']=true;
	$ddm4_elements['data']['mandant_name']['options']['search']=true;
	$ddm4_elements['data']['mandant_name']['validation']=[];
	$ddm4_elements['data']['mandant_name']['validation']['module']='string';
	$ddm4_elements['data']['mandant_name']['validation']['length_min']=2;
	$ddm4_elements['data']['mandant_name']['validation']['length_max']=128;

	/*
	 * Data: Beschreibung
	 */
	$ddm4_elements['data']['mandant_description']=[];
	$ddm4_elements['data']['mandant_description']['module']='text';
	$ddm4_elements['data']['mandant_description']['title']='Beschreibung';
	$ddm4_elements['data']['mandant_description']['name']='mandant_description';
	$ddm4_elements['data']['mandant_description']['options']=[];
	$ddm4_elements['data']['mandant_description']['options']['search']=true;
	$ddm4_elements['data']['mandant_description']['validation']=[];
	$ddm4_elements['data']['mandant_description']['validation']['module']='string';
	$ddm4_elements['data']['mandant_description']['validation']['length_min']=0;
	$ddm4_elements['data']['mandant_description']['validation']['length_max']=64;

	/*
	 * Data: Status
	 */
	$ddm4_elements['data']['mandant_ispublic']=[];
	$ddm4_elements['data']['mandant_ispublic']['module']='yesno';
	$ddm4_elements['data']['mandant_ispublic']['title']='Status';
	$ddm4_elements['data']['mandant_ispublic']['name']='mandant_ispublic';
	$ddm4_elements['data']['mandant_ispublic']['options']=[];
	$ddm4_elements['data']['mandant_ispublic']['options']['order']=true;
	$ddm4_elements['data']['mandant_ispublic']['options']['default_value']=1;
	$ddm4_elements['data']['mandant_ispublic']['options']['required']=true;
	$ddm4_elements['data']['mandant_ispublic']['options']['text_yes']='Aktiviert';
	$ddm4_elements['data']['mandant_ispublic']['options']['text_no']='Deaktiviert';

	/*
	 * Data: ToolId
	 */
	$ddm4_elements['data']['tool_id']=[];
	$ddm4_elements['data']['tool_id']['module']='hidden';
	$ddm4_elements['data']['tool_id']['title']='ToolId';
	$ddm4_elements['data']['tool_id']['name']='tool_id';
	$ddm4_elements['data']['tool_id']['options']=[];
	$ddm4_elements['data']['tool_id']['options']['default_value']=$VIS2_Main->getToolId();
	$ddm4_elements['data']['tool_id']['validation']=[];
	$ddm4_elements['data']['tool_id']['validation']['module']='integer';
	$ddm4_elements['data']['tool_id']['validation']['length_min']=1;
	$ddm4_elements['data']['tool_id']['validation']['length_max']=11;
	$ddm4_elements['data']['tool_id']['_view']=[];
	$ddm4_elements['data']['tool_id']['_view']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_search']=[];
	$ddm4_elements['data']['tool_id']['_search']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_edit']=[];
	$ddm4_elements['data']['tool_id']['_edit']['enabled']=false;
	$ddm4_elements['data']['tool_id']['_delete']=[];
	$ddm4_elements['data']['tool_id']['_delete']['enabled']=false;

	/*
	 * Data: VIS2_CreateUpdate
	 */
	$ddm4_elements['data']['vis2_createupdatestatus']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
	$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
	$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='mandant_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='mandant_';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

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