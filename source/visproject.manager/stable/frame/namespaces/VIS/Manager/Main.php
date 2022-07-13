<?php

/**
 * This file is part of the VIS:Manager package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS:Manager
 * @link https://oswframe.com
 * @license MIT License
 */

namespace VIS\Manager;

use osWFrame\Core as osWFrame;
use VIS\Core as VIS;

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
	private const CLASS_MINOR_VERSION=0;

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
	 *
	 * @param object $osW_Template
	 * @return bool
	 */
	public function setEnvironment(object $osW_Template):bool {
		$this->setTemplate($Template);
	}

}

?>