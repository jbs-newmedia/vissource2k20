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

use osWFrame\Core\Settings;
use VIS2\Core\Manager;

$this->setEditElementStorage(
    $element,
    Manager::loadPagePermission($this->getIndexElementStorage(), $this->getEditElementOption($element, 'tool_id'))
);

if (Settings::getAction() === 'doedit') {
    $ar_navigation_permission = [];
    foreach (Manager::getPermissionTextList(
        $this->getEditElementOption($element, 'tool_id')
    ) as $permission_flag => $permission_name) {
        if ((isset($_POST[$element . '_' . $permission_flag])) && ($_POST[$element . '_' . $permission_flag] === '1')) {
            $ar_navigation_permission[$permission_flag] = 1;
        } else {
            $ar_navigation_permission[$permission_flag] = 0;
            if (!isset($_POST[$element . '_' . $permission_flag])) {
                $_POST[$element . '_' . $permission_flag] = 0;
            }
        }
    }
    $this->setDoEditElementStorage($element, $ar_navigation_permission);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
