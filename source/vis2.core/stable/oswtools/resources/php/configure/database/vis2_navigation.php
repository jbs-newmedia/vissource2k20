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
$__datatable_table='vis2_navigation';
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

	$QwriteData=new \osWFrame\Core\Database();
	$QwriteData->prepare('
CREATE TABLE :table: (
	navigation_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	tool_id int(10) unsigned NOT NULL DEFAULT 0,
	navigation_parent_id int(11) unsigned NOT NULL DEFAULT 0,
	navigation_title varchar(128) NOT NULL DEFAULT \'\',
	page_id int(11) unsigned NOT NULL DEFAULT 0,
	navigation_sortorder int(11) unsigned NOT NULL DEFAULT 0,
	navigation_intern_sortorder int(11) NOT NULL DEFAULT 0,
	navigation_ispublic int(11) unsigned NOT NULL DEFAULT 0,
	navigation_create_time int(11) unsigned NOT NULL DEFAULT 0,
	navigation_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	navigation_update_time int(11) unsigned NOT NULL DEFAULT 0,
	navigation_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (navigation_id),
	KEY tool_id (tool_id),
	KEY navigation_parent_id (navigation_parent_id),
	KEY navigation_sortorder (navigation_sortorder),
	KEY navigation_intern_sortorder (navigation_intern_sortorder),
	KEY navigation_ispublic (navigation_ispublic),
	KEY page_id (page_id),
	KEY navigation_create_time (navigation_create_time),
	KEY navigation_create_user_id (navigation_create_user_id),
	KEY navigation_update_time (navigation_update_time),
	KEY navigation_update_user_id (navigation_update_user_id)
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
	} else {
		$av_tbl=1;
		$ab_tbl=0;
	}
}

/*
 * update table DBV-1.1
 */
/*
if (($av_tbl==1)&&($ab_tbl==1)) {
	$__datatable_do=true;

	... query ...
	if ($QupdateData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QupdateData->getErrorMessage();
	} else {
		$av_tbl=1;
		$ab_tbl=1;
	}
}
*/

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