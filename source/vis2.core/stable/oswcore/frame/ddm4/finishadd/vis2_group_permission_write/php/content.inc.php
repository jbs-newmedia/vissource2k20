<?php declare(strict_types=0);

/**
 * This file is part of the VIS2 package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var string $element
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use VIS2\Core\Manager;

$ar_permission = $this->getAddElementStorage(substr($element, 0, -6));
$ar_permission_do = $this->getDoAddElementStorage(substr($element, 0, -6));

$vis_time = time();
$vis_user_id = $this->getGroupOption('user_id', 'data');

foreach ($ar_permission_do as $permission_page => $permissions) {
    foreach ($permissions as $permission => $flag) {
        if (((!isset($ar_permission[$permission_page])) || (!isset($ar_permission[$permission_page][$permission]))) || ($ar_permission[$permission_page][$permission] !== $flag)) {
            if ($flag === 1) {
                Manager::addGroupPermission(
                    $this->getIndexElementStorage(),
                    $permission_page,
                    $permission,
                    $vis_time,
                    $vis_user_id
                );
            }
        }
    }
}
