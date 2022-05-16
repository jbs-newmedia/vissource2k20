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

trait BaseToolTrait {

	/**
	 * @var int
	 */
	protected int $tool_id=0;

	/**
	 * @param int $tool_id
	 * @return bool
	 */
	public function setToolId(int $tool_id):bool {
		$this->tool_id=$tool_id;

		return true;
	}

	/**
	 * @return int
	 */
	public function getToolId():int {
		return $this->tool_id;
	}

}

?>