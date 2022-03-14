<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license MIT License
 */

if ($profile_run=='init') {
	$navigation_links[100]=['navigation_id'=>100, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Passwort ändern',];
} else {
	$osW_DDM4->setGroupOption('engine', 'vis2_formular');
	$osW_DDM4->setGroupOption('table', 'vis2_user', 'database');
	$osW_DDM4->setGroupOption('index', 'user_id', 'database');
	$osW_DDM4->setGroupOption('order', ['user_name'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('disable_delete', true);

	$messages=[];
	$messages['send_title']='Passwort ändern';
	$messages['send_success_title']='Passwort wurde erfolgreich geändert';
	$messages['send_error_title']='Passwort konnte nicht geändert werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

	/*
	 * Send: Passwort
	 */
	$ddm4_elements['send']['user_password']=[];
	$ddm4_elements['send']['user_password']['module']='password_double';
	$ddm4_elements['send']['user_password']['title']='Passwort';
	$ddm4_elements['send']['user_password']['name']='user_password';
	$ddm4_elements['send']['user_password']['options']=[];
	$ddm4_elements['send']['user_password']['options']['required']=true;
	$ddm4_elements['send']['user_password']['options']['title_double']='Passwort (wdh)';
	$ddm4_elements['send']['user_password']['validation']=[];
	$ddm4_elements['send']['user_password']['validation']['module']='crypt';
	$ddm4_elements['send']['user_password']['validation']['length_min']=\osWFrame\Core\Settings::getIntVar('vis2_user_password_length_min');
	$ddm4_elements['send']['user_password']['validation']['length_max']=\osWFrame\Core\Settings::getIntVar('vis2_user_password_length_max');
	$ddm4_elements['send']['user_password']['validation']['filter']=[];
	$ddm4_elements['send']['user_password']['validation']['filter']['password_double']=[];

	/*
	 * Finish: VIS2_Store_Form_Data
	 */
	$ddm4_elements['finish']['vis2_store_form_data']=[];
	$ddm4_elements['finish']['vis2_store_form_data']['module']='vis2_store_form_data';
	$ddm4_elements['finish']['vis2_store_form_data']['options']=[];
	$ddm4_elements['finish']['vis2_store_form_data']['options']['mode']='update';
}

?>