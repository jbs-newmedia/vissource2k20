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

use \osWFrame\Core as osWFrame;
use osWFrame\Core\Settings;
use PHPMailer\PHPMailer\Exception;

class User {

	use osWFrame\BaseStaticTrait;
	use osWFrame\BaseConnectionTrait;
	use osWFrame\BaseVarTrait;
	use BaseToolTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=2;

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
	 * @var bool
	 */
	private bool $logged_in=false;

	/**
	 * @var array|null
	 */
	private ?array $tools=null;

	/**
	 * @var array|null
	 */
	private ?array $mandanten=null;

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
		return osWFrame\Filter::verifyEmailIDNAPattern($email);
	}

	/**
	 *
	 * @param string $pwblank
	 * @param string $pwcrypted
	 * @return bool
	 */
	public static function validatePassword(string $pwblank, string $pwcrypted):bool {
		if (($pwblank!='')&&($pwcrypted!='')) {

			if (password_verify($pwblank, $pwcrypted)) {
				return true;
			}

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
	 * @param string $password
	 * @return bool
	 */
	public function rehashPassword(string $password):bool {
		if ($this->getId()!==null) {

			$hash=osWFrame\StringFunctions::encryptString($password);

			$QupdateData=self::getConnection();
			$QupdateData->prepare('UPDATE :table_vis2_user: SET user_password=:user_password: WHERE user_id=:user_id:');
			$QupdateData->bindTable(':table_vis2_user:', 'vis2_user');
			$QupdateData->bindString(':user_password:', $hash);
			$QupdateData->bindInt(':user_id:', $this->getId());

			return $QupdateData->execute()&&$this->setStringVar('user_password', $hash);
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

			osWFrame\Session::setStringVar('vis2_user_token', $user_token);

			return true;
		}

		return false;
	}

	/**
	 * @param string $user_token
	 * @return bool
	 */
	public function doLoginByToken(string $user_token):bool {
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

			osWFrame\Session::setStringVar('vis2_user_token', $user_token);

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
	 * @return bool
	 */
	public function loadTools():bool {
		$this->tools=[];
		$this->tools[Settings::getStringVar('vis2_login_module')]=['tool_id'=>0, 'tool_name'=>'Anmelden', 'tool_name_intern'=>Settings::getStringVar('vis2_login_module')];
		$this->tools[Settings::getStringVar('vis2_chtool_module')]=['tool_id'=>0, 'tool_name'=>'Programm wählen', 'tool_name_intern'=>Settings::getStringVar('vis2_chtool_module')];

		$QselectTools=self::getConnection();
		$QselectTools->prepare('SELECT * FROM :table_vis2_tool: AS t INNER JOIN :table_vis2_user_tool: AS u ON (u.tool_id=t.tool_id) WHERE t.tool_ispublic=:tool_ispublic: AND u.user_id=:user_id: ORDER BY t.tool_name ASC');
		$QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
		$QselectTools->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
		$QselectTools->bindInt(':tool_ispublic:', 1);
		$QselectTools->bindInt(':user_id:', $this->getId());
		foreach ($QselectTools->query() as $tool_details) {
			$this->tools[$tool_details['tool_name_intern']]=['tool_id'=>$tool_details['tool_id'], 'tool_name'=>$tool_details['tool_name'], 'tool_name_intern'=>$tool_details['tool_name_intern']];
		}

		return true;
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
	public function getToolsSelectArray():array {
		if ($this->tools===null) {
			$this->loadTools();
		}

		$tools=[];
		foreach ($this->tools as $tool_details) {
			$tools[$tool_details['tool_name_intern']]=$tool_details['tool_name'];
		}
		unset($tools[Settings::getStringVar('vis2_login_module')]);
		unset($tools[Settings::getStringVar('vis2_chtool_module')]);

		return $tools;
	}

	/**
	 * @return bool
	 */
	public function loadMandanten():bool {
		$this->mandanten=[];

		$QselectMandanten=self::getConnection();
		$QselectMandanten->prepare('SELECT *, m.mandant_id as mandant_id FROM :table_vis2_mandant: AS m INNER JOIN :table_vis2_user_mandant: AS u ON (u.mandant_id=m.mandant_id OR u.mandant_id=0) WHERE m.mandant_ispublic=:mandant_ispublic: AND u.user_id=:user_id: AND u.tool_id=:tool_id: ORDER BY m.mandant_name ASC');
		$QselectMandanten->bindTable(':table_vis2_mandant:', 'vis2_mandant');
		$QselectMandanten->bindTable(':table_vis2_user_mandant:', 'vis2_user_mandant');
		$QselectMandanten->bindInt(':mandant_ispublic:', 1);
		$QselectMandanten->bindInt(':user_id:', $this->getId());
		$QselectMandanten->bindInt(':tool_id:', $this->getToolId());
		foreach ($QselectMandanten->query() as $mandant_details) {
			$this->mandanten[$mandant_details['mandant_id']]=['mandant_id'=>$mandant_details['mandant_id'], 'mandant_name'=>$mandant_details['mandant_name']];
		}

		return true;
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
	public function getMandantenSelectArray():array {
		if ($this->mandanten===null) {
			$this->loadMandanten();
		}

		$mandanten=[];
		foreach ($this->mandanten as $mandanten_details) {
			$mandanten[$mandanten_details['mandant_id']]=$mandanten_details['mandant_name'];
		}

		return $mandanten;
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