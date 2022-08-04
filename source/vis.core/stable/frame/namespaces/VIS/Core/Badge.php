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

use osWFrame\Core\BaseStaticTrait;

class Badge {

	use BaseStaticTrait;

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
	 * Array zum Speichern der Badges.
	 *
	 * @var array
	 */
	protected static array $badge=[];

	/**
	 * Setzt ein Badge.
	 *
	 * @param string $page
	 * @param int $count
	 * @return bool
	 */
	public static function set(string $page, int $count):bool {
		self::$badge[$page]=$count;

		return true;
	}

	/**
	 * Gibt die Anzahl des Badge zurück. Bei NULL ist die Badge nicht angelegt.
	 *
	 * @param string $page
	 * @param int $max
	 * @return int
	 */
	public static function get(string $page, ?int $max=999):?int {
		$name='badge';
		if (isset(self::$badge[$name][$page])) {
			if (($max!==null)&&(self::$badge[$name][$page]>$max)) {
				return $max;
			}

			return self::$badge[$name][$page];
		}

		return null;
	}

}

?>