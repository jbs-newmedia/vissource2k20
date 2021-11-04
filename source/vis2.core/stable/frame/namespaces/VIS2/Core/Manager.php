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

namespace VIS2\Core;

use osWFrame\Core as osWFrame;

class Manager {

	use osWFrame\BaseStaticTrait;
	use osWFrame\BaseConnectionTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=1;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=1;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 *
	 * @var array
	 */
	private static array $datacache=[];

	/**
	 *
	 * @var array
	 */
	private static array $tools=[];

	/**
	 *
	 * @var array
	 */
	private static array $tool_details=[];

	/**
	 *
	 * @var array
	 */
	private static array $users=[];

	/**
	 *
	 * @var array
	 */
	private static array $groups=[];

	/**
	 *
	 * @var array
	 */
	private static array $groupsbytoolid=[];

	/**
	 *
	 * @var array
	 */
	private static array $mandantebytoolid=[];

	/**
	 *
	 * @var array
	 */
	private static array $pagesbytoolid=[];

	/**
	 * @var array
	 */
	private static array $permissiontext=[];

	/**
	 * @var array
	 */
	private static array $navigation_real=[];

	/**
	 * @var array
	 */
	private static array $navigation_tree=[];

	/**
	 * @var array
	 */
	private static array $navigation_unsorted=[];

	/**
	 * @var array
	 */
	private static array $navigation_name2id=[];

	/**
	 * @var array
	 */
	private static array $navigation_data=[];

	/**
	 * Ermittelt Anzahl der Benutzer mit einer E-Mail-Adresse
	 *
	 * @param string $email
	 * @param bool $cached
	 * @return int
	 */
	public static function getUserCountByEMail(string $email, bool $cached=true):int {
		$cachename=__FUNCTION__.'#'.md5($email);
		if ((!isset(self::$datacache[$cachename]))||($cached!==true)) {
			$QselectUserCount=self::getConnection();
			$QselectUserCount->prepare('SELECT user_id FROM :table_vis2_user: WHERE user_email=:user_email:');
			$QselectUserCount->bindTable(':table_vis2_user:', 'vis2_user');
			$QselectUserCount->bindString(':user_email:', $email);
			$QselectUserCount->execute();
			self::$datacache[$cachename]=$QselectUserCount->rowCount();
		}

		return self::$datacache[$cachename];
	}

	/**
	 *
	 * Gibt Benutzerdetails anhand der E-Mail-Adresse zurück
	 *
	 * @param string $email
	 * @param bool $cached
	 * @return array|null
	 */
	public static function getUserDetailsByEMail(string $email, bool $cached=true):?array {
		$cachename=__FUNCTION__.'#'.md5($email);
		if ((!isset(self::$datacache[$cachename]))||($cached!==true)) {
			$QselectUserCount=self::getConnection();
			$QselectUserCount->prepare('SELECT * FROM :table_vis2_user: WHERE user_email=:user_email:');
			$QselectUserCount->bindTable(':table_vis2_user:', 'vis2_user');
			$QselectUserCount->bindString(':user_email:', $email);
			if ($QselectUserCount->exec()==1) {
				self::$datacache[$cachename]=$QselectUserCount->fetch();
			} else {
				return null;
			}
		}

		return self::$datacache[$cachename];
	}

	/**
	 * Ermittelt den Bunutzernamen anhand der Benutzer-Id
	 *
	 * @param int $user_id
	 * @return string
	 */
	public static function getUsernameById(int $user_id):string {
		if (self::$users==[]) {
			self::getUsers();
		}
		if (isset(self::$users[$user_id])) {
			return self::$users[$user_id];
		}

		return 'VIS-Benutzer';
	}

	/**
	 * Gibt eine Liste aller Tools zurück
	 *
	 * @return array
	 */
	public static function getTools():array {
		if (self::$tools==[]) {
			$QselectUsers=self::getConnection();
			$QselectUsers->prepare('SELECT * FROM :table_vis2_tool: WHERE 1 ORDER BY tool_name ASC');
			$QselectUsers->bindTable(':table_vis2_tool:', 'vis2_tool');
			foreach ($QselectUsers->query() as $tool) {
				self::$tools[$tool['tool_id']]=$tool['tool_name'];
			}
		}

		return self::$tools;
	}

	/**
	 * @param int $tool_id
	 * @param bool $force
	 * @return array
	 */
	public static function getToolDetails(int $tool_id, bool $force=false):array {
		if ((!isset(self::$tool_details[$tool_id]))||($force===true)) {
			self::$tool_details[$tool_id]=[];
			$QgetToolDetails=self::getConnection();
			$QgetToolDetails->prepare('SELECT * FROM :table_vis2_tool: WHERE tool_id=:tool_id:');
			$QgetToolDetails->bindTable(':table_vis2_tool:', 'vis2_tool');
			$QgetToolDetails->bindInt(':tool_id:', $tool_id);
			$QgetToolDetails->execute();
			if ($QgetToolDetails->exec()==1) {
				self::$tool_details[$tool_id]=$QgetToolDetails->fetch();
			}
		}

		return self::$tool_details[$tool_id];
	}

	/**
	 * Gibt eine Liste aller Benutzer zurück
	 *
	 * @return array
	 */
	public static function getUsers():array {
		if (self::$users==[]) {
			$QselectUsers=self::getConnection();
			$QselectUsers->prepare('SELECT * FROM :table_vis2_user: WHERE 1 ORDER BY user_lastname ASC, user_firstname ASC');
			$QselectUsers->bindTable(':table_vis2_user:', 'vis2_user');
			foreach ($QselectUsers->query() as $user) {
				self::$users[$user['user_id']]=$user['user_lastname'].' '.$user['user_firstname'];
			}
		}

		return self::$users;
	}

	/**
	 * Gibt eine Liste aller Gruppen zurück
	 *
	 * @return array
	 */
	public static function getGroups():array {
		if (self::$groups==[]) {
			$QselectUsers=self::getConnection();
			$QselectUsers->prepare('SELECT * FROM :table_vis2_group: WHERE 1 ORDER BY group_name ASC');
			$QselectUsers->bindTable(':table_vis2_group:', 'vis2_group');
			foreach ($QselectUsers->query() as $group) {
				self::$groups[$group['group_id']]=$group['group_name'];
			}
		}

		return self::$groups;
	}

	/**
	 * Gibt eine Liste aller Gruppen eines Tools zurück anhand der Tool-Id
	 *
	 * @param int $tool_id
	 * @param bool $force
	 * @return array
	 */
	public static function getGroupsByToolId(int $tool_id=0, bool $force=false):array {
		if ($tool_id==0) {
			$tool_id=Main::getToolId();
		}
		if ((!isset(self::$groupsbytoolid[$tool_id]))||($force===true)) {
			self::$groupsbytoolid[$tool_id]=[];
			$QselectUsers=self::getConnection();
			$QselectUsers->prepare('SELECT * FROM :table_vis2_group: WHERE tool_id=:tool_id: ORDER BY group_name ASC');
			$QselectUsers->bindTable(':table_vis2_group:', 'vis2_group');
			$QselectUsers->bindInt(':tool_id:', $tool_id);
			foreach ($QselectUsers->query() as $group) {
				self::$groupsbytoolid[$tool_id][$group['group_id']]=$group['group_name'];
			}
		}

		return self::$groupsbytoolid[$tool_id];
	}

	/**
	 * Gibt eine Liste aller Gruppen eines Tools zurück anhand der Tool-Id
	 *
	 * @param int $tool_id
	 * @param bool $with_all
	 * @param bool $force
	 * @return array
	 */
	public static function getMandantenByToolId(int $tool_id=0, bool $with_all=false, bool $force=false):array {
		if ($tool_id==0) {
			$tool_id=Main::getToolId();
		}
		if ((!isset(self::$mandantebytoolid[$tool_id]))||($force===true)) {
			self::$mandantebytoolid[$tool_id]=[];
			$QselectMandanten=self::getConnection();
			$QselectMandanten->prepare('SELECT * FROM :table_vis2_mandant: WHERE tool_id=:tool_id: ORDER BY mandant_name ASC');
			$QselectMandanten->bindTable(':table_vis2_mandant:', 'vis2_mandant');
			$QselectMandanten->bindInt(':tool_id:', $tool_id);
			foreach ($QselectMandanten->query() as $mandant) {
				self::$mandantebytoolid[$tool_id][$mandant['mandant_id']]=$mandant['mandant_name'];
			}
		}

		if ($with_all===true) {
			return [0=>'Alle']+self::$mandantebytoolid[$tool_id];
		}

		return self::$mandantebytoolid[$tool_id];
	}

	/**
	 * Gibt eine Liste aller Seiten eines Tools zurück anhand der Tool-Id
	 *
	 * @param int $tool_id
	 * @param bool $force
	 * @return array
	 */
	public static function getPagesByToolId(int $tool_id=0, bool $force=false):array {
		if ($tool_id==0) {
			$tool_id=Main::getToolId();
		}
		if ((!isset(self::$pagesbytoolid[$tool_id]))||($force===true)) {
			self::$pagesbytoolid[$tool_id]=[];
			$QselectPages=self::getConnection();
			$QselectPages->prepare('SELECT * FROM :table_vis2_page: WHERE tool_id=:tool_id: AND page_ispublic=:page_ispublic: ORDER BY page_name ASC, page_name_intern ASC');
			$QselectPages->bindTable(':table_vis2_page:', 'vis2_page');
			$QselectPages->bindInt(':tool_id:', $tool_id);
			$QselectPages->bindInt(':page_ispublic:', 1);
			foreach ($QselectPages->query() as $page) {
				self::$pagesbytoolid[$tool_id][$page['page_id']]=$page['page_name'].' ('.$page['page_name_intern'].')';
			}
		}

		return self::$pagesbytoolid[$tool_id];
	}

	/**
	 * Gibt eine Liste aller Recht-Texte eines Tools zurück anhand der Tool-Id
	 *
	 * @param int $tool_id
	 * @param bool $force
	 * @return array
	 */
	public static function getPermissionTextList(int $tool_id=0, bool $force=false):array {
		if ($tool_id==0) {
			$tool_id=Main::getToolId();
		}
		if ((!isset(self::$permissiontext[$tool_id]))||($force===true)) {
			self::$permissiontext[$tool_id]=[];
			$Qselect=self::getConnection();
			$Qselect->prepare('SELECT * FROM :table_vis2_permission: WHERE tool_id=:tool_id: AND permission_ispublic=:permission_ispublic:');
			$Qselect->bindTable(':table_vis2_permission:', 'vis2_permission');
			$Qselect->bindInt(':tool_id:', $tool_id);
			$Qselect->bindInt(':permission_ispublic:', 1);
			foreach ($Qselect->query() as $permission) {
				self::$permissiontext[$tool_id][$permission['permission_flag']]=$permission['permission_title'];
			}
		}

		return self::$permissiontext[$tool_id];
	}

	/**
	 * Gibt einen Recht-Text eines Tools zurück anhand der Tool-Id
	 *
	 * @param string $permission
	 * @param int $tool_id
	 * @param bool $force
	 * @return string
	 */
	public static function getPermissionText(string $permission, int $tool_id=0, bool $force=false):string {
		if ($tool_id==0) {
			$tool_id=Main::getToolId();
		}
		if ((!isset(self::$permissiontext[$tool_id]))||($force===true)) {
			self::getPermissionTextList($tool_id);
		}
		if (isset(self::$permissiontext[$tool_id][$permission])) {
			return self::$permissiontext[$tool_id][$permission];
		}

		return '-';
	}

	/**
	 * @param int $group_id
	 * @return array
	 */
	public static function loadGroupPermission(int $group_id):array {
		$ar_group_permission=[];
		$QloadData=self::getConnection();
		$QloadData->prepare('SELECT * FROM :table_vis2_group_permission: WHERE group_id=:group_id:');
		$QloadData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
		$QloadData->bindInt(':group_id:', $group_id);
		foreach ($QloadData->query() as $group_permission) {
			$ar_group_permission[$group_permission['permission_page']][$group_permission['permission_flag']]=1;
		}

		return $ar_group_permission;
	}

	/**
	 * @param int $group_id
	 * @param int $tool_id
	 * @return array
	 */
	public static function loadUserGroupByGroupId(int $group_id, int $tool_id):array {
		$ar_tool_user=[];
		$QloadData=self::getConnection();
		$QloadData->prepare('SELECT * FROM :table_vis2_user_group: WHERE group_id=:group_id: AND tool_id=:tool_id:');
		$QloadData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QloadData->bindInt(':group_id:', $group_id);
		$QloadData->bindInt(':tool_id:', $tool_id);
		foreach ($QloadData->query() as $user_group) {
			$ar_tool_user[$user_group['user_id']]=1;
		}

		return $ar_tool_user;
	}

	/**
	 * @param int $user_id
	 * @param int $tool_id
	 * @return array
	 */
	public static function loadUserGroupByUserId(int $user_id, int $tool_id):array {
		$ar_tool_group=[];
		$QloadData=self::getConnection();
		if ($tool_id==0) {
			$QloadData->prepare('SELECT * FROM :table_vis2_user_group: WHERE user_id=:user_id:');
		} else {
			$QloadData->prepare('SELECT * FROM :table_vis2_user_group: WHERE user_id=:user_id: AND tool_id=:tool_id:');
		}
		$QloadData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QloadData->bindInt(':user_id:', $user_id);
		$QloadData->bindInt(':tool_id:', $tool_id);
		foreach ($QloadData->query() as $user_group) {
			$ar_tool_group[$user_group['tool_id']][$user_group['group_id']]=1;
		}

		return $ar_tool_group;
	}

	/**
	 * @param int $group_id
	 * @param int $tool_id
	 * @return array
	 */
	public static function loadUserMandantenByMandantenId(int $mandant_id, int $tool_id):array {
		$ar_tool_mandant=[];
		$QloadData=self::getConnection();
		$QloadData->prepare('SELECT * FROM :table_vis2_user_mandant: WHERE mandant_id=:mandant_id: AND tool_id=:tool_id:');
		$QloadData->bindTable(':table_vis2_user_mandant:', 'vis2_user_mandant');
		$QloadData->bindInt(':mandant_id:', $mandant_id);
		$QloadData->bindInt(':tool_id:', $tool_id);
		foreach ($QloadData->query() as $user_mandant) {
			$ar_tool_mandant[$user_mandant['user_id']]=1;
		}

		return $ar_tool_mandant;
	}

	/**
	 * @param int $user_id
	 * @param int $tool_id
	 * @return array
	 */
	public static function loadUserMandantenByUserId(int $user_id, int $tool_id):array {
		$ar_tool_mandant=[];
		$QloadData=self::getConnection();
		if ($tool_id==0) {
			$QloadData->prepare('SELECT * FROM :table_vis2_user_mandant: WHERE user_id=:user_id:');
		} else {
			$QloadData->prepare('SELECT * FROM :table_vis2_user_mandant: WHERE user_id=:user_id: AND tool_id=:tool_id:');
		}
		$QloadData->bindTable(':table_vis2_user_mandant:', 'vis2_user_mandant');
		$QloadData->bindInt(':user_id:', $user_id);
		$QloadData->bindInt(':tool_id:', $tool_id);
		foreach ($QloadData->query() as $user_mandant) {
			$ar_tool_mandant[$user_mandant['tool_id']][$user_mandant['mandant_id']]=1;
		}

		return $ar_tool_mandant;
	}

	/**
	 * @param int $page_id
	 * @param int $tool_id
	 * @return array
	 */
	public static function loadPagePermission(int $page_id, int $tool_id):array {
		$ar_page_permission=[];
		$QloadData=self::getConnection();
		$QloadData->prepare('SELECT * FROM :table_vis2_page_permission: WHERE page_id=:page_id: AND tool_id=:tool_id:');
		$QloadData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
		$QloadData->bindInt(':page_id:', $page_id);
		$QloadData->bindInt(':tool_id:', $tool_id);
		foreach ($QloadData->query() as $page_permission) {
			$ar_page_permission[$page_permission['permission_flag']]=1;
		}

		return $ar_page_permission;
	}

	/**
	 * @param int $page_id
	 * @param int $tool_id
	 * @param string $permission_flag
	 * @param int $vis_time
	 * @param int $vis_user_id
	 * @return bool
	 */
	public static function addPagePermission(int $page_id, int $tool_id, string $permission_flag, int $vis_time, int $vis_user_id):bool {
		$QinsertData=self::getConnection();
		$QinsertData->prepare('INSERT INTO :table_vis2_page_permission: (page_id, tool_id, permission_flag, page_permission_create_time, page_permission_create_user_id, page_permission_update_time, page_permission_update_user_id) VALUES (:page_id:, :tool_id:, :permission_flag:, :page_permission_create_time:, :page_permission_create_user_id:, :page_permission_update_time:, :page_permission_update_user_id:)');
		$QinsertData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
		$QinsertData->bindInt(':page_id:', $page_id);
		$QinsertData->bindInt(':tool_id:', $tool_id);
		$QinsertData->bindString(':permission_flag:', $permission_flag);
		$QinsertData->bindInt(':page_permission_create_time:', $vis_time);
		$QinsertData->bindInt(':page_permission_create_user_id:', $vis_user_id);
		$QinsertData->bindInt(':page_permission_update_time:', $vis_time);
		$QinsertData->bindInt(':page_permission_update_user_id:', $vis_user_id);
		$QinsertData->execute();

		return true;
	}

	/**
	 * @param int $page_id
	 * @param int $tool_id
	 * @param string $permission_flag
	 * @return bool
	 */
	public static function delPagePermission(int $page_id, int $tool_id, string $permission_flag):bool {
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_page_permission: WHERE page_id=:page_id: AND tool_id=:tool_id: AND permission_flag=:permission_flag:');
		$QdeleteData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
		$QdeleteData->bindInt(':page_id:', $page_id);
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->bindString(':permission_flag:', $permission_flag);
		$QdeleteData->execute();

		return true;
	}

	/**
	 * @param string $page_id
	 * @return bool
	 */
	public static function delPageById(string $page_id, int $tool_id):bool {
		/**
		 * vis2_navigation
		 */
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_navigation: WHERE page_id=:page_id: AND tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_navigation:', 'vis2_navigation');
		$QdeleteData->bindInt(':page_id:', $page_id);
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		/**
		 * vis2_page
		 */
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_page: WHERE page_id=:page_id:');
		$QdeleteData->bindTable(':table_vis2_page:', 'vis2_page');
		$QdeleteData->bindInt(':page_id:', $page_id);
		$QdeleteData->execute();

		/**
		 * vis2_page_permission
		 */
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_page_permission: WHERE page_id=:page_id:');
		$QdeleteData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
		$QdeleteData->bindInt(':page_id:', $page_id);
		$QdeleteData->execute();

		return true;
	}

	/**
	 * @param string $permission_flag
	 * @param int $tool_id
	 * @return bool
	 */
	public static function delPermissionFlagByToolId(string $permission_flag, int $tool_id):bool {
		$QgetData=self::getConnection();
		$QgetData->prepare('SELECT group_id FROM :table_vis2_group: WHERE tool_id=:tool_id:');
		$QgetData->bindTable(':table_vis2_group:', 'vis2_group');
		$QgetData->bindInt(':tool_id:', $tool_id);
		foreach ($QgetData->query() as $group) {
			/**
			 * vis2_group_permission
			 */
			$QdeleteData=self::getConnection();
			$QdeleteData->prepare('DELETE FROM :table_vis2_group_permission: WHERE permission_flag=:permission_flag: AND group_id=:group_id:');
			$QdeleteData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
			$QdeleteData->bindString(':permission_flag:', $permission_flag);
			$QdeleteData->bindInt(':group_id:', $group['group_id']);
			$QdeleteData->execute();
		}

		/**
		 * vis2_page_permission
		 */
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_page_permission: WHERE permission_flag=:permission_flag: AND tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
		$QdeleteData->bindString(':permission_flag:', $permission_flag);
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		/**
		 * vis2_permission
		 */
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_permission: WHERE permission_flag=:permission_flag: AND tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_permission:', 'vis2_permission');
		$QdeleteData->bindString(':permission_flag:', $permission_flag);
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		return true;
	}

	/**
	 * @param int $navigation_id
	 * @return bool
	 */
	public static function delNavigationById(int $navigation_id):bool {
		// ToDo Rekursiv alles löschen inkl. Verknüpfungen
		return true;
	}

	public static function delGroupById(int $group_id):bool {
		// vis2_group_permission
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_group_permission: WHERE group_id=:group_id:');
		$QdeleteData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
		$QdeleteData->bindInt(':group_id:', $group_id);
		$QdeleteData->execute();

		// vis2_user_group
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_group: WHERE group_id=:group_id:');
		$QdeleteData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QdeleteData->bindInt(':group_id:', $group_id);
		$QdeleteData->execute();

		return true;
	}

	/**
	 * @param int $parent_id
	 * @param int $max_level
	 * @param int $tool_id
	 * @return array|null
	 */
	public static function getNavigationReal(int $parent_id=0, int $max_level=0, int $tool_id=0):?array {
		if ($tool_id==0) {
			$tool_id=Main::getToolId();
		}

		if (!isset(self::$navigation_real[$tool_id])) {
			self::loadNavigation($tool_id);
		}

		if (!isset(self::$navigation_real[$tool_id])) {
			self::$navigation_real[$tool_id]=[];
		}
		if (!isset(self::$navigation_real[$tool_id][$parent_id.'_'.$max_level])) {
			self::$navigation_real[$tool_id][$parent_id.'_'.$max_level]=self::createNavigationRealRecursive($parent_id, 0, $max_level, $tool_id);
		}

		return self::$navigation_real[$tool_id][$parent_id.'_'.$max_level];
	}

	/**
	 * @param int $tool_id
	 * @return array
	 */
	public static function getNavigationRealUnsorted(int $tool_id=0):array {
		if ($tool_id==0) {
			$tool_id=Main::getToolId();
		}

		if (!isset(self::$navigation_real[$tool_id])) {
			self::loadNavigation($tool_id);
		}

		return self::$navigation_unsorted[$tool_id];
	}

	/**
	 * @param int $tool_id
	 * @return bool
	 */
	public static function loadNavigation(int $tool_id=0):bool {
		if ($tool_id==0) {
			$tool_id=Main::getToolId();
		}

		self::$navigation_tree[$tool_id]=[];
		self::$navigation_unsorted[$tool_id]=[];
		self::$navigation_name2id[$tool_id]=[];

		$Qselect=self::getConnection();
		$Qselect->prepare('SELECT *, n.tool_id, n.page_id FROM :table_vis2_navigation: AS n LEFT JOIN :table_vis2_page: AS p on (p.tool_id=n.tool_id AND p.page_id=n.page_id) WHERE n.tool_id=:tool_id: ORDER BY n.navigation_parent_id ASC, n.navigation_sortorder ASC, n.navigation_title ASC');
		$Qselect->bindTable(':table_vis2_navigation:', 'vis2_navigation');
		$Qselect->bindTable(':table_vis2_page:', 'vis2_page');
		$Qselect->bindInt(':tool_id:', $tool_id);
		foreach ($Qselect->query() as $navigation_element) {
			$navigation_element['navigation_sortorder']=intval($navigation_element['navigation_sortorder']);
			$navigation_element['custom']=false;
			$navigation_element['permission_link']=false;
			$navigation_element['permission_view']=false;
			self::$navigation_tree[$tool_id][$navigation_element['navigation_parent_id']][$navigation_element['navigation_id']]=$navigation_element;
			self::$navigation_unsorted[$tool_id][$navigation_element['navigation_id']]=$navigation_element;
			self::$navigation_name2id[$tool_id][$navigation_element['page_name_intern']]=$navigation_element['navigation_id'];
		}

		$QselectPagePermissions=self::getConnection();
		$QselectPagePermissions->prepare('SELECT * FROM :table_vis2_page_permission: WHERE tool_id=:tool_id:');
		$QselectPagePermissions->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
		$QselectPagePermissions->bindInt(':tool_id:', $tool_id);
		$ar_permission=[];
		foreach ($QselectPagePermissions->query() as $pagepermission) {
			if (!isset($ar_permission[$pagepermission['page_id']])) {
				$ar_permission[$pagepermission['page_id']]=[];
			}
			$ar_permission[$pagepermission['page_id']][]=$pagepermission['permission_flag'];
		}

		foreach (self::$navigation_tree[$tool_id] as $parent_id=>$members) {
			foreach ($members as $member_id=>$member) {
				if (isset($ar_permission[$member['page_id']])) {
					self::$navigation_tree[$tool_id][$parent_id][$member_id]['permission']=$ar_permission[$member['page_id']];
					self::$navigation_unsorted[$tool_id][$member_id]['permission']=$ar_permission[$member['page_id']];
				} else {
					self::$navigation_tree[$tool_id][$parent_id][$member_id]['permission']=[];
					self::$navigation_unsorted[$tool_id][$member_id]['permission']=[];
				}
			}
		}

		return true;
	}

	/**
	 * @param int $parent_id
	 * @param int $level
	 * @param int $max_level
	 * @param int $tool_id
	 * @return array|null
	 */
	private static function createNavigationRealRecursive(int $parent_id, int $level, int $max_level, int $tool_id):?array {
		$data=[];
		if (isset(self::$navigation_tree[$tool_id][$parent_id])) {
			foreach (self::$navigation_tree[$tool_id][$parent_id] as $category_id=>$category) {
				$_data=[];
				$category['navigation_level']=$level;
				self::$navigation_data[$tool_id][$category_id]=$category;

				$category['navigation_active']=false;
				if (isset(self::$navigation_tree[$tool_id][$category_id])&&(($max_level==0)||($max_level>$level+1))) {
					self::$navigation_data[$tool_id][$category_id]['navigation_subcats']=true;
					$category['navigation_subcats']=true;
					$_data['info']=$category;
					$_data['links']=self::createNavigationRealRecursive($category_id, $level+1, $max_level, $tool_id);
					$data[]=$_data;
				} else {
					self::$navigation_data[$tool_id][$category_id]['navigation_subcats']=false;
					$category['navigation_subcats']=false;
					$_data['info']=$category;
					$_data['links']=[];
					$data[]=$_data;
				}
			}

		}

		return $data;
	}

	public static function loadUserTool(int $user_id, int $tool_id):array {
		$ar_user_tool=[];
		$QloadData=self::getConnection();
		if ($tool_id==0) {
			$QloadData->prepare('SELECT * FROM :table_vis2_user_tool: WHERE user_id=:user_id:');
		} else {
			$QloadData->prepare('SELECT * FROM :table_vis2_user_tool: WHERE user_id=:user_id: AND tool_id=:tool_id:');
		}
		$QloadData->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QloadData->bindInt(':user_id:', $user_id);
		$QloadData->bindInt(':tool_id:', $tool_id);
		foreach ($QloadData->query() as $tool) {
			$ar_user_tool[$tool['tool_id']]=1;
		}

		return $ar_user_tool;
	}

	/**
	 * Erstellt eine Verknüpfung zwischen einem Benutzer und einem Tool
	 *
	 * @param int $user_id
	 * @param int $tool_id
	 * @param int $create_time
	 * @param int $create_user_id
	 * @return bool
	 */
	public static function addUserTool(int $user_id, int $tool_id, int $create_time, int $create_user_id):bool {
		$QinsertData=self::getConnection();
		$QinsertData->prepare('INSERT INTO :table_vis2_user_tool: (user_id, tool_id, user_tool_create_time, user_tool_create_user_id, user_tool_update_time, user_tool_update_user_id) VALUES (:user_id:, :tool_id:, :user_tool_create_time:, :user_tool_create_user_id:, :user_tool_update_time:, :user_tool_update_user_id:)');
		$QinsertData->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QinsertData->bindInt(':user_id:', $user_id);
		$QinsertData->bindInt(':tool_id:', $tool_id);
		$QinsertData->bindInt(':user_tool_create_time:', $create_time);
		$QinsertData->bindInt(':user_tool_create_user_id:', $create_user_id);
		$QinsertData->bindInt(':user_tool_update_time:', $create_time);
		$QinsertData->bindInt(':user_tool_update_user_id:', $create_user_id);
		$QinsertData->execute();

		return true;
	}

	/**
	 * Löscht eine Verknüpfung zwischen einem Benutzer und einem Tool
	 *
	 * @param int $user_id
	 * @param int $tool_id
	 * @return bool
	 */
	public static function delUserTool(int $user_id, int $tool_id):bool {
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_tool: WHERE user_id=:user_id: AND tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QdeleteData->bindInt(':user_id:', $user_id);
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		return true;
	}

	/**
	 * Erstellt eine Verknüpfung zwischen einem Benutzer und einer Gruppe und einem Tool
	 *
	 * @param int $user_id
	 * @param int $group_id
	 * @param int $tool_id
	 * @param int $create_time
	 * @param int $create_user_id
	 * @return bool
	 */
	public static function addUserGroup(int $user_id, int $group_id, int $tool_id, int $create_time, int $create_user_id):bool {
		$QinsertData=self::getConnection();
		$QinsertData->prepare('INSERT INTO :table_vis2_user_group: (group_id, tool_id, user_id, user_group_create_time, user_group_create_user_id, user_group_update_time, user_group_update_user_id) VALUES (:group_id:, :tool_id:, :user_id:, :user_group_create_time:, :user_group_create_user_id:, :user_group_update_time:, :user_group_update_user_id:)');
		$QinsertData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QinsertData->bindInt(':user_id:', $user_id);
		$QinsertData->bindInt(':group_id:', $group_id);
		$QinsertData->bindInt(':tool_id:', $tool_id);
		$QinsertData->bindInt(':user_group_create_time:', $create_time);
		$QinsertData->bindInt(':user_group_create_user_id:', $create_user_id);
		$QinsertData->bindInt(':user_group_update_time:', $create_time);
		$QinsertData->bindInt(':user_group_update_user_id:', $create_user_id);
		$QinsertData->execute();

		return true;
	}

	/**
	 * Löscht eine Verknüpfung zwischen einem Benutzer und einer Gruppe und einem Tool
	 *
	 * @param int $user_id
	 * @param int $group_id
	 * @param int $tool_id
	 * @return bool
	 */
	public static function delUserGroup(int $user_id, int $group_id, int $tool_id):bool {
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_group: WHERE user_id=:user_id: AND tool_id=:tool_id: AND group_id=:group_id:');
		$QdeleteData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QdeleteData->bindInt(':user_id:', $user_id);
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->bindInt(':group_id:', $group_id);
		$QdeleteData->execute();

		return true;
	}

	/**
	 * Erstellt eine Verknüpfung zwischen einem Benutzer und einem Mandanten und einem Tool
	 *
	 * @param int $user_id
	 * @param int $mandant_id
	 * @param int $tool_id
	 * @param int $create_time
	 * @param int $create_user_id
	 * @return bool
	 */
	public static function addUserMandant(int $user_id, int $mandant_id, int $tool_id, int $create_time, int $create_user_id):bool {
		$QinsertData=self::getConnection();
		$QinsertData->prepare('INSERT INTO :table_vis2_user_mandant: (mandant_id, tool_id, user_id, user_mandant_create_time, user_mandant_create_user_id, user_mandant_update_time, user_mandant_update_user_id) VALUES (:mandant_id:, :tool_id:, :user_id:, :user_mandant_create_time:, :user_mandant_create_user_id:, :user_mandant_update_time:, :user_mandant_update_user_id:)');
		$QinsertData->bindTable(':table_vis2_user_mandant:', 'vis2_user_mandant');
		$QinsertData->bindInt(':user_id:', $user_id);
		$QinsertData->bindInt(':mandant_id:', $mandant_id);
		$QinsertData->bindInt(':tool_id:', $tool_id);
		$QinsertData->bindInt(':user_mandant_create_time:', $create_time);
		$QinsertData->bindInt(':user_mandant_create_user_id:', $create_user_id);
		$QinsertData->bindInt(':user_mandant_update_time:', $create_time);
		$QinsertData->bindInt(':user_mandant_update_user_id:', $create_user_id);
		$QinsertData->execute();

		return true;
	}

	/**
	 * Löscht eine Verknüpfung zwischen einem Benutzer und einem Mandanten und einem Tool
	 *
	 * @param int $user_id
	 * @param int $mandant_id
	 * @param int $tool_id
	 * @return bool
	 */
	public static function delUserMandant(int $user_id, int $mandant_id, int $tool_id):bool {
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_mandant: WHERE user_id=:user_id: AND tool_id=:tool_id: AND mandant_id=:mandant_id:');
		$QdeleteData->bindTable(':table_vis2_user_mandant:', 'vis2_user_mandant');
		$QdeleteData->bindInt(':user_id:', $user_id);
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->bindInt(':mandant_id:', $mandant_id);
		$QdeleteData->execute();

		return true;
	}

	/**
	 * Erstellt eine Verknüpfung zwischen einer Gruppe und einer Seite und ihren Rechten
	 *
	 * @param int $group_id
	 * @param string $permission_page
	 * @param string $permission_flag
	 * @param int $create_time
	 * @param int $create_user_id
	 * @return bool
	 */
	public static function addGroupPermission(int $group_id, string $permission_page, string $permission_flag, int $create_time, int $create_user_id):bool {
		$QinsertData=self::getConnection();
		$QinsertData->prepare('INSERT INTO :table_vis2_group_permission: (group_id, permission_page, permission_flag, group_permission_create_time, group_permission_create_user_id, group_permission_update_time, group_permission_update_user_id) VALUES (:group_id:, :permission_page:, :permission_flag:, :group_permission_create_time:, :group_permission_create_user_id:, :group_permission_update_time:, :group_permission_update_user_id:)');
		$QinsertData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
		$QinsertData->bindInt(':group_id:', $group_id);
		$QinsertData->bindString(':permission_page:', $permission_page);
		$QinsertData->bindString(':permission_flag:', $permission_flag);
		$QinsertData->bindInt(':group_permission_create_time:', $create_time);
		$QinsertData->bindInt(':group_permission_create_user_id:', $create_user_id);
		$QinsertData->bindInt(':group_permission_update_time:', $create_time);
		$QinsertData->bindInt(':group_permission_update_user_id:', $create_user_id);
		$QinsertData->execute();

		return true;
	}

	/**
	 * Löscht eine Verknüpfung zwischen einer Gruppe und einer Seite und ihren Rechten
	 *
	 * @param int $group_id
	 * @param string $permission_page
	 * @param string $permission_flag
	 * @return bool
	 */
	public static function delGroupPermission(int $group_id, string $permission_page, string $permission_flag):bool {
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page: AND permission_flag=:permission_flag:');
		$QdeleteData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
		$QdeleteData->bindInt(':group_id:', $group_id);
		$QdeleteData->bindString(':permission_page:', $permission_page);
		$QdeleteData->bindString(':permission_flag:', $permission_flag);
		$QdeleteData->execute();

		return true;
	}

	public static function delUserById(int $user_id):bool {
		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_protect: WHERE user_id=:user_id:');
		$QdeleteData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QdeleteData->bindInt(':user_id:', $user_id);
		$QdeleteData->execute();

		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_user: WHERE user_id=:user_id:');
		$QdeleteData->bindTable(':table_vis2_user:', 'vis2_user');
		$QdeleteData->bindInt(':user_id:', $user_id);
		$QdeleteData->execute();

		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_group: WHERE user_id=:user_id:');
		$QdeleteData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QdeleteData->bindInt(':user_id:', $user_id);
		$QdeleteData->execute();

		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_mandant: WHERE user_id=:user_id:');
		$QdeleteData->bindTable(':table_vis2_user_mandant:', 'vis2_user_mandant');
		$QdeleteData->bindInt(':user_id:', $user_id);
		$QdeleteData->execute();

		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_pref: WHERE user_id=:user_id:');
		$QdeleteData->bindTable(':table_vis2_user_pref:', 'vis2_user_pref');
		$QdeleteData->bindInt(':user_id:', $user_id);
		$QdeleteData->execute();

		$QdeleteData=self::getConnection();
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_tool: WHERE user_id=:user_id:');
		$QdeleteData->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QdeleteData->bindInt(':user_id:', $user_id);
		$QdeleteData->execute();

		return true;
	}

	public static function delTool(int $tool_id) {
		$QgetData=self::getConnection();
		$QgetData->prepare('SELECT group_id FROM :table_vis2_group: WHERE tool_id=:tool_id:');
		$QgetData->bindTable(':table_vis2_group:', 'vis2_group');
		$QgetData->bindInt(':tool_id:', $tool_id);
		foreach ($QgetData->query() as $group) {
			// vis2_group_permission
			$QdeleteData=self::getConnection();
			$QdeleteData->prepare('DELETE FROM :table_vis2_group_permission: WHERE group_id=:group_id:');
			$QdeleteData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
			$QdeleteData->bindInt(':group_id:', $group->getInt['group_id']);
			$QdeleteData->execute();

			// vis2_user_group
			$QdeleteData->prepare('DELETE FROM :table_vis2_user_group: WHERE group_id=:group_id:');
			$QdeleteData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
			$QdeleteData->bindInt(':group_id:', $group->getInt['group_id']);
			$QdeleteData->execute();
		}

		// vis2_group
		$QdeleteData->prepare('DELETE FROM :table_vis2_group: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_group:', 'vis2_group');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		// vis2_mandant
		$QdeleteData->prepare('DELETE FROM :table_vis2_mandant: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_mandant:', 'vis2_mandant');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		// vis2_navigation
		$QdeleteData->prepare('DELETE FROM :table_vis2_navigation: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_navigation:', 'vis2_navigation');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		// vis2_page
		$QdeleteData->prepare('DELETE FROM :table_vis2_page: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_page:', 'vis2_page');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		// vis2_page_permission
		$QdeleteData->prepare('DELETE FROM :table_vis2_page_permission: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		// vis2_permission
		$QdeleteData->prepare('DELETE FROM :table_vis2_permission: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_permission:', 'vis2_permission');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		// vis2_protect
		$QdeleteData->prepare('DELETE FROM :table_vis2_protect: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		// vis2_tool
		$QdeleteData->prepare('DELETE FROM :table_vis2_tool: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		// vis2_user_group
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_group: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();

		// vis2_user_tool
		$QdeleteData->prepare('DELETE FROM :table_vis2_user_tool: WHERE tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QdeleteData->bindInt(':tool_id:', $tool_id);
		$QdeleteData->execute();
	}

}

?>