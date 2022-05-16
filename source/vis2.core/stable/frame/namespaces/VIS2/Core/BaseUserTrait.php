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

trait BaseUserTrait {

	/**
	 * @var int
	 */
	protected int $user_id=0;

	/**
	 * @param int $user_id
	 * @return bool
	 */
	public function setUserId(int $user_id):bool {
		$this->user_id=$user_id;

		return true;
	}

	/**
	 * @return int
	 */
	public function getUserId():int {
		return $this->user_id;
	}

}

?>