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

if ($this->getEditElementOption($element, 'manager') === true) {
    $this->setEditElementStorage($element, Manager::loadUserGroupByUserId($this->getIndexElementStorage(), 0));
} else {
    $this->setEditElementStorage(
        $element,
        Manager::loadUserGroupByUserId(
            $this->getIndexElementStorage(),
            $this->getEditElementOption($element, 'tool_id')
        )
    );
}

if (Settings::getAction() === 'doedit') {
    $ar_user_group = [];
    if ($this->getEditElementOption($element, 'manager') === true) {
        foreach (Manager::getTools() as $tool_id => $tool_name) {
            foreach (Manager::getGroupsByToolId($tool_id) as $group_id => $group_name) {
                if ((isset($_POST[$element . '_' . $group_id])) && ($_POST[$element . '_' . $group_id] === '1')) {
                    $ar_user_group[$tool_id][$group_id] = 1;
                } else {
                    $ar_user_group[$tool_id][$group_id] = 0;
                    if (!isset($_POST[$element . '_' . $group_id])) {
                        $_POST[$element . '_' . $group_id] = 0;
                    }
                }
            }
        }
    } else {
        foreach (Manager::getGroupsByToolId(
            $this->getEditElementOption($element, 'tool_id')
        ) as $group_id => $group_name) {
            if ((isset($_POST[$element . '_' . $group_id])) && ($_POST[$element . '_' . $group_id] === '1')) {
                $ar_user_group[$this->getEditElementOption($element, 'tool_id')][$group_id] = 1;
            } else {
                $ar_user_group[$this->getEditElementOption($element, 'tool_id')][$group_id] = 0;
                if (!isset($_POST[$element . '_' . $group_id])) {
                    $_POST[$element . '_' . $group_id] = 0;
                }
            }
        }
    }
    $this->setDoEditElementStorage($element, $ar_user_group);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
