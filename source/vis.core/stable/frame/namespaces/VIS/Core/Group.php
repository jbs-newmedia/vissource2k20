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

class Group {

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
	protected ?array $groups=null;

	/**
	 * Group constructor.
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
		if ($this->groups===null) {

			return false;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getGroups():array {
		if ($this->isLoaded()!==true) {
			$this->loadGroups();
		}

		return $this->groups;
	}

	/**
	 * @return $this
	 */
	public function loadGroups():self {
		$this->groups=[];

		$QloadGroupData=self::getConnection();
		$QloadGroupData->prepare('SELECT * FROM :table_vis_user_group: AS ug INNER JOIN :table_vis_group: AS g ON (g.group_id=ug.group_id) WHERE g.group_ispublic=:group_ispublic: AND ug.tool_id=:tool_id: AND ug.user_id=:user_id:');
		$QloadGroupData->bindTable(':table_vis_user_group:', 'vis_user_group');
		$QloadGroupData->bindTable(':table_vis_group:', 'vis_group');
		$QloadGroupData->bindInt(':group_ispublic:', 1);
		$QloadGroupData->bindInt(':user_id:', $this->getUserId());
		$QloadGroupData->bindInt(':tool_id:', $this->getToolId());
		foreach ($QloadGroupData->query() as $group) {
			$this->groups[$group['group_id']]=['group_id'=>$group['group_id'], 'group_name_intern'=>$group['group_name_intern'], 'group_name'=>$group['group_name']];
		}

		return $this;
	}

}

?>