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

namespace VIS2\Core;

use osWFrame\Core\BaseConnectionTrait;
use osWFrame\Core\BaseStaticTrait;
use osWFrame\Core\BaseVarTrait;
use osWFrame\Core\Filter;
use osWFrame\Core\Session;
use osWFrame\Core\Settings;

class User {

	use BaseStaticTrait;
	use BaseConnectionTrait;
	use BaseVarTrait;
	use BaseToolTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=3;

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
	 * @var bool
	 */
	protected bool $logged_in=false;

	/**
	 * @var array|null
	 */
	protected ?array $tools=null;

	/**
	 * @var array|null
	 */
	protected ?array $tools_select=null;

	/**
	 * @var array|null
	 */
	protected ?array $groups=null;

	/**
	 * @var array|null
	 */
	protected ?array $groups_select=null;

	/**
	 * @var array|null
	 */
	protected ?array $mandanten=null;

	/**
	 * @var array|null
	 */
	protected ?array $mandanten_select=null;

	/**
	 * User constructor.
	 */
	public function __construct() {

	}

	/**
	 *
	 * @return bool
	 */
	public function isLoggedIn():bool {
		if ($this->logged_in===true) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @param bool $logged_in
	 * @return bool
	 */
	public function setLoggedIn(bool $logged_in):bool {
		$this->logged_in=$logged_in;

		return true;
	}

	/**
	 *
	 * @param string $email
	 * @return bool
	 */
	public static function validateEmail(string $email):bool {
		return Filter::verifyEmailIDNAPattern($email);
	}

	/**
	 *
	 * @param string $pwblank
	 * @param string $pwcrypted
	 * @return bool
	 */
	public static function validatePassword(string $pwblank, string $pwcrypted):bool {
		if (($pwblank!='')&&($pwcrypted!='')) {
			$stack=explode(':', $pwcrypted);
			if (sizeof($stack)!=2) {
				return false;
			}
			if (hash('sha512', $stack[1].$pwblank)==$stack[0]) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function createLogin():bool {
		if ($this->getId()!==null) {
			$user_token=md5($this->getEMail().microtime().uniqid(microtime()));

			$QupdateData=self::getConnection();
			$QupdateData->prepare('UPDATE :table_vis2_user: SET user_token=:user_token: WHERE user_id=:user_id:');
			$QupdateData->bindTable(':table_vis2_user:', 'vis2_user');
			$QupdateData->bindString(':user_token:', $user_token);
			$QupdateData->bindInt(':user_id:', $this->getId());
			$QupdateData->execute();

			$this->setLoginSessionToken($user_token);

			return true;
		}

		return false;
	}

	/**
	 * @return $this
	 */
	public function setLoginSessionToken(string $user_token):self {
		Session::setStringVar(Settings::getStringVar('vis2_path').'_user_token', $user_token);

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isLoginSessionToken():bool {
		if (Session::getStringVar(Settings::getStringVar('vis2_path').'_user_token')!==null) {
			return true;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function getLoginSessionToken():string {
		if ($this->isLoginSessionToken()===true) {
			return Session::getStringVar(Settings::getStringVar('vis2_path').'_user_token');
		}

		return '';
	}

	/**
	 * @param string $user_token
	 * @return bool
	 */
	public function doLoginByToken(string $user_token=''):bool {
		if ($user_token=='') {
			$user_token=$this->getLoginSessionToken();
		}

		if (strlen($user_token)==32) {
			if ($this->loadUserDetailsByToken($user_token)===true) {
				self::setLoggedIn(true);

				return true;
			} else {
				$this->clearVars();
				self::setLoggedIn(false);

				return false;
			}
		} else {
			$this->clearVars();
			self::setLoggedIn(false);

			return false;
		}
	}

	/**
	 * @return bool
	 */
	public function doLogout():bool {
		if ($this->getId()!==null) {
			$user_token='';

			$QupdateData=self::getConnection();
			$QupdateData->prepare('UPDATE :table_vis2_user: SET user_token=:user_token: WHERE user_id=:user_id:');
			$QupdateData->bindTable(':table_vis2_user:', 'vis2_user');
			$QupdateData->bindString(':user_token:', $user_token);
			$QupdateData->bindInt(':user_id:', $this->getId());
			$QupdateData->execute();

			$this->setLoginSessionToken('');

			return true;
		}

		return false;
	}

	/**
	 * @param int $user_id
	 * @return bool
	 */
	public function loadUserDetailsById(int $user_id):bool {
		$this->clearVars();

		$QselectUserDetails=self::getConnection();
		$QselectUserDetails->prepare('SELECT * FROM :table_vis2_user: WHERE user_id=:user_id:');
		$QselectUserDetails->bindTable(':table_vis2_user:', 'vis2_user');
		$QselectUserDetails->bindInt(':user_id:', $user_id);
		if ($QselectUserDetails->exec()==1) {
			$this->vars=$QselectUserDetails->fetch();

			return true;
		}

		return false;
	}

	/**
	 * @param string $email
	 * @return bool
	 */
	public function loadUserDetailsByEMail(string $user_email):bool {
		$this->clearVars();

		$QselectUserDetails=self::getConnection();
		$QselectUserDetails->prepare('SELECT * FROM :table_vis2_user: WHERE user_email=:user_email:');
		$QselectUserDetails->bindTable(':table_vis2_user:', 'vis2_user');
		$QselectUserDetails->bindString(':user_email:', $user_email);
		if ($QselectUserDetails->exec()==1) {
			$this->vars=$QselectUserDetails->fetch();

			return true;
		}

		return false;
	}

	/**
	 * @param string $user_token
	 * @return bool
	 */
	public function loadUserDetailsByToken(string $user_token):bool {
		$this->clearVars();

		$QselectUserDetails=self::getConnection();
		$QselectUserDetails->prepare('SELECT * FROM :table_vis2_user: WHERE user_token=:user_token:');
		$QselectUserDetails->bindTable(':table_vis2_user:', 'vis2_user');
		$QselectUserDetails->bindString(':user_token:', $user_token);
		if ($QselectUserDetails->exec()==1) {
			$this->vars=$QselectUserDetails->fetch();

			return true;
		}

		return false;
	}

	/**
	 * @return array|null
	 */
	public function getUserDetails():?array {
		return $this->vars;
	}

	/**
	 * @return $this
	 */
	public function loadTools():self {
		$this->tools=[];
		$this->tools[Settings::getStringVar('vis2_login_module')]=['tool_id'=>0, 'tool_name'=>'Anmelden', 'tool_name_intern'=>Settings::getStringVar('vis2_login_module')];
		$this->tools[Settings::getStringVar('vis2_chtool_module')]=['tool_id'=>0, 'tool_name'=>'Programm wählen', 'tool_name_intern'=>Settings::getStringVar('vis2_chtool_module')];

		$this->tools_select=[];

		$QselectTools=self::getConnection();
		$QselectTools->prepare('SELECT * FROM :table_vis2_tool: AS t INNER JOIN :table_vis2_user_tool: AS u ON (u.tool_id=t.tool_id) WHERE t.tool_ispublic=:tool_ispublic: AND u.user_id=:user_id: ORDER BY t.tool_name ASC');
		$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTools->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QselectTools->bindInt(':tool_ispublic:', 1);
		$QselectTools->bindInt(':user_id:', $this->getId());
		foreach ($QselectTools->query() as $tool_details) {
			$this->tools[$tool_details['tool_name_intern']]=['tool_id'=>$tool_details['tool_id'], 'tool_name'=>$tool_details['tool_name'], 'tool_name_intern'=>$tool_details['tool_name_intern']];
			$this->tools_select[$tool_details['tool_name_intern']]=$tool_details['tool_name'];
		}

		return $this;
	}

	/**
	 * @param string $tool_name_intern
	 * @return bool
	 */
	public function checkToolAccess(string $tool_name_intern):bool {
		if ($this->tools===null) {
			$this->loadTools();
		}

		if (isset($this->tools[$tool_name_intern])) {
			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getTools():array {
		if ($this->tools===null) {
			$this->loadTools();
		}

		return $this->tools;
	}

	/**
	 * @return array
	 */
	public function getToolsSelect():array {
		return $this->getToolsSelectArray();
	}

	/**
	 * @return array
	 */
	public function getToolsSelectArray():array {
		if ($this->tools_select===null) {
			$this->loadTools();
		}

		return $this->tools_select;
	}

	/**
	 * @param string $tool_name_intern
	 * @return bool
	 */
	public function hasTool(string $tool_name_intern):bool {
		if ($this->tools===null) {
			$this->loadTools();
		}

		if (isset($this->tools[$tool_name_intern])) {
			return true;
		}

		return false;
	}

	/**
	 * @param int $tool_id
	 * @return bool
	 */
	public function hasToolId(int $tool_id):bool {
		if ($this->tools===null) {
			$this->loadTools();
		}

		foreach ($this->tools as $tool) {
			if ($tool['tool_id']==$tool_id) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return $this
	 */
	public function loadGroups():self {
		$this->groups=[];
		$this->groups_select=[];

		$QselectGroups=self::getConnection();
		$QselectGroups->prepare('SELECT * FROM :table_vis2_group: AS g INNER JOIN :table_vis2_user_group: AS u ON (u.group_id=g.group_id) WHERE g.group_ispublic=:group_ispublic: AND u.user_id=:user_id: ORDER BY g.group_name ASC');
		$QselectGroups->bindTable(':table_vis2_group:', 'vis2_group');
		$QselectGroups->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QselectGroups->bindInt(':group_ispublic:', 1);
		$QselectGroups->bindInt(':user_id:', $this->getId());
		foreach ($QselectGroups->query() as $group_details) {
			$this->groups[$group_details['group_name_intern']]=['group_id'=>$group_details['group_id'], 'group_name'=>$group_details['group_name'], 'group_name_intern'=>$group_details['group_name_intern']];
			$this->groups_select[$group_details['group_name_intern']]=$group_details['group_name'];
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getGroups():array {
		if ($this->groups===null) {
			$this->loadGroups();
		}

		return $this->groups;
	}

	/**
	 * @return array
	 */
	public function getGroupsSelect():array {
		return $this->getGroupsSelectArray();
	}

	/**
	 * @return array
	 */
	public function getGroupsSelectArray():array {
		if ($this->groups_select===null) {
			$this->loadGroups();
		}

		return $this->groups_select;
	}

	/**
	 * @param string $group_name_intern
	 * @return bool
	 */
	public function hasGroup(string $group_name_intern):bool {
		if ($this->groups===null) {
			$this->loadGroups();
		}

		if (isset($this->groups[$group_name_intern])) {
			return true;
		}

		return false;
	}

	/**
	 * @param int $group_id
	 * @return bool
	 */
	public function hasGroupId(int $group_id):bool {
		if ($this->groups===null) {
			$this->loadGroups();
		}

		foreach ($this->groups as $group) {
			if ($group['group_id']==$group_id) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return $this
	 */
	public function loadMandanten():self {
		$this->mandanten=[];
		$this->mandanten_select=[];

		$QselectMandanten=self::getConnection();
		$QselectMandanten->prepare('SELECT *, m.mandant_id as mandant_id FROM :table_vis2_mandant: AS m INNER JOIN :table_vis2_user_mandant: AS u ON (u.mandant_id=m.mandant_id OR u.mandant_id=0) WHERE m.mandant_ispublic=:mandant_ispublic: AND u.user_id=:user_id: AND u.tool_id=:tool_id: ORDER BY m.mandant_name ASC');
		$QselectMandanten->bindTable(':table_vis2_mandant:', 'vis2_mandant');
		$QselectMandanten->bindTable(':table_vis2_user_mandant:', 'vis2_user_mandant');
		$QselectMandanten->bindInt(':mandant_ispublic:', 1);
		$QselectMandanten->bindInt(':user_id:', $this->getId());
		$QselectMandanten->bindInt(':tool_id:', $this->getToolId());
		foreach ($QselectMandanten->query() as $mandant_details) {
			$this->mandanten[$mandant_details['mandant_id']]=['mandant_id'=>$mandant_details['mandant_id'], 'mandant_name'=>$mandant_details['mandant_name']];
			$this->mandanten_select[$mandant_details['mandant_id']]=$mandant_details['mandant_name'];
		}

		return $this;
	}

	/**
	 * @param int $mandant_id
	 * @return bool
	 */
	public function checkMandantAccess(int $mandant_id):bool {
		if ($this->mandanten===null) {
			$this->loadMandanten();
		}

		if (isset($this->mandanten[$mandant_id])) {
			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getMandanten():array {
		if ($this->mandanten===null) {
			$this->loadMandanten();
		}

		return $this->mandanten;
	}

	/**
	 * @return array
	 */
	public function getMandantenSelect():array {
		return $this->getMandantenSelectArray();
	}

	/**
	 * @return array
	 */
	public function getMandantenSelectArray():array {
		if ($this->mandanten_select===null) {
			$this->loadMandanten();
		}

		return $this->mandanten_select;
	}

	/**
	 * @param int $mandant_id
	 * @return bool
	 */
	public function hasMandantId(int $mandant_id):bool {
		if ($this->mandanten===null) {
			$this->loadMandanten();
		}

		if (isset($this->mandanten[$mandant_id])) {
			return true;
		}

		return false;
	}

	/**
	 * @return int|null
	 */
	public function getId():?int {
		return $this->getIntVar('user_id');
	}

	/**
	 * @return string|null
	 */
	public function getEMail():?string {
		return $this->getStringVar('user_email');
	}

	/**
	 * @return string|null
	 */
	public function getName():?string {
		return $this->getStringVar('user_name');
	}

	/**
	 * @param bool $full
	 * @return string|null
	 */
	public function getDisplayName($full=true):?string {
		if ($full===true) {
			if (($this->getStringVar('user_form')!==null)&&($this->getStringVar('user_firstname')!==null)&&($this->getStringVar('user_lastname')!==null)) {
				return trim($this->getStringVar('user_form').' '.$this->getStringVar('user_firstname').' '.$this->getStringVar('user_lastname'));
			}

			return null;
		} else {
			if (($this->getStringVar('user_firstname')!==null)&&($this->getStringVar('user_lastname')!==null)) {
				return trim($this->getStringVar('user_firstname').' '.$this->getStringVar('user_lastname'));
			}

			return null;
		}
	}

	/**
	 * @return string
	 */
	public function getProfileImage():string {
		return Main::getResourcePath().'img'.DIRECTORY_SEPARATOR.'profile.png';
	}

}

?>