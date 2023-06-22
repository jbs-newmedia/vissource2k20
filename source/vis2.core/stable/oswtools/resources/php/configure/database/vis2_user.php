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
$__datatable_table='vis2_user';
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
	user_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	user_email varchar(64) NOT NULL DEFAULT \'\',
	user_name varchar(32) NOT NULL DEFAULT \'\',
	user_form varchar(16) NOT NULL DEFAULT \'\',
	user_firstname varchar(32) NOT NULL DEFAULT \'\',
	user_lastname varchar(32) NOT NULL DEFAULT \'\',
	user_gender int(11) unsigned NOT NULL DEFAULT 0,
	user_phone varchar(32) NOT NULL DEFAULT \'\',
	user_fax varchar(32) NOT NULL DEFAULT \'\',
	user_mobile varchar(32) NOT NULL DEFAULT \'\',
	user_password varchar(256) NOT NULL DEFAULT \'\',
	user_status tinyint(1) unsigned NOT NULL DEFAULT 0,
	user_token varchar(32) NOT NULL DEFAULT \'\',
	user_create_time int(11) unsigned NOT NULL DEFAULT 0,
	user_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	user_update_time int(11) unsigned NOT NULL DEFAULT 0,
	user_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (user_id),
	KEY user_email (user_email),
	KEY user_firstname (user_firstname),
	KEY user_lastname (user_lastname),
	KEY user_gender (user_gender),
	KEY user_phone (user_phone),
	KEY user_fax (user_fax),
	KEY user_mobile (user_mobile),
	KEY user_status (user_status),
	KEY user_token (user_token),
	KEY user_create_time (user_create_time),
	KEY user_create_user_id (user_create_user_id),
	KEY user_update_time (user_update_time),
	KEY user_update_user_id (user_update_user_id)
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
 * update table DBV-1.1
 */
if (($av_tbl==1)&&($ab_tbl==0)) {
	$__datatable_do=true;
	$av_tbl=1;
	$ab_tbl=1;

	$QupdateData=new \osWFrame\Core\Database();
	$QupdateData->prepare('
ALTER TABLE :table:
	ADD user_token_custom varchar(32) NOT NULL DEFAULT \'\' AFTER user_token,
	ADD user_token_api varchar(32) NOT NULL DEFAULT \'\' AFTER user_token_custom,
	ADD user_image varchar(128) NOT NULL DEFAULT \'\' AFTER user_email,
	ADD user_avatar varchar(128) NOT NULL DEFAULT \'\' AFTER user_image,
	ADD INDEX user_token_custom (user_token_custom),
	ADD INDEX user_token_api (user_token_api),
	CHANGE user_token user_token varchar(32) COLLATE \'utf8mb4_general_ci\' NOT NULL DEFAULT \'\' AFTER user_status;
');
	$QupdateData->bindRaw(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
	$QupdateData->execute();
	if ($QupdateData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QupdateData->getErrorMessage();
		$av_tbl=1;
		$ab_tbl=0;
	}
}

/*
 * update table DBV-1.2
 */
if (($av_tbl==1)&&($ab_tbl==1)) {
	$__datatable_do=true;
	$av_tbl=1;
	$ab_tbl=2;

	$QupdateData=new \osWFrame\Core\Database();
	$QupdateData->prepare('
ALTER TABLE :table:
	DROP user_token,
	DROP user_token_custom,
	DROP user_token_api;;
');
	$QupdateData->bindRaw(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
	$QupdateData->execute();
	if ($QupdateData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QupdateData->getErrorMessage();
		$av_tbl=1;
		$ab_tbl=1;
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