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

use \osWFrame\Core as osWFrame;

class Main {

	use osWFrame\BaseStaticTrait;
	use osWFrame\BaseConnectionTrait;
	use osWFrame\BaseVarTrait;
	use osWFrame\BaseTemplateBridgeTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=2;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=3;

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
	 * @var string
	 */
	protected string $tool='';

	/**
	 * @var array
	 */
	protected array $tools=[];

	/**
	 * Main constructor.
	 */
	public function __construct() {

	}

	/**
	 * @return string
	 */
	public static function getResourcePath():string {
		$version=self::getVersion();
		$dir=strtolower('VIS');

		return osWFrame\Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;
	}

	/**
	 * @param object $Template
	 * @return $this
	 */
	public function setEnvironment(object $Template):self {
		$version=self::getVersion();
		$dir=strtolower('VIS');
		$name=$version.'.resource';
		$path=self::getResourcePath();
		if (osWFrame\Resource::existsResource('VIS', $name)!==true) {
			$files=['js'.DIRECTORY_SEPARATOR.'vis.js', 'css'.DIRECTORY_SEPARATOR.'vis.css', 'img'.DIRECTORY_SEPARATOR.'profile.png'];

			osWFrame\Resource::copyResourcePath('modules'.DIRECTORY_SEPARATOR.'vis'.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $files);
			osWFrame\Resource::writeResource('VIS', $name, time());
		}

		$this->setTemplate($Template);

		$this->addTemplateJSFile('head', $path.'js'.DIRECTORY_SEPARATOR.'vis.js');
		$this->addTemplateCSSFile('head', $path.'css'.DIRECTORY_SEPARATOR.'vis.css');

		return $this;
	}

	/**
	 * @param string $file
	 * @return string
	 */
	public function getResourceLink(string $file):string {
		$version=self::getVersion();
		$dir=strtolower('VIS');
		$name=$version.'.resource';
		$path=self::getResourcePath();

		$rfile=osWFrame\Settings::getStringVar('settings_abspath').$path.$file;
		$lfile=osWFrame\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.'vis'.DIRECTORY_SEPARATOR.$file;

		if ((osWFrame\Filesystem::existsFile($rfile)!==true)||((osWFrame\Filesystem::getFileModTime($rfile))<(osWFrame\Filesystem::getFileModTime($lfile)))) {
			osWFrame\Resource::copyResourcePath('modules'.DIRECTORY_SEPARATOR.'vis'.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, [$file]);
		}

		return $path.$file;
	}

	/*
	 * Setzt das aktuelle Tool.
	 *
	 * @param string $tool
	 * @return boolean
	 */
	public function setTool(string $tool):bool {
		$tool=strtolower($tool);
		if ($this->validateTool($tool)===true) {
			$this->tool=$tool;
			osWFrame\Session::setStringVar('vis_tool', $this->tool);
			$this->loadToolDetails();

			return true;
		}

		return false;
	}

	/*
	 * Liefert das aktuelle Tool.
	 *
	 * @return string
	 */
	public function getTool():string {
		return $this->tool;
	}

	/*
	 * Überprueft ob es sich um ein gültiges Modul handelt.
	 *
	 * @param string $tool
	 * @return bool
	 */
	private function validateTool(string $tool):bool {
		if ($this->tools==[]) {
			$this->loadTools();
		}
		if (isset($this->tools[$tool])) {
			return true;
		}

		return false;
	}

	/*
	 * Ermittelt die vorhanden Tools.
	 *
	 * @param bool $force
	 * @return $this
	 */
	public function loadTools():self {
		$this->tools==[];
		$this->tools[osWFrame\Settings::getStringVar('vis_login_module')]='Logon';
		$this->tools[osWFrame\Settings::getStringVar('vis_chtool_module')]='ChTool';

		$QselectTools=self::getConnection(osWFrame\Settings::getStringVar('vis_database_alias'));
		$QselectTools->prepare('SELECT * FROM :table_vis_tool: WHERE tool_status=:tool_status: ORDER BY tool_name ASC');
		$QselectTools->bindTable(':table_vis_tool:', 'vis_tool');
		$QselectTools->bindInt(':tool_status:', 1);
		foreach ($QselectTools->query() as $tool) {
			$this->tools[$tool['tool_name_intern']]=$tool['tool_name'];
		}

		return $this;
	}

	/*
	 * Ermittelt die vorhanden Tools.
	 *
	 * @param bool $force
	 * @return array
	 */
	public function getTools():array {
		if ($this->tools==[]) {
			$this->loadTools();
		}

		return $this->tools;
	}

	/*
	 * Ermittelt Details des Tools.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function loadToolDetails():bool {
		$this->clearVars();

		if (in_array($this->getTool(), [\osWFrame\Core\Settings::getStringVar('vis_login_module'), \osWFrame\Core\Settings::getStringVar('vis_chtool_module')])) {
			if ($this->getTool()==\osWFrame\Core\Settings::getStringVar('vis_login_module')) {
				$this->vars=['tool_id'=>0, 'tool_name_intern'=>\osWFrame\Core\Settings::getStringVar('vis_login_module'), 'tool_name'=>'Anmelden', 'tool_description'=>'Anmelden', 'tool_status'=>1, 'tool_hide_logon'=>0, 'tool_hide_navigation'=>0, 'tool_use_mandant'=>0, 'tool_use_mandantswitch'=>0, 'tool_create_time'=>0, 'tool_create_user_id'=>0, 'tool_update_time'=>0, 'tool_update_user_id'=>0];
			}
			if ($this->getTool()==\osWFrame\Core\Settings::getStringVar('vis_chtool_module')) {
				$this->vars=['tool_id'=>0, 'tool_name_intern'=>\osWFrame\Core\Settings::getStringVar('vis_chtool_module'), 'tool_name'=>'Programm wählen', 'tool_description'=>'Programm wählen', 'tool_status'=>1, 'tool_hide_logon'=>0, 'tool_hide_navigation'=>0, 'tool_use_mandant'=>0, 'tool_use_mandantswitch'=>0, 'tool_create_time'=>0, 'tool_create_user_id'=>0, 'tool_update_time'=>0, 'tool_update_user_id'=>0];
			}
		} else {
			$QgetToolDetails=self::getConnection(osWFrame\Settings::getStringVar('vis_database_alias'));
			$QgetToolDetails->prepare('SELECT * FROM :table_vis_tool: WHERE tool_name_intern=:tool_name_intern:');
			$QgetToolDetails->bindTable(':table_vis_tool:', 'vis_tool');
			$QgetToolDetails->bindString(':tool_name_intern:', $this->getTool());
			if ($QgetToolDetails->exec()==1) {
				$this->vars=$QgetToolDetails->fetch();

				return true;
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getToolDetails():array {
		if ($this->isVarsLoaded()!==true) {
			$this->loadToolDetails();
		}

		return $this->vars;
	}

	/*
	 * Liefert die aktuelle ToolId
	 *
	 * @return int|null
	 */
	public function getToolId():?int {
		return $this->getIntVar('tool_id');
	}

	/*
	 * Liefert den aktuellen ToolName
	 *
	 * @return int|null
	 */
	public function getToolName():?string {
		return $this->getStringVar('tool_name');
	}

}

?>