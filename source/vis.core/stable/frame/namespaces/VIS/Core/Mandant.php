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

class Mandant {

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
	 * Speichert alle Mandanten mit Details.
	 *
	 * @var array|null
	 */
	protected ?array $mandanten=null;

	/**
	 * Mandant constructor.
	 *
	 * @param int $tool_id
	 */
	public function __construct(int $tool_id=0) {
		if ($tool_id>0) {
			$this->setToolId($tool_id);
		}
	}

	/**
	 * @return bool
	 */
	public function isLoaded():bool {
		if ($this->mandanten===null) {

			return false;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getMandanten():array {
		if ($this->isLoaded()!==true) {
			$this->loadMandanten();
		}

		return $this->mandanten;
	}

	/**
	 * Lädt alle Mandanten.
	 *
	 * @return $this
	 */
	private function loadMandanten():self {
		$this->mandanten=[];

		$QselectMandanten=self::getConnection(osWFrame\Settings::getStringVar('vis_database_alias'));
		$QselectMandanten->prepare('SELECT * FROM :table_vis_mandant: WHERE tool_id=:tool_id: AND mandant_ispublic=:mandant_ispublic: ORDER BY mandant_name ASC');
		$QselectMandanten->bindTable(':table_vis_mandant:', 'vis_mandant');
		$QselectMandanten->bindInt(':mandant_ispublic:', 1);
		$QselectMandanten->bindInt(':tool_id:', $this->getToolId());
		foreach ($QselectMandanten->query() as $mandant) {
			$this->mandanten[$mandant['mandant_id']]=['mandant_id'=>$mandant['mandant_id'], 'mandant_number'=>$mandant['mandant_number'], 'mandant_name_intern'=>$mandant['mandant_name_intern'], 'mandant_name'=>$mandant['mandant_name']];
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getMandantenSelectArray():array {
		if ($this->isLoaded()!==true) {
			$this->loadMandanten();
		}

		$mandanten=[];
		foreach ($this->mandanten as $mandant_details) {
			$mandanten[$mandant_details['mandant_id']]=$mandant_details['mandant_name'];
		}

		return $mandanten;
	}

	/**
	 *
	 * @param int $mandant_id
	 * @return bool
	 */
	public function setId(int $mandant_id):bool {
		return osWFrame\Session::setIntVar('vis_mandante_id_'.$this->getToolId(), $mandant_id);
	}

	/**
	 *
	 * @return int
	 */
	public function getId():int {
		return intval(osWFrame\Session::getIntVar('vis_mandante_id_'.$this->getToolId()));
	}

	/**
	 * @return string|null
	 */
	public function getName():?string {
		if ($this->isLoaded()!==true) {
			$this->loadMandanten();
		}
		if (isset($this->mandanten[$this->getId()])) {
			return $this->mandanten[$this->getId()]['mandant_name'];
		}

		return null;
	}

	/**
	 * @param string $link
	 * @param string $message
	 */
	public function directEmptyMandant(string $link, string $message=''):void {
		if ($this->getId()==0) {
			if ($message=='') {
				$message='Bitte einen Mandanten auswählen';
			}
			osWFrame\SessionMessageStack::addMessage('session', 'warning', ['msg'=>$message]);
			osWFrame\Network::directHeader($link);
		}
	}

}

?>