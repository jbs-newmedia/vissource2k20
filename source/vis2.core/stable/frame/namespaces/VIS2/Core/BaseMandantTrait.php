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

trait BaseMandantTrait {

	/**
	 * @var int
	 */
	protected int $mandant_id=0;

	/**
	 * @param int $mandant_id
	 * @return bool
	 */
	public function setMandantId(int $mandant_id):bool {
		$this->mandant_id=$mandant_id;

		return true;
	}

	/**
	 * @return int
	 */
	public function getMandantId():int {
		return $this->mandant_id;
	}

}

?>