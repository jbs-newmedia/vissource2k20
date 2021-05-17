<?php

$this->settings=['page_title'=>'VIS2: Settings'];

$VIS2=new \osWFrame\Tools\VIS2($this);
$VIS2->loadUsers();

if ($VIS2->getUsers()!=[]) {
	$this->fields['vis2_admin_user_id']=['default_name'=>'User', 'default_type'=>'select', 'default_value'=>$VIS2->geUserId(), 'default_select'=>$VIS2->getUsers(), 'valid_type'=>'integer', 'valid_min_length'=>1, 'valid_max_length'=>11, 'configure_write'=>false];
} else {
	$this->fields['vis2_admin_user_id']=['default_name'=>'User', 'default_type'=>'hidden', 'default_value'=>'1', 'valid_type'=>'integer', 'valid_min_length'=>1, 'valid_max_length'=>11, 'configure_write'=>false];

	$this->fields['vis2_admin_username']=['default_name'=>'Username', 'default_type'=>'text', 'default_value'=>'admin', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>32, 'configure_write'=>false];

	$this->fields['vis2_admin_form']=['default_name'=>'Form', 'default_type'=>'select', 'default_value'=>'', 'default_select'=>[''=>'', 'Herr'=>'Herr', 'Frau'=>'Frau'], 'valid_type'=>'string', 'valid_min_length'=>4, 'valid_max_length'=>4, 'configure_write'=>false];

	$this->fields['vis2_admin_firstname']=['default_name'=>'Vorname', 'default_type'=>'text', 'default_value'=>'', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>32, 'configure_write'=>false];

	$this->fields['vis2_admin_lastname']=['default_name'=>'Nachname', 'default_type'=>'text', 'default_value'=>'', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>32, 'configure_write'=>false];

	$this->fields['vis2_admin_gender']=['default_name'=>'Gender', 'default_type'=>'select', 'default_value'=>'', 'default_select'=>[''=>'', '1'=>'männlich', '2'=>'weiblich'], 'valid_type'=>'integer', 'valid_min_length'=>1, 'valid_max_length'=>1, 'valid_min_value'=>1, 'configure_write'=>false];

	$this->fields['vis2_admin_email']=['default_name'=>'E-Mail', 'default_type'=>'text', 'default_value'=>'', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>64, 'valid_function'=>'check_email', 'configure_write'=>false];

	$this->fields['vis2_admin_password']=['default_name'=>'Password', 'default_type'=>'password', 'default_value'=>'', 'valid_type'=>'string', 'valid_min_length'=>8, 'valid_max_length'=>32, 'configure_write'=>false];
}

if ($VIS2->getJSONs()!==[]) {
	foreach ($VIS2->getJSONs() as $json) {
		$this->fields['vis2_tool_'.$json['name_intern']]=['default_name'=>'Install/Update VIS2: '.$json['name'], 'default_type'=>'select', 'default_value'=>1, 'default_select'=>[1=>'YES', 2=>'NO'], 'valid_type'=>'integer', 'valid_min_length'=>1, 'valid_max_length'=>1, 'configure_write'=>false];
	}
}

?>