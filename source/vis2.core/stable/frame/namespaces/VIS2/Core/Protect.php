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

use osWFrame\Core as osWFrame;

class Protect {

	use osWFrame\BaseStaticTrait;
	use osWFrame\BaseConnectionTrait;

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
	 * @param int $user_id
	 * @return bool
	 */
	public static function addEntry(int $user_id):bool {
		$time=time();

		$QinsertData=self::getConnection();
		$QinsertData->prepare('INSERT INTO :table_vis2_protect: (user_id, protect_create_time, protect_create_user_id, protect_update_time, protect_update_user_id) VALUES (:user_id:, :protect_create_time:, :protect_create_user_id:, :protect_update_time:, :protect_update_user_id:)');
		$QinsertData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QinsertData->bindInt(':user_id:', $user_id);
		$QinsertData->bindInt(':protect_create_time:', $time);
		$QinsertData->bindInt(':protect_create_user_id:', 0);
		$QinsertData->bindInt(':protect_update_time:', $time);
		$QinsertData->bindInt(':protect_update_user_id:', 0);
		$QinsertData->execute();

		return true;
	}

	/**
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public static function clearEntries(int $user_id):bool {
		$QclearData=self::getConnection();
		$QclearData->prepare('DELETE FROM :table_vis2_protect: WHERE user_id=:user_id:');
		$QclearData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QclearData->bindInt(':user_id:', $user_id);
		$QclearData->execute();

		return true;
	}

	/**
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public static function isBlocked(int $user_id):bool {
		$QgetData=self::getConnection();
		$QgetData->prepare('SELECT COUNT(protect_id) AS counter FROM :table_vis2_protect: WHERE user_id=:user_id:');
		$QgetData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QgetData->bindInt(':user_id:', $user_id);
		$QgetData->execute();
		$QgetData->fetch();
		$attempts=$QgetData->getInt('counter');

		if ($attempts==0) {
			return false;
		}

		if ($attempts<osWFrame\Settings::getIntVar('vis2_protect_attempts')) {
			return false;
		}

		if ($attempts>=osWFrame\Settings::getIntVar('vis2_protect_attempts_max')) {
			return true;
		}

		$attempts=bcdiv($attempts, osWFrame\Settings::getIntVar('vis2_protect_attempts'));
		$time=osWFrame\Settings::getIntVar('vis2_protect_time')*pow(2, $attempts-1);

		$QgetData=self::getConnection();
		$QgetData->prepare('SELECT protect_create_time FROM :table_vis2_protect: WHERE user_id=:user_id: ORDER BY protect_id ASC LIMIT :start:, 1');
		$QgetData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QgetData->bindInt(':user_id:', $user_id);
		$QgetData->bindInt(':start:', ($attempts*osWFrame\Settings::getIntVar('vis2_protect_attempts'))-1);
		$QgetData->execute();
		$QgetData->fetch();
		if (($QgetData->getInt('protect_create_time')+$time)>time()) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @param int $user_id
	 * @return int
	 */
	public static function getTime(int $user_id):int {
		$QgetData=self::getConnection();
		$QgetData->prepare('SELECT COUNT(protect_id) AS counter FROM :table_vis2_protect: WHERE user_id=:user_id:');
		$QgetData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QgetData->bindInt(':user_id:', $user_id);
		$QgetData->execute();
		$QgetData->fetch();
		$attempts=$QgetData->getInt('counter');

		if ($attempts>=osWFrame\Settings::getIntVar('vis2_protect_attempts_max')) {
			return 0;
		}

		$attempts=bcdiv($attempts, osWFrame\Settings::getIntVar('vis2_protect_attempts'));
		$time=osWFrame\Settings::getIntVar('vis2_protect_time')*pow(2, $attempts-1);

		$QgetData=self::getConnection();
		$QgetData->prepare('SELECT protect_create_time FROM :table_vis2_protect: WHERE user_id=:user_id: ORDER BY protect_id ASC LIMIT :start:, 1');
		$QgetData->bindTable(':table_vis2_protect:', 'vis2_protect');
		$QgetData->bindInt(':user_id:', $user_id);
		$QgetData->bindInt(':start:', ($attempts*osWFrame\Settings::getIntVar('vis2_protect_attempts'))-1);
		$QgetData->execute();
		$QgetData->fetch();

		return ($QgetData->getInt('protect_create_time')+$time);
	}

}

?>