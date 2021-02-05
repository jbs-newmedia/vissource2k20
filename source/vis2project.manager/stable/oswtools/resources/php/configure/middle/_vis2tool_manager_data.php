<?php

$this->data['settings']=array();

$this->data['settings']['data']=array(
	'page_title'=>'VIS2:Manager Settings',
);

osW_Tool_Database::addDatabase('default', array('type'=>'mysql', 'database'=>$this->data['values_json']['database_db'], 'server'=>$this->data['values_json']['database_server'], 'username'=>$this->data['values_json']['database_username'], 'password'=>$this->data['values_json']['database_password'], 'pconnect'=>false, 'prefix'=>$this->data['values_json']['database_prefix']));

$QreadData=osW_Tool_Database::getInstance()->query('SELECT user_id, user_email, user_lastname, user_firstname FROM :table_vis2_user: WHERE user_status=:user_status: ORDER BY user_lastname ASC, user_email DESC');
$QreadData->bindRaw(':table_vis2_user:', $this->data['values_json']['database_prefix'].'vis2_user');
$QreadData->bindInt(':user_status:', 1);
$QreadData->execute();

if ($QreadData->numberOfRows()>0) {
	$ar_user=array();
	$default_user='';
	while (($QreadData->next())) {
		$ar_user[$QreadData->Value('user_id')]=$QreadData->Value('user_lastname').' '.$QreadData->Value('user_firstname').' ('.$QreadData->Value('user_email').')';
		if (isset($this->data['values_json']['vis2_admin_email'])) {
			if ($this->data['values_json']['vis2_admin_email']==$QreadData->Value('user_email')) {
				$default_user=$QreadData->Value('user_id');
			}
		}
	}
	if (isset($this->data['values_json']['vis2_admin_user_id'])) {
		$default_user=$this->data['values_json']['vis2_admin_user_id'];
	}

	$this->data['settings']['fields']['vis2_admin_user_id']=array(
		'default_name'=>'User',
		'default_type'=>'select',
		'default_value'=>1,
		'default_select'=>$ar_user,
		'valid_type'=>'integer',
		'valid_min_length'=>1,
		'valid_max_length'=>11,
		'configure_write'=>false,
	);
} else {
	$this->data['settings']['fields']['vis2_admin_user_id']=array(
		'default_name'=>'User',
		'default_type'=>'hidden',
		'default_value'=>'1',
		'valid_type'=>'integer',
		'valid_min_length'=>1,
		'valid_max_length'=>11,
		'configure_write'=>false,
	);

	$this->data['settings']['fields']['vis2_admin_username']=array(
		'default_name'=>'Username',
		'default_type'=>'text',
		'default_value'=>'admin',
		'valid_type'=>'string',
		'valid_min_length'=>2,
		'valid_max_length'=>32,
		'configure_write'=>false,
	);

	$this->data['settings']['fields']['vis2_admin_form']=array(
		'default_name'=>'Form',
		'default_type'=>'select',
		'default_value'=>'',
		'default_select'=>array(''=>'', 'Herr'=>'Herr', 'Frau'=>'Frau'),
		'valid_type'=>'string',
		'valid_min_length'=>4,
		'valid_max_length'=>4,
		'configure_write'=>false,
	);

	$this->data['settings']['fields']['vis2_admin_firstname']=array(
		'default_name'=>'Vorname',
		'default_type'=>'text',
		'default_value'=>'',
		'valid_type'=>'string',
		'valid_min_length'=>2,
		'valid_max_length'=>32,
		'configure_write'=>false,
	);

	$this->data['settings']['fields']['vis2_admin_lastname']=array(
		'default_name'=>'Nachname',
		'default_type'=>'text',
		'default_value'=>'',
		'valid_type'=>'string',
		'valid_min_length'=>2,
		'valid_max_length'=>32,
		'configure_write'=>false,
	);

	$this->data['settings']['fields']['vis2_admin_gender']=array(
		'default_name'=>'Gender',
		'default_type'=>'select',
		'default_value'=>'',
		'default_select'=>array(''=>'', '1'=>'männlich', '2'=>'weiblich'),
		'valid_type'=>'integer',
		'valid_min_length'=>1,
		'valid_max_length'=>1,
		'configure_write'=>false,
	);

	$this->data['settings']['fields']['vis2_admin_email']=array(
		'default_name'=>'E-Mail',
		'default_type'=>'text',
		'default_value'=>'',
		'valid_type'=>'string',
		'valid_min_length'=>2,
		'valid_max_length'=>32,
		'valid_function'=>'check_email',
		'configure_write'=>false,
	);

	$this->data['settings']['fields']['vis2_admin_password']=array(
		'default_name'=>'Password',
		'default_type'=>'password',
		'default_value'=>'',
		'valid_type'=>'string',
		'valid_min_length'=>8,
		'valid_max_length'=>32,
		'configure_write'=>false,
	);
}
$this->data['settings']['fields']['vis2_tool_manager']=array(
	'default_name'=>'Install VIS2:Manager Tool',
	'default_type'=>'select',
	'default_value'=>1,
	'default_select'=>array(1=>'YES'),
	'valid_type'=>'integer',
	'valid_min_length'=>1,
	'valid_max_length'=>1,
	'configure_write'=>false,
);

?>