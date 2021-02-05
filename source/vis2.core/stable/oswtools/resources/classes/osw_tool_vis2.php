<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link http://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */
class osW_Tool_VIS2 extends osW_Tool_Object {

	private $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function parseScript($_vis2_script, $object, $version, $time=0) {

		switch($version) {
			case '1':
				return $this->parseScript1($_vis2_script, $object, $time);
				break;
			default:
				return false;
		}
	}

	public function parseScript1($_vis2_script, $object, $time=0) {
		if ($time==0) {
			$time=time();
		}

		// User
		if (isset($object->data['values_json']['vis2_admin_user_id'])) {
			$QreadData=osW_Tool_Database::getInstance()->query('SELECT user_id FROM :table_vis2_user: WHERE user_id=:user_id:');
			$QreadData->bindRaw(':table_vis2_user:', $object->data['values_json']['database_prefix'].'vis2_user');
			$QreadData->bindInt(':user_id:', $object->data['values_json']['vis2_admin_user_id']);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_user: (user_email, user_name, user_form, user_firstname, user_lastname, user_gender, user_password, user_status, user_create_time, user_update_time) VALUES (:user_email:, :user_name:, :user_form:, :user_firstname:, :user_lastname:, :user_gender:, :user_password:, :user_status:, :user_create_time:, :user_update_time:)');
				$QinsertData->bindRaw(':table_vis2_user:', $object->data['values_json']['database_prefix'].'vis2_user');
				$QinsertData->bindValue(':user_email:', $object->data['values_json']['vis2_admin_email']);
				$QinsertData->bindValue(':user_name:', $object->data['values_json']['vis2_admin_username']);
				$QinsertData->bindValue(':user_form:', $object->data['values_json']['vis2_admin_form']);
				$QinsertData->bindValue(':user_firstname:', $object->data['values_json']['vis2_admin_firstname']);
				$QinsertData->bindValue(':user_lastname:', $object->data['values_json']['vis2_admin_lastname']);
				$QinsertData->bindInt(':user_gender:', $object->data['values_json']['vis2_admin_gender']);
				$QinsertData->bindValue(':user_password:', $object->data['values_json']['vis2_admin_password']);
				$QinsertData->bindInt(':user_status:', 1);
				$QinsertData->bindInt(':user_create_time:', $time);
				$QinsertData->bindInt(':user_update_time:', $time);
				$QinsertData->execute();
				$user_id=$QinsertData->nextID();

				$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_user: SET user_create_user_id=:user_create_user_id:, user_update_user_id=:user_update_user_id: WHERE user_id=:user_id:');
				$QupdateData->bindRaw(':table_vis2_user:', $object->data['values_json']['database_prefix'].'vis2_user');
				$QupdateData->bindInt(':user_create_user_id:', $user_id);
				$QupdateData->bindInt(':user_update_user_id:', $user_id);
				$QupdateData->bindInt(':user_id:', $user_id);
				$QupdateData->execute();
			} else {
				$user_id=$object->data['values_json']['vis2_admin_user_id'];
			}
		} else {
			$QreadData=osW_Tool_Database::getInstance()->query('SELECT user_id FROM :table_vis2_user: WHERE user_email=:user_email:');
			$QreadData->bindRaw(':table_vis2_user:', $object->data['values_json']['database_prefix'].'vis2_user');
			$QreadData->bindValue(':user_email:', $object->data['values_json']['vis2_admin_email']);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_user: (user_email, user_name, user_form, user_firstname, user_lastname, user_gender, user_password, user_status, user_create_time, user_update_time) VALUES (:user_email:, :user_name:, :user_form:, :user_firstname:, :user_lastname:, :user_gender:, :user_password:, :user_status:, :user_create_time:, :user_update_time:)');
				$QinsertData->bindRaw(':table_vis2_user:', $object->data['values_json']['database_prefix'].'vis2_user');
				$QinsertData->bindValue(':user_email:', $object->data['values_json']['vis2_admin_email']);
				$QinsertData->bindValue(':user_name:', $object->data['values_json']['vis2_admin_username']);
				$QinsertData->bindValue(':user_form:', $object->data['values_json']['vis2_admin_form']);
				$QinsertData->bindValue(':user_firstname:', $object->data['values_json']['vis2_admin_firstname']);
				$QinsertData->bindValue(':user_lastname:', $object->data['values_json']['vis2_admin_lastname']);
				$QinsertData->bindInt(':user_gender:', $object->data['values_json']['vis2_admin_gender']);
				$QinsertData->bindValue(':user_password:', $object->data['values_json']['vis2_admin_password']);
				$QinsertData->bindInt(':user_status:', 1);
				$QinsertData->bindInt(':user_create_time:', $time);
				$QinsertData->bindInt(':user_update_time:', $time);
				$QinsertData->execute();
				$user_id=$QinsertData->nextID();

				$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_user: SET user_create_user_id=:user_create_user_id:, user_update_user_id=:user_update_user_id: WHERE user_id=:user_id:');
				$QupdateData->bindRaw(':table_vis2_user:', $object->data['values_json']['database_prefix'].'vis2_user');
				$QupdateData->bindInt(':user_create_user_id:', $user_id);
				$QupdateData->bindInt(':user_update_user_id:', $user_id);
				$QupdateData->bindInt(':user_id:', $user_id);
				$QupdateData->execute();
			} else {
				$QreadData->next();
				$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_user: SET user_email=:user_email:, user_name=:user_name:, user_form=:user_form:, user_firstname=:user_firstname:, user_lastname=:user_lastname:, user_gender=:user_gender:, user_password=:user_password:, user_status=:user_status: WHERE user_id=:user_id:');
				$QupdateData->bindRaw(':table_vis2_user:', $object->data['values_json']['database_prefix'].'vis2_user');
				$QupdateData->bindValue(':user_email:', $object->data['values_json']['vis2_admin_email']);
				$QupdateData->bindValue(':user_name:', $object->data['values_json']['vis2_admin_username']);
				$QupdateData->bindValue(':user_form:', $object->data['values_json']['vis2_admin_form']);
				$QupdateData->bindValue(':user_firstname:', $object->data['values_json']['vis2_admin_firstname']);
				$QupdateData->bindValue(':user_lastname:', $object->data['values_json']['vis2_admin_lastname']);
				$QupdateData->bindInt(':user_gender:', $object->data['values_json']['vis2_admin_gender']);
				$QupdateData->bindValue(':user_password:', $object->data['values_json']['vis2_admin_password']);
				$QupdateData->bindInt(':user_status:', 1);
				$QupdateData->bindInt(':user_id:', $QreadData->result['user_id']);
				$QupdateData->execute();
				if ($QupdateData->affectedRows()>0) {
					$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_user: SET user_update_time=:user_update_time:, user_update_user_id=:user_update_user_id: WHERE user_id=:user_id:');
					$QupdateData->bindRaw(':table_vis2_user:', $object->data['values_json']['database_prefix'].'vis2_user');
					$QupdateData->bindInt(':user_update_time:', $time);
					$QupdateData->bindInt(':user_update_user_id:', $user_id);
					$QupdateData->bindInt(':user_id:', $QreadData->result['user_id']);
					$QupdateData->execute();
				}
				$user_id=$QreadData->result['user_id'];
			}
		}

		// Tool
		$QreadData=osW_Tool_Database::getInstance()->query('SELECT tool_id FROM :table_vis2_tool: WHERE tool_name_intern=:tool_name_intern:');
		$QreadData->bindRaw(':table_vis2_tool:', $object->data['values_json']['database_prefix'].'vis2_tool');
		$QreadData->bindValue(':tool_name_intern:', $_vis2_script['tool']['tool_name_intern']);
		$QreadData->execute();
		if ($QreadData->numberOfRows()==0) {
			$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_tool: (tool_name, tool_name_intern, tool_description, tool_ispublic, tool_hide_logon, tool_hide_navigation, tool_use_mandant, tool_use_mandantswitch, tool_create_time, tool_create_user_id, tool_update_time, tool_update_user_id) VALUES (:tool_name:, :tool_name_intern:, :tool_description:, :tool_ispublic:, :tool_hide_logon:, :tool_hide_navigation:, :tool_use_mandant:, :tool_use_mandantswitch:, :tool_create_time:, :tool_create_user_id:, :tool_update_time:, :tool_update_user_id:)');
			$QinsertData->bindRaw(':table_vis2_tool:', $object->data['values_json']['database_prefix'].'vis2_tool');
			$QinsertData->bindValue(':tool_name:', $_vis2_script['tool']['tool_name']);
			$QinsertData->bindValue(':tool_name_intern:', $_vis2_script['tool']['tool_name_intern']);
			$QinsertData->bindValue(':tool_description:', $_vis2_script['tool']['tool_description']);
			$QinsertData->bindInt(':tool_ispublic:', $_vis2_script['tool']['tool_ispublic']);
			$QinsertData->bindInt(':tool_hide_logon:', $_vis2_script['tool']['tool_hide_logon']);
			$QinsertData->bindInt(':tool_hide_navigation:', $_vis2_script['tool']['tool_hide_navigation']);
			$QinsertData->bindInt(':tool_use_mandant:', $_vis2_script['tool']['tool_use_mandant']);
			$QinsertData->bindInt(':tool_use_mandantswitch:', $_vis2_script['tool']['tool_use_mandantswitch']);
			$QinsertData->bindInt(':tool_create_time:', $time);
			$QinsertData->bindInt(':tool_create_user_id:', $user_id);
			$QinsertData->bindInt(':tool_update_time:', $time);
			$QinsertData->bindInt(':tool_update_user_id:', $user_id);
			$QinsertData->execute();
			$tool_id=$QinsertData->nextID();
		} else {
			$QreadData->next();
			$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_tool: SET tool_name=:tool_name:, tool_description=:tool_description:, tool_ispublic=:tool_ispublic:, tool_hide_logon=:tool_hide_logon:, tool_hide_navigation=:tool_hide_navigation:, tool_use_mandant=:tool_use_mandant:, tool_use_mandantswitch=:tool_use_mandantswitch: WHERE tool_id=:tool_id:');
			$QupdateData->bindRaw(':table_vis2_tool:', $object->data['values_json']['database_prefix'].'vis2_tool');
			$QupdateData->bindValue(':tool_name:', $_vis2_script['tool']['tool_name']);
			$QupdateData->bindValue(':tool_description:', $_vis2_script['tool']['tool_description']);
			$QupdateData->bindValue(':tool_ispublic:', $_vis2_script['tool']['tool_ispublic']);
			$QupdateData->bindValue(':tool_hide_logon:', $_vis2_script['tool']['tool_hide_logon']);
			$QupdateData->bindValue(':tool_hide_navigation:', $_vis2_script['tool']['tool_hide_navigation']);
			$QupdateData->bindInt(':tool_use_mandant:', $_vis2_script['tool']['tool_use_mandant']);
			$QupdateData->bindInt(':tool_use_mandantswitch:', $_vis2_script['tool']['tool_use_mandantswitch']);
			$QupdateData->bindInt(':tool_id:', $QreadData->result['tool_id']);
			$QupdateData->execute();
			if ($QupdateData->affectedRows()>0) {
				$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_tool: SET tool_update_time=:tool_update_time:, tool_update_user_id=:tool_update_user_id: WHERE tool_id=:tool_id:');
				$QupdateData->bindRaw(':table_vis2_tool:', $object->data['values_json']['database_prefix'].'vis2_tool');
				$QupdateData->bindInt(':tool_update_time:', $time);
				$QupdateData->bindInt(':tool_update_user_id:', $user_id);
				$QupdateData->bindInt(':tool_id:', $QreadData->result['tool_id']);
				$QupdateData->execute();
			}
			$tool_id=$QreadData->result['tool_id'];
		}

		// Group
		$ar_groups=array();
		foreach ($_vis2_script['group'] as $id => $group) {
			$QreadData=osW_Tool_Database::getInstance()->query('SELECT group_id FROM :table_vis2_group: WHERE tool_id=:tool_id: AND group_name_intern=:group_name_intern:');
			$QreadData->bindRaw(':table_vis2_group:', $object->data['values_json']['database_prefix'].'vis2_group');
			$QreadData->bindInt(':tool_id:', $tool_id);
			$QreadData->bindValue(':group_name_intern:', $group['group_name_intern']);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_group: (group_name_intern, group_name, group_description, group_ispublic, tool_id, group_create_time, group_create_user_id, group_update_time, group_update_user_id) VALUES (:group_name_intern:, :group_name:, :group_description:, :group_ispublic:, :tool_id:, :group_create_time:, :group_create_user_id:, :group_update_time:, :group_update_user_id:)');
				$QinsertData->bindRaw(':table_vis2_group:', $object->data['values_json']['database_prefix'].'vis2_group');
				$QinsertData->bindValue(':group_name:',$group['group_name']);
				$QinsertData->bindValue(':group_name_intern:', $group['group_name_intern']);
				$QinsertData->bindValue(':group_description:', $group['group_description']);
				$QinsertData->bindInt(':group_ispublic:', $group['group_ispublic']);
				$QinsertData->bindInt(':group_create_time:', $time);
				$QinsertData->bindInt(':group_create_user_id:', $user_id);
				$QinsertData->bindInt(':group_update_time:', $time);
				$QinsertData->bindInt(':group_update_user_id:', $user_id);
				$QinsertData->bindInt(':tool_id:', $tool_id);
				$QinsertData->execute();
				$group_id=$QinsertData->nextID();
			} else {
				$QreadData->next();
				$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_group: SET group_name=:group_name:, group_description=:group_description:, group_ispublic=:group_ispublic:, tool_id=:tool_id: WHERE group_id=:group_id:');
				$QupdateData->bindRaw(':table_vis2_group:', $object->data['values_json']['database_prefix'].'vis2_group');
				$QupdateData->bindValue(':group_name:',$group['group_name']);
				$QupdateData->bindValue(':group_description:', $group['group_description']);
				$QupdateData->bindInt(':group_ispublic:', $group['group_ispublic']);
				$QupdateData->bindInt(':group_id:', $QreadData->result['group_id']);
				$QupdateData->bindInt(':tool_id:', $tool_id);
				$QupdateData->execute();
				if ($QupdateData->affectedRows()>0) {
					$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_group: SET group_update_time=:group_update_time:, group_update_user_id=:group_update_user_id: WHERE group_id=:group_id:');
					$QupdateData->bindRaw(':table_vis2_group:', $object->data['values_json']['database_prefix'].'vis2_group');
					$QupdateData->bindInt(':group_update_time:', $time);
					$QupdateData->bindInt(':group_update_user_id:', $user_id);
					$QupdateData->bindInt(':group_id:', $QreadData->result['group_id']);
					$QupdateData->execute();
				}
				$group_id=$QreadData->result['group_id'];
			}
			$ar_groups[$id]=$group_id;
		}

		// Permission
		foreach ($_vis2_script['permission'] as $permission) {
			$QreadData=osW_Tool_Database::getInstance()->query('SELECT permission_id FROM :table_vis2_permission: WHERE tool_id=:tool_id: AND permission_flag=:permission_flag:');
			$QreadData->bindRaw(':table_vis2_permission:', $object->data['values_json']['database_prefix'].'vis2_permission');
			$QreadData->bindInt(':tool_id:', $tool_id);
			$QreadData->bindValue(':permission_flag:', $permission['permission_flag']);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_permission: (tool_id, permission_flag, permission_title, permission_ispublic, permission_create_time, permission_create_user_id, permission_update_time, permission_update_user_id) VALUES (:tool_id:, :permission_flag:, :permission_title:, :permission_ispublic:, :permission_create_time:, :permission_create_user_id:, :permission_update_time:, :permission_update_user_id:)');
				$QinsertData->bindRaw(':table_vis2_permission:', $object->data['values_json']['database_prefix'].'vis2_permission');
				$QinsertData->bindValue(':permission_flag:', $permission['permission_flag']);
				$QinsertData->bindValue(':permission_title:', $permission['permission_title']);
				$QinsertData->bindInt(':permission_ispublic:', $permission['permission_ispublic']);
				$QinsertData->bindInt(':permission_create_time:', $time);
				$QinsertData->bindInt(':permission_create_user_id:', $user_id);
				$QinsertData->bindInt(':permission_update_time:', $time);
				$QinsertData->bindInt(':permission_update_user_id:', $user_id);
				$QinsertData->bindInt(':tool_id:', $tool_id);
				$QinsertData->execute();
			} else {
				$QreadData->next();
				$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_permission: SET tool_id=:tool_id:, permission_flag=:permission_flag:, permission_title=:permission_title:, permission_ispublic=:permission_ispublic: WHERE permission_id=:permission_id:');
				$QupdateData->bindRaw(':table_vis2_permission:', $object->data['values_json']['database_prefix'].'vis2_permission');
				$QupdateData->bindValue(':permission_flag:', $permission['permission_flag']);
				$QupdateData->bindValue(':permission_title:', $permission['permission_title']);
				$QupdateData->bindInt(':permission_ispublic:', $permission['permission_ispublic']);
				$QupdateData->bindInt(':tool_id:', $tool_id);
				$QupdateData->bindInt(':permission_id:', $QreadData->result['permission_id']);
				$QupdateData->execute();
				if ($QupdateData->affectedRows()>0) {
					$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_permission: SET permission_update_time=:permission_update_time:, permission_update_user_id=:permission_update_user_id: WHERE permission_id=:permission_id:');
					$QupdateData->bindRaw(':table_vis2_permission:', $object->data['values_json']['database_prefix'].'vis2_permission');
					$QupdateData->bindInt(':permission_update_time:', $time);
					$QupdateData->bindInt(':permission_update_user_id:', $user_id);
					$QupdateData->bindInt(':permission_id:', $QreadData->result['permission_id']);
					$QupdateData->execute();
				}
			}
		}

		// User-Tool
		$QreadData=osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis2_user_tool: WHERE user_id=:user_id: AND tool_id=:tool_id:');
		$QreadData->bindRaw(':table_vis2_user_tool:', $object->data['values_json']['database_prefix'].'vis2_user_tool');
		$QreadData->bindInt(':user_id:', $user_id);
		$QreadData->bindInt(':tool_id:', $tool_id);
		$QreadData->execute();
		if ($QreadData->numberOfRows()==0) {
			$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_user_tool: (user_id, tool_id, user_tool_create_time, user_tool_create_user_id, user_tool_update_time, user_tool_update_user_id) VALUES (:user_id:, :tool_id:, :user_tool_create_time:, :user_tool_create_user_id:, :user_tool_update_time:, :user_tool_update_user_id:)');
			$QinsertData->bindRaw(':table_vis2_user_tool:', $object->data['values_json']['database_prefix'].'vis2_user_tool');
			$QinsertData->bindInt(':user_id:', $user_id);
			$QinsertData->bindInt(':tool_id:', $tool_id);
			$QinsertData->bindInt(':user_tool_create_time:', $time);
			$QinsertData->bindInt(':user_tool_create_user_id:', $user_id);
			$QinsertData->bindInt(':user_tool_update_time:', $time);
			$QinsertData->bindInt(':user_tool_update_user_id:', $user_id);
			$QinsertData->execute();
		}

		// User-Group
		foreach ($ar_groups as $group_id) {
			$QreadData=osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis2_user_group: WHERE user_id=:user_id: AND group_id=:group_id: AND tool_id=:tool_id:');
			$QreadData->bindRaw(':table_vis2_user_group:', $object->data['values_json']['database_prefix'].'vis2_user_group');
			$QreadData->bindInt(':user_id:', $user_id);
			$QreadData->bindInt(':group_id:', $group_id);
			$QreadData->bindInt(':tool_id:', $tool_id);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_user_group: (user_id, group_id, tool_id, user_group_create_time, user_group_create_user_id, user_group_update_time, user_group_update_user_id) VALUES (:user_id:, :group_id:, :tool_id:, :user_group_create_time:, :user_group_create_user_id:, :user_group_update_time:, :user_group_update_user_id:)');
				$QinsertData->bindRaw(':table_vis2_user_group:', $object->data['values_json']['database_prefix'].'vis2_user_group');
				$QinsertData->bindInt(':user_id:', $user_id);
				$QinsertData->bindInt(':group_id:', $group_id);
				$QinsertData->bindInt(':tool_id:', $tool_id);
				$QinsertData->bindInt(':user_group_create_time:', $time);
				$QinsertData->bindInt(':user_group_create_user_id:', $user_id);
				$QinsertData->bindInt(':user_group_update_time:', $time);
				$QinsertData->bindInt(':user_group_update_user_id:', $user_id);
				$QinsertData->execute();
			}
		}

		$_pages=array();
		$_navigation=array();
		foreach ($_vis2_script['navigation'] as $navigation) {
			// Page
			$QreadData=osW_Tool_Database::getInstance()->query('SELECT page_id FROM :table_vis2_page: WHERE page_name_intern=:page_name_intern: AND tool_id=:tool_id:');
			$QreadData->bindRaw(':table_vis2_page:', $object->data['values_json']['database_prefix'].'vis2_page');
			$QreadData->bindValue(':page_name_intern:', $navigation['page']['page_name_intern']);
			$QreadData->bindInt(':tool_id:', $tool_id);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_page: (tool_id, page_name_intern, page_name, page_description, page_ispublic, page_create_time, page_create_user_id, page_update_time, page_update_user_id) VALUES (:tool_id:, :page_name_intern:, :page_name:, :page_description:, :page_ispublic:, :page_create_time:, :page_create_user_id:, :page_update_time:, :page_update_user_id:)');
				$QinsertData->bindRaw(':table_vis2_page:', $object->data['values_json']['database_prefix'].'vis2_page');
				$QinsertData->bindInt(':tool_id:', $tool_id);
				$QinsertData->bindValue(':page_name:', $navigation['page']['page_name']);
				$QinsertData->bindValue(':page_name_intern:', $navigation['page']['page_name_intern']);
				$QinsertData->bindValue(':page_description:', $navigation['page']['page_description']);
				$QinsertData->bindInt(':page_ispublic:', $navigation['page']['page_ispublic']);
				$QinsertData->bindInt(':page_create_time:', $time);
				$QinsertData->bindInt(':page_create_user_id:', $user_id);
				$QinsertData->bindInt(':page_update_time:', $time);
				$QinsertData->bindInt(':page_update_user_id:', $user_id);
				$QinsertData->execute();
				$_pages[$navigation['page']['page_name_intern']]=$QreadData->nextId();
			} else {
				$QreadData->next();
				$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_page: SET tool_id=:tool_id:, page_name=:page_name:, page_description=:page_description:, page_ispublic=:page_ispublic: WHERE page_id=:page_id:');
				$QupdateData->bindRaw(':table_vis2_page:', $object->data['values_json']['database_prefix'].'vis2_page');
				$QupdateData->bindInt(':tool_id:', $tool_id);
				$QupdateData->bindValue(':page_name:', $navigation['page']['page_name']);
				$QupdateData->bindValue(':page_description:', $navigation['page']['page_description']);
				$QupdateData->bindInt(':page_ispublic:', $navigation['page']['page_ispublic']);
				$QupdateData->bindInt(':user_status:', 1);
				$QupdateData->bindInt(':page_id:', $QreadData->result['page_id']);
				$QupdateData->execute();
				if ($QupdateData->affectedRows()>0) {
					$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_page: SET page_update_time=:page_update_time:, page_update_user_id=:page_update_user_id: WHERE page_id=:page_id:');
					$QupdateData->bindRaw(':table_vis2_page:', $object->data['values_json']['database_prefix'].'vis2_page');
					$QupdateData->bindInt(':page_update_time:', $time);
					$QupdateData->bindInt(':page_update_user_id:', $user_id);
					$QupdateData->bindInt(':page_id:', $QreadData->result['page_id']);
					$QupdateData->execute();
				}
				$_pages[$navigation['page']['page_name_intern']]=$QreadData->value('page_id');
			}

			foreach ($navigation['page']['permission'] as $permission) {
				// Page-Permission
				$QreadData=osW_Tool_Database::getInstance()->query('SELECT page_id FROM :table_vis2_page_permission: WHERE page_id=:page_id: AND tool_id=:tool_id: AND permission_flag=:permission_flag:');
				$QreadData->bindRaw(':table_vis2_page_permission:', $object->data['values_json']['database_prefix'].'vis2_page_permission');
				$QreadData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
				$QreadData->bindInt(':tool_id:', $tool_id);
				$QreadData->bindValue(':permission_flag:', $permission);
				$QreadData->execute();
				if ($QreadData->numberOfRows()==0) {
					$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_page_permission: (page_id, tool_id, permission_flag, page_permission_create_time, page_permission_create_user_id, page_permission_update_time, page_permission_update_user_id) VALUES (:page_id:, :tool_id:, :permission_flag:, :page_permission_create_time:, :page_permission_create_user_id:, :page_permission_update_time:, :page_permission_update_user_id:)');
					$QinsertData->bindRaw(':table_vis2_page_permission:', $object->data['values_json']['database_prefix'].'vis2_page_permission');
					$QinsertData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
					$QinsertData->bindInt(':tool_id:', $tool_id);
					$QinsertData->bindValue(':permission_flag:', $permission);
					$QinsertData->bindInt(':page_permission_create_time:', $time);
					$QinsertData->bindInt(':page_permission_create_user_id:', $user_id);
					$QinsertData->bindInt(':page_permission_update_time:', $time);
					$QinsertData->bindInt(':page_permission_update_user_id:', $user_id);
					$QinsertData->execute();
				}
			}

			foreach ($navigation['permission'] as $group_id => $group_permission) {
				foreach ($group_permission as $permission) {
					// Page-Permission
					$QreadData=osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis2_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page: AND permission_flag=:permission_flag:');
					$QreadData->bindRaw(':table_vis2_group_permission:', $object->data['values_json']['database_prefix'].'vis2_group_permission');
					$QreadData->bindInt(':group_id:', $ar_groups[$group_id]);
					$QreadData->bindValue(':permission_page:', $navigation['page']['page_name_intern']);
					$QreadData->bindValue(':permission_flag:', $permission);
					$QreadData->execute();
					if ($QreadData->numberOfRows()==0) {
						$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_group_permission: (group_id, permission_page, permission_flag, group_permission_create_time, group_permission_create_user_id, group_permission_update_time, group_permission_update_user_id) VALUES (:group_id:, :permission_page:, :permission_flag:, :group_permission_create_time:, :group_permission_create_user_id:, :group_permission_update_time:, :group_permission_update_user_id:)');
						$QinsertData->bindRaw(':table_vis2_group_permission:', $object->data['values_json']['database_prefix'].'vis2_group_permission');
						$QinsertData->bindInt(':group_id:', $ar_groups[$group_id]);
						$QinsertData->bindValue(':permission_page:', $navigation['page']['page_name_intern']);
						$QinsertData->bindValue(':permission_flag:', $permission);
						$QinsertData->bindInt(':group_permission_create_time:', $time);
						$QinsertData->bindInt(':group_permission_create_user_id:', $user_id);
						$QinsertData->bindInt(':group_permission_update_time:', $time);
						$QinsertData->bindInt(':group_permission_update_user_id:', $user_id);
						$QinsertData->execute();
					}
				}
			}

			// Navigation
			$QreadData=osW_Tool_Database::getInstance()->query('SELECT * FROM :table_vis2_navigation: WHERE tool_id=:tool_id: AND page_id=:page_id:');
			$QreadData->bindRaw(':table_vis2_navigation:', $object->data['values_json']['database_prefix'].'vis2_navigation');
			$QreadData->bindInt(':tool_id:', $tool_id);
			$QreadData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
			$QreadData->execute();
			if ($QreadData->numberOfRows()==0) {
				$QinsertData=osW_Tool_Database::getInstance()->query('INSERT INTO :table_vis2_navigation: (tool_id, page_id, navigation_parent_id, navigation_title, navigation_sortorder, navigation_ispublic, navigation_create_time, navigation_create_user_id, navigation_update_time, navigation_update_user_id) VALUES (:tool_id:, :page_id:, :navigation_parent_id:, :navigation_title:, :navigation_sortorder:, :navigation_ispublic:, :navigation_create_time:, :navigation_create_user_id:, :navigation_update_time:, :navigation_update_user_id:)');
				$QinsertData->bindRaw(':table_vis2_navigation:', $object->data['values_json']['database_prefix'].'vis2_navigation');
				$QinsertData->bindInt(':tool_id:', $tool_id);
				$QinsertData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
				if (isset($_navigation[$navigation['navigation_parent_id']])) {
					$QinsertData->bindInt(':navigation_parent_id:', $_navigation[$navigation['navigation_parent_id']]);
				} else {
					$QinsertData->bindInt(':navigation_parent_id:', 0);
				}
				$QinsertData->bindValue(':navigation_title:', $navigation['navigation_title']);
				$QinsertData->bindInt(':navigation_sortorder:', $navigation['navigation_sortorder']);
				$QinsertData->bindInt(':navigation_ispublic:', $navigation['navigation_ispublic']);
				$QinsertData->bindInt(':navigation_create_time:', $time);
				$QinsertData->bindInt(':navigation_create_user_id:', $user_id);
				$QinsertData->bindInt(':navigation_update_time:', $time);
				$QinsertData->bindInt(':navigation_update_user_id:', $user_id);
				$QinsertData->execute();
				$_navigation[$navigation['page']['page_name_intern']]=$QinsertData->nextId();
			} else {
				$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_navigation: SET navigation_title=:navigation_title:, navigation_sortorder=:navigation_sortorder:, navigation_ispublic=:navigation_ispublic: WHERE tool_id=:tool_id: AND page_id=:page_id:');
				$QupdateData->bindRaw(':table_vis2_navigation:', $object->data['values_json']['database_prefix'].'vis2_navigation');
				$QupdateData->bindValue(':navigation_title:', $navigation['navigation_title']);
				$QupdateData->bindInt(':navigation_sortorder:', $navigation['navigation_sortorder']);
				$QupdateData->bindInt(':navigation_ispublic:', $navigation['navigation_ispublic']);
				$QupdateData->bindInt(':tool_id:', $tool_id);
				$QupdateData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
				$QupdateData->execute();
				if ($QupdateData->affectedRows()>0) {
					$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table_vis2_navigation: SET navigation_update_time=:navigation_update_time:, navigation_update_user_id=:navigation_update_user_id: WHERE navigation_id=:navigation_id:');
					$QupdateData->bindRaw(':table_vis2_navigation:', $object->data['values_json']['database_prefix'].'vis2_navigation');
					$QupdateData->bindInt(':navigation_update_time:', $time);
					$QupdateData->bindInt(':navigation_update_user_id:', $user_id);
					$QupdateData->bindInt(':navigation_id:', $QreadData->result['navigation_id']);
					$QupdateData->execute();
				}
				$QreadData->next();
				$_navigation[$navigation['page']['page_name_intern']]=$QreadData->result['navigation_id'];
			}
		}

		return $user_id;
	}

	public function patchCreateUpdate($table, $prefix, $time, $user_id) {
		$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table: SET :create_time_key:=:create_time_value: WHERE :create_time_key:=:create_time_null:');
		$QupdateData->bindRaw(':table:', $table);
		$QupdateData->bindRaw(':create_time_key:', $prefix.'create_time');
		$QupdateData->bindInt(':create_time_value:', $time);
		$QupdateData->bindInt(':create_time_null:', 0);
		$QupdateData->execute();

		$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table: SET :create_user_id_key:=:create_user_id_value: WHERE :create_user_id_key:=:create_user_id_null:');
		$QupdateData->bindRaw(':table:', $table);
		$QupdateData->bindRaw(':create_user_id_key:', $prefix.'create_user_id');
		$QupdateData->bindInt(':create_user_id_value:', $user_id);
		$QupdateData->bindInt(':create_user_id_null:', 0);
		$QupdateData->execute();

		$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table: SET :update_time_key:=:update_time_value: WHERE :update_time_key:=:update_time_null:');
		$QupdateData->bindRaw(':table:', $table);
		$QupdateData->bindRaw(':update_time_key:', $prefix.'update_time');
		$QupdateData->bindInt(':update_time_value:', $time);
		$QupdateData->bindInt(':update_time_null:', 0);
		$QupdateData->execute();

		$QupdateData=osW_Tool_Database::getInstance()->query('UPDATE :table: SET :update_user_id_key:=:update_user_id_value: WHERE :update_user_id_key:=:update_user_id_null:');
		$QupdateData->bindRaw(':table:', $table);
		$QupdateData->bindRaw(':update_user_id_key:', $prefix.'update_user_id');
		$QupdateData->bindInt(':update_user_id_value:', $user_id);
		$QupdateData->bindInt(':update_user_id_null:', 0);
		$QupdateData->execute();
	}

	/**
	 *
	 * @return osW_Tool_VIS2	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>