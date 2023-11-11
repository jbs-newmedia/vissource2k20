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

$ar_navigation_permission = $this->getAddElementStorage(substr($element, 0, -6));
$ar_navigation_permission_do = $this->getDoAddElementStorage(substr($element, 0, -6));

$vis_time = time();
$vis_user_id = $this->getGroupOption('user_id', 'data');

foreach ($ar_navigation_permission_do as $permission_flag => $flag) {
    if ((!isset($ar_navigation_permission[$permission_flag])) || ($ar_navigation_permission[$permission_flag] !== $flag)) {
        if ($flag === 1) {
            Manager::addPagePermission(
                $this->getIndexElementStorage(),
                $this->getFinishElementOption($element, 'tool_id'),
                $permission_flag,
                $vis_time,
                $vis_user_id
            );
        }
    }
}
