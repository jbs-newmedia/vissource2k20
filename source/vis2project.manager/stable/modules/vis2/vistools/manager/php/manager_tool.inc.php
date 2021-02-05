<?php

/**
 * This file is part of the VIS2:Manager package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:Manager
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
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
$ddm4_object['general']['status_keys']['tool_ispublic']=[];
$ddm4_object['general']['status_keys']['tool_ispublic'][0]=['value'=>'0', 'class'=>'danger'];
$ddm4_object['data']=[];
$ddm4_object['data']['user_id']=$VIS2_User->getId();
$ddm4_object['data']['tool']=$VIS2_Main->getTool();
$ddm4_object['data']['page']=$VIS2_Navigation->getPage();
$ddm4_object['messages']=[];
$ddm4_object['messages']['createupdate_title']='Datensatzinformationen';
$ddm4_object['messages']['data_noresults']='Keine Programme vorhanden';
$ddm4_object['messages']['search_title']='Programme durchsuchen';
$ddm4_object['messages']['add_title']='Neues Programm anlegen';
$ddm4_object['messages']['add_success_title']='Programm wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Programm konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Programm editieren';
$ddm4_object['messages']['edit_load_error_title']='Programm wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Programm wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Programm konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Programm löschen';
$ddm4_object['messages']['delete_load_error_title']='Programm wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Programm wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Programm konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='vis2_tool';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='tool_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['tool_name_intern']='asc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/**
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'vis2_tool', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * View: VIS2_Datatables
 */
$ddm4_elements['view']['vis2_datatables']=[];
$ddm4_elements['view']['vis2_datatables']['module']='vis2_datatables';

/*
 * Data: Name
 */
$ddm4_elements['data']['tool_name']=[];
$ddm4_elements['data']['tool_name']['module']='text';
$ddm4_elements['data']['tool_name']['title']='Name';
$ddm4_elements['data']['tool_name']['name']='tool_name';
$ddm4_elements['data']['tool_name']['options']=[];
$ddm4_elements['data']['tool_name']['options']['order']=true;
$ddm4_elements['data']['tool_name']['options']['required']=true;
$ddm4_elements['data']['tool_name']['options']['search']=true;
$ddm4_elements['data']['tool_name']['validation']=[];
$ddm4_elements['data']['tool_name']['validation']['module']='string';
$ddm4_elements['data']['tool_name']['validation']['length_min']=2;
$ddm4_elements['data']['tool_name']['validation']['length_max']=32;

/*
 * Data: Name Intern
 */
$ddm4_elements['data']['tool_name_intern']=[];
$ddm4_elements['data']['tool_name_intern']['module']='text';
$ddm4_elements['data']['tool_name_intern']['title']='Name Intern';
$ddm4_elements['data']['tool_name_intern']['name']='tool_name_intern';
$ddm4_elements['data']['tool_name_intern']['options']=[];
$ddm4_elements['data']['tool_name_intern']['options']['order']=true;
$ddm4_elements['data']['tool_name_intern']['options']['required']=true;
$ddm4_elements['data']['tool_name_intern']['options']['search']=true;
$ddm4_elements['data']['tool_name_intern']['options']['notice']='Nur a-z, 0-9 und "_". Nach Speichern nicht änderbar.';
$ddm4_elements['data']['tool_name_intern']['validation']=[];
$ddm4_elements['data']['tool_name_intern']['validation']['module']='string';
$ddm4_elements['data']['tool_name_intern']['validation']['length_min']=2;
$ddm4_elements['data']['tool_name_intern']['validation']['length_max']=32;
$ddm4_elements['data']['tool_name_intern']['validation']['preg']='/^[a-z0-9_]+$/';
$ddm4_elements['data']['tool_name_intern']['validation']['filter']=[];
$ddm4_elements['data']['tool_name_intern']['validation']['filter']['unique']=[];
$ddm4_elements['data']['tool_name_intern']['_edit']=[];
$ddm4_elements['data']['tool_name_intern']['_edit']['module']='autovalue';
$ddm4_elements['data']['tool_name_intern']['_edit']['options']=[];
$ddm4_elements['data']['tool_name_intern']['_edit']['options']['required']=false;
$ddm4_elements['data']['tool_name_intern']['_edit']['options']['notice']='';
$ddm4_elements['data']['tool_name_intern']['_delete']=[];
$ddm4_elements['data']['tool_name_intern']['_delete']['module']='autovalue';
$ddm4_elements['data']['tool_name_intern']['_delete']['options']=[];
$ddm4_elements['data']['tool_name_intern']['_delete']['options']['required']=false;
$ddm4_elements['data']['tool_name_intern']['_delete']['options']['notice']='';

/*
 * Data: Beschreibung
 */
$ddm4_elements['data']['tool_description']=[];
$ddm4_elements['data']['tool_description']['module']='text';
$ddm4_elements['data']['tool_description']['title']='Beschreibung';
$ddm4_elements['data']['tool_description']['name']='tool_description';
$ddm4_elements['data']['tool_description']['options']=[];
$ddm4_elements['data']['tool_description']['options']['search']=true;
$ddm4_elements['data']['tool_description']['validation']=[];
$ddm4_elements['data']['tool_description']['validation']['module']='string';
$ddm4_elements['data']['tool_description']['validation']['length_min']=0;
$ddm4_elements['data']['tool_description']['validation']['length_max']=128;

/*
 * Data: Status
 */
$ddm4_elements['data']['tool_ispublic']=[];
$ddm4_elements['data']['tool_ispublic']['module']='yesno';
$ddm4_elements['data']['tool_ispublic']['title']='Status';
$ddm4_elements['data']['tool_ispublic']['name']='tool_ispublic';
$ddm4_elements['data']['tool_ispublic']['options']=[];
$ddm4_elements['data']['tool_ispublic']['options']['order']=true;
$ddm4_elements['data']['tool_ispublic']['options']['default_value']=1;
$ddm4_elements['data']['tool_ispublic']['options']['required']=true;
$ddm4_elements['data']['tool_ispublic']['options']['text_yes']='Aktiviert';
$ddm4_elements['data']['tool_ispublic']['options']['text_no']='Deaktiviert';

/*
 * Data: Ausblenden (Login)
 */
$ddm4_elements['data']['tool_hide_logon']=[];
$ddm4_elements['data']['tool_hide_logon']['module']='yesno';
$ddm4_elements['data']['tool_hide_logon']['title']='Ausblenden (Login)';
$ddm4_elements['data']['tool_hide_logon']['name']='tool_hide_logon';
$ddm4_elements['data']['tool_hide_logon']['options']=[];
$ddm4_elements['data']['tool_hide_logon']['options']['order']=true;
$ddm4_elements['data']['tool_hide_logon']['options']['default_value']=0;
$ddm4_elements['data']['tool_hide_logon']['options']['required']=true;
$ddm4_elements['data']['tool_hide_logon']['options']['text_yes']='Aktiviert';
$ddm4_elements['data']['tool_hide_logon']['options']['text_no']='Deaktiviert';

/*
 * Data: Ausblenden (Navigation)
 */
$ddm4_elements['data']['tool_hide_navigation']=[];
$ddm4_elements['data']['tool_hide_navigation']['module']='yesno';
$ddm4_elements['data']['tool_hide_navigation']['title']='Ausblenden (Navigation)';
$ddm4_elements['data']['tool_hide_navigation']['name']='tool_hide_navigation';
$ddm4_elements['data']['tool_hide_navigation']['options']=[];
$ddm4_elements['data']['tool_hide_navigation']['options']['order']=true;
$ddm4_elements['data']['tool_hide_navigation']['options']['default_value']=0;
$ddm4_elements['data']['tool_hide_navigation']['options']['required']=true;
$ddm4_elements['data']['tool_hide_navigation']['options']['text_yes']='Aktiviert';
$ddm4_elements['data']['tool_hide_navigation']['options']['text_no']='Deaktiviert';

/*
 * Data: Mandanten
 */
$ddm4_elements['data']['tool_use_mandant']=[];
$ddm4_elements['data']['tool_use_mandant']['module']='yesno';
$ddm4_elements['data']['tool_use_mandant']['title']='Mandanten';
$ddm4_elements['data']['tool_use_mandant']['name']='tool_use_mandant';
$ddm4_elements['data']['tool_use_mandant']['options']=[];
$ddm4_elements['data']['tool_use_mandant']['options']['order']=true;
$ddm4_elements['data']['tool_use_mandant']['options']['default_value']=0;
$ddm4_elements['data']['tool_use_mandant']['options']['required']=true;
$ddm4_elements['data']['tool_use_mandant']['options']['text_yes']='Aktiviert';
$ddm4_elements['data']['tool_use_mandant']['options']['text_no']='Deaktiviert';

/*
 * Data: Mandanten-Switch
 */
$ddm4_elements['data']['tool_use_mandantswitch']=[];
$ddm4_elements['data']['tool_use_mandantswitch']['module']='yesno';
$ddm4_elements['data']['tool_use_mandantswitch']['title']='Mandanten-Switch';
$ddm4_elements['data']['tool_use_mandantswitch']['name']='tool_use_mandantswitch';
$ddm4_elements['data']['tool_use_mandantswitch']['options']=[];
$ddm4_elements['data']['tool_use_mandantswitch']['options']['order']=true;
$ddm4_elements['data']['tool_use_mandantswitch']['options']['default_value']=0;
$ddm4_elements['data']['tool_use_mandantswitch']['options']['required']=true;
$ddm4_elements['data']['tool_use_mandantswitch']['options']['text_yes']='Aktiviert';
$ddm4_elements['data']['tool_use_mandantswitch']['options']['text_no']='Deaktiviert';

/*
 * Data: Benutzer
 */
$ddm4_elements['data']['vis2_manager_tool_user']=[];
$ddm4_elements['data']['vis2_manager_tool_user']['module']='vis2_manager_tool_user';
$ddm4_elements['data']['vis2_manager_tool_user']['title']='Benutzer';

/*
 * Data: VIS2_CreateUpdate
 */
$ddm4_elements['data']['vis2_createupdatestatus']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='tool_';
$ddm4_elements['data']['vis2_createupdatestatus']['options']['time']=time();
$ddm4_elements['data']['vis2_createupdatestatus']['options']['user_id']=$VIS2_User->getId();
$ddm4_elements['data']['vis2_createupdatestatus']['options']['order']=true;
$ddm4_elements['data']['vis2_createupdatestatus']['options']['search']=true;
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
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='tool_';

/*
 * Finish: VIS2_Manager_Tool_Delete
 */
$ddm4_elements['finish']['vis2_manager_tool_delete']=[];
$ddm4_elements['finish']['vis2_manager_tool_delete']['module']='vis2_manager_tool_delete';

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

/**
 * DDM4-Objekt Runtime
 */
$osW_DDM4->runDDMPHP();

/**
 * DDM4-Objekt an Template übergeben
 */
$osW_Template->setVar('osW_DDM4', $osW_DDM4);

?>