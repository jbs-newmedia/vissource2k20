<?php

/**
 * This file is part of the VIS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS
 * @link https://oswframe.com
 * @license MIT License
 */

/*
 * init
 */
$__datatable_table='vis_tool';
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
	$av_tbl=1;
	$ab_tbl=0;
	$__datatable_create=true;

	$QwriteData=new \osWFrame\Core\Database();
	$QwriteData->prepare('
CREATE TABLE :table: (
	tool_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	tool_name_intern varchar(64) NOT NULL DEFAULT \'\',
	tool_name varchar(64) NOT NULL DEFAULT \'\',
	tool_description varchar(128) NOT NULL DEFAULT \'\',
	tool_status tinyint(1) unsigned NOT NULL DEFAULT 0,
	tool_hide_logon tinyint(1) unsigned NOT NULL DEFAULT 0,
	tool_hide_navigation tinyint(1) unsigned NOT NULL DEFAULT 0,
	tool_use_mandant tinyint(1) unsigned NOT NULL DEFAULT 0,
	tool_use_mandantswitch tinyint(1) unsigned NOT NULL DEFAULT 0,
	tool_create_time int(11) unsigned NOT NULL DEFAULT 0,
	tool_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	tool_update_time int(11) unsigned NOT NULL DEFAULT 0,
	tool_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (tool_id),
	KEY tool_name_intern (tool_name_intern),
	KEY tool_name (tool_name),
	KEY tool_status (tool_status),
	KEY tool_hide_logon (tool_hide_logon),
	KEY tool_hide_navigation (tool_hide_navigation),
	KEY tool_use_mandant (tool_use_mandant),
	KEY tool_use_mandantswitch (tool_use_mandantswitch),
	KEY tool_create_time (tool_create_time),
	KEY tool_create_user_id (tool_create_user_id),
	KEY tool_update_time (tool_update_time),
	KEY tool_update_user_id (tool_update_user_id)
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
	}
}

/*
 * update table
 */
/*
if (($av_tbl<=1)&&($ab_tbl<1)) {
	$av_tbl=1;
	$ab_tbl=1;
	$__datatable_do=true;

	... code ...
}
*/

if ($__datatable_do===true) {
	$QwriteData=new \osWFrame\Core\Database();
	$QwriteData->prepare('ALTER TABLE :table: COMMENT=:version:;');
	$QwriteData->bindString(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
	$QwriteData->bindString(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->getErrorMessage();
	}
}

?>