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

/*
 * init
 */
$__datatable_table='vis2_user_token';
$__datatable_create=false;
$__datatable_do=false;

/*
 * check version of table
 */
$QreadData=new \osWFrame\Core\Database();
$QreadData->prepare('SHOW TABLE STATUS LIKE :table:');
$QreadData->bindString(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
$QreadData->execute();
if ($QreadData->rowCount()==1) {
	$QreadData_result=$QreadData->fetch();
	$avb_tbl=$QreadData_result['Comment'];
} else {
	$avb_tbl='0.0';
}
$avb_tbl=explode('.', $avb_tbl);
if (count($avb_tbl)==1) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=0;
} elseif (count($avb_tbl)==2) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=intval($avb_tbl[1]);
} else {
	$av_tbl=0;
	$ab_tbl=0;
}

/*
 * create table
 */
if (($av_tbl==0)&&($ab_tbl==0)) {
	$__datatable_create=true;
	$av_tbl=1;
	$ab_tbl=0;

	$QwriteData=new \osWFrame\Core\Database();
	$QwriteData->prepare('
CREATE TABLE :table: (
   	token_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	user_id int(11) unsigned NOT NULL DEFAULT 0,
	session_id varchar(32) NOT NULL DEFAULT \'\',
	token_value varchar(32) NOT NULL DEFAULT \'\',
	token_type varchar(8) NOT NULL DEFAULT \'\',
	token_name varchar(32) NOT NULL DEFAULT \'\',
	token_description varchar(128) NOT NULL DEFAULT \'\',
	token_permanent tinyint(1) unsigned NOT NULL DEFAULT 0,
	token_create_time int(11) unsigned NOT NULL DEFAULT 0,
	token_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	token_update_time int(11) unsigned NOT NULL DEFAULT 0,
	token_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (token_id),
	KEY user_id (user_id),
	KEY session_id (session_id),
	KEY token_value (token_value),
	KEY token_type (token_type),
	KEY token_permanent (token_permanent),
	KEY token_create_time (token_create_time),
	KEY token_create_user_id (token_create_user_id),
	KEY token_update_time (token_update_time),
	KEY token_update_user_id (token_update_user_id)
) ENGINE=:engine: DEFAULT CHARSET=:charset: COMMENT=:version:;
');
	$QwriteData->bindRaw(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
	$QwriteData->bindString(':engine:', $this->getJSONStringValue('database_engine'));
	$QwriteData->bindString(':charset:', $this->getJSONStringValue('database_character'));
	$QwriteData->bindString(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->getErrorMessage();
		$av_tbl=0;
		$ab_tbl=0;
	}
}

/*
 * update version
 */
if ($__datatable_do===true) {
	$QwriteData=new \osWFrame\Core\Database();
	$QwriteData->prepare('ALTER TABLE :table: COMMENT=:version:;');
	$QwriteData->bindRaw(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
	$QwriteData->bindString(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->getErrorMessage();
	}
}

?>