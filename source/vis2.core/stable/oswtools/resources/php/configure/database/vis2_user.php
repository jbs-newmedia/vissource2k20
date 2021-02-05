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
 * init
 */
$__datatable_table='vis2_user';
$__datatable_create=false;
$__datatable_do=false;

/*
 * check version of table
 */
$QreadData=osW_Tool_Database::getInstance()->query('SHOW TABLE STATUS LIKE :table:');
$QreadData->bindValue(':table:', $this->data['values_json']['database_prefix'].$__datatable_table);
$QreadData->execute();
if ($QreadData->numberOfRows()==1) {
	$QreadData->next();
	$avb_tbl=$QreadData->result['Comment'];
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

	$QwriteData=osW_Tool_Database::getInstance()->query('
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
	user_token varchar(32) DEFAULT NULL DEFAULT \'\',
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
) ENGINE='.$this->data['values_json']['database_engine'].' DEFAULT CHARSET='.$this->data['values_json']['database_character'].' COMMENT=:version:;
');
	$QwriteData->bindTable(':table:', $__datatable_table);
	$QwriteData->bindValue(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->query_handler===false) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->error;
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
	$QwriteData=osW_Tool_Database::getInstance()->query('ALTER TABLE :table: COMMENT=:version:;');
	$QwriteData->bindTable(':table:', $__datatable_table);
	$QwriteData->bindValue(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->query_handler===false) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->error;
	}
}

?>