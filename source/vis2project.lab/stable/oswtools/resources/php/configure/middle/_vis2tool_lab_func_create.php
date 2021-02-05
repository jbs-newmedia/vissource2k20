<?php

$this->data['settings']=array();

$this->data['settings']['data']=array(
	'page_title'=>'VIS2:Lab Settings',
);

if (($position=='run')&&(isset($_POST['next']))&&($_POST['next']=='next')) {
	foreach ($this->data['values_post'] as $key => $values) {
		$this->data['values_json'][$key]=$values['value'];
	}

	if ((isset($this->data['values_json']['vis2_tool_lab']))&&($this->data['values_json']['vis2_tool_lab']==1)) {
		osW_Tool_Database::addDatabase('default', array('type'=>'mysql', 'database'=>$this->data['values_json']['database_db'], 'server'=>$this->data['values_json']['database_server'], 'username'=>$this->data['values_json']['database_username'], 'password'=>$this->data['values_json']['database_password'], 'pconnect'=>false, 'prefix'=>$this->data['values_json']['database_prefix']));
	}

	$_vis2script=array();
	$_vis2_script['tool']=array(
		'tool_name'=>'Lab',
		'tool_name_intern'=>'lab',
		'tool_description'=>'Lab-Tool',
		'tool_ispublic'=>1,
		'tool_hide_logon'=>0,
		'tool_hide_navigation'=>0,
		'tool_use_mandant'=>0,
		'tool_use_mandantswitch'=>0
	);
	$_vis2_script['group']=array();
	$_vis2_script['group'][1]=array(
		'group_name'=>'Lab-Admin',
		'group_name_intern'=>'lab_admin',
		'group_description'=>'Lab-Admin',
		'group_ispublic'=>1,
	);
	$_vis2_script['permission']=array();
	$_vis2_script['permission'][]=array(
		'permission_flag'=>'link',
		'permission_title'=>'Link anzeigen',
		'permission_ispublic'=>1,
	);
	$_vis2_script['permission'][]=array(
		'permission_flag'=>'view',
		'permission_title'=>'Seite anzeigen',
		'permission_ispublic'=>1,
	);
	$_vis2_script['navigation'][]=array(
		'navigation_parent_id'=>0,
		'navigation_title'=>'VIS',
		'navigation_sortorder'=>999,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'Header VIS',
			'page_name_intern'=>'header_vis2',
			'page_description'=>'Header VIS2',
			'page_ispublic'=>1,
			'permission'=>array('link'),
		),
		'permission'=>array(
			1=>array('link'),
		),
	);
	$_vis2_script['navigation'][]=array(
		'navigation_parent_id'=>'header_vis2',
		'navigation_title'=>'Navigation',
		'navigation_sortorder'=>10,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'VIS Navigation',
			'page_name_intern'=>'vis_navigation',
			'page_description'=>'VIS Navigation',
			'page_ispublic'=>1,
			'permission'=>array('link','view'),
		),
		'permission'=>array(
			1=>array('link','view'),
		),
	);
	$_vis2_script['navigation'][]=array(
		'navigation_parent_id'=>'header_vis2',
		'navigation_title'=>'Benutzer',
		'navigation_sortorder'=>20,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'VIS Benutzer',
			'page_name_intern'=>'vis_user',
			'page_description'=>'VIS Benutzer',
			'page_ispublic'=>1,
			'permission'=>array('link','view'),
		),
		'permission'=>array(
			1=>array('link','view'),
		),
	);

	osW_Tool_VIS2::getInstance()->parseScript($_vis2_script, $this, 1);

	$this->data['messages'][]='VIS2:Lab configured successfully';
}

if (($position=='run')&&(isset($_POST['prev']))&&($_POST['prev']=='prev')) {
	$this->data['messages'][]='VIS2:Lab configured successfully';
}

?>