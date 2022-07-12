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

namespace VIS\Core;

use osWFrame\Core as osWFrame;

class Permission {

	use osWFrame\BaseStaticTrait;
	use osWFrame\BaseConnectionTrait;
	use BaseUserTrait;
	use BaseToolTrait;

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
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var array|null
	 */
	protected ?array $permission=null;

	/**
	 * Permission constructor.
	 */
	public function __construct(int $tool_id=0, int $user_id=0) {
		if ($tool_id>0) {
			$this->setToolId($tool_id);
		}
		if ($user_id>0) {
			$this->setUserId($user_id);
		}
	}

	/**
	 * @return bool
	 */
	public function isLoaded():bool {
		if ($this->permission===null) {

			return false;
		}

		return true;
	}

	/**
	 * @param string $page
	 * @param string $flag
	 * @return bool
	 */
	public function checkPermission(string $page, string $flag):bool {
		if ($this->isLoaded()!==true) {
			$this->loadPermission();
		}
		if ((isset($this->permission[$page]))&&(isset($this->permission[$page][$flag]))&&($this->permission[$page][$flag]===true)) {
			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getPermission():array {
		if ($this->isLoaded()!==true) {
			$this->loadPermission();
		}

		return $this->permission;
	}

	/**
	 * @return $this
	 */
	public function loadPermission():self {
		$this->permission=[];
		$VIS_Groups=new Group($this->getToolId(), $this->getUserId());

		foreach ($VIS_Groups->getGroups() as $group_id=>$group_details) {
			$QloadGroupPermission=self::getConnection(osWFrame\Settings::getStringVar('vis_database_alias'));
			$QloadGroupPermission->prepare('SELECT * FROM :table_vis_group_permission: AS gp INNER JOIN :table_vis_page: AS p ON (p.page_name_intern=gp.permission_page) INNER JOIN :table_vis_page_permission: AS pp ON (pp.permission_flag=gp.permission_flag AND pp.page_id=p.page_id) INNER JOIN :table_vis_permission: AS pe ON (pe.permission_flag=pp.permission_flag) WHERE pe.permission_ispublic=:permission_ispublic: AND gp.group_id=:group_id:');
			$QloadGroupPermission->bindTable(':table_vis_group_permission:', 'vis_group_permission');
			$QloadGroupPermission->bindTable(':table_vis_page:', 'vis_page');
			$QloadGroupPermission->bindTable(':table_vis_page_permission:', 'vis_page_permission');
			$QloadGroupPermission->bindTable(':table_vis_permission:', 'vis_permission');
			$QloadGroupPermission->bindInt(':permission_ispublic:', 1);
			$QloadGroupPermission->bindInt(':group_id:', $group_id);
			foreach ($QloadGroupPermission->query() as $grouppermission) {
				$this->permission[$grouppermission['permission_page']][$grouppermission['permission_flag']]=true;
			}
		}

		$this->permission['vis_api']['view']=true;
		$this->permission['vis_dashboard']['view']=true;
		$this->permission['vis_dashboard']['link']=true;
		$this->permission['vis_profile']['view']=true;
		$this->permission['vis_profile']['link']=true;
		$this->permission['vis_settings']['view']=true;
		$this->permission['vis_settings']['link']=true;
		$this->permission['vis_logout']['view']=true;
		$this->permission['vis_logout']['link']=true;

		return $this;
	}

	/**
	 * @param string $permission_page
	 * @param string $permission_flag
	 * @param bool $status
	 * @return $this
	 */
	public function addPermission(string $permission_page, string $permission_flag, bool $status=true):self {
		if ($this->isLoaded()!==true) {
			$this->loadPermission();
		}

		$this->permission[$permission_page][$permission_flag]=$status;

		return $this;
	}

}

?>