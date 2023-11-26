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

$this->setAddElementStorage($element, []);

if (Settings::getAction() === 'doadd') {
    $ar_user_mandant = [];
    if ($this->getAddElementOption($element, 'manager') === true) {
        foreach (Manager::getTools() as $tool_id => $tool_name) {
            foreach (Manager::getMandantenByToolId($tool_id, true) as $mandant_id => $mandant_name) {
                if ((isset($_POST[$element . '_' . $tool_id . '_' . $mandant_id])) && ($_POST[$element . '_' . $tool_id . '_' . $mandant_id] === '1')) {
                    $ar_user_mandant[$tool_id][$mandant_id] = 1;
                } else {
                    $ar_user_mandant[$tool_id][$mandant_id] = 0;
                    if (!isset($_POST[$element . '_' . $tool_id . '_' . $mandant_id])) {
                        $_POST[$element . '_' . $tool_id . '_' . $mandant_id] = 0;
                    }
                }
            }
        }
    } else {
        foreach (Manager::getMandantenByToolId(
            $this->getAddElementOption($element, 'tool_id'),
            true
        ) as $mandant_id => $mandant_name) {
            if ((isset(
                $_POST[$element . '_' . $this->getAddElementOption(
                    $element,
                    'tool_id'
                ) . '_' . $mandant_id]
            )) && ($_POST[$element . '_' . $this->getAddElementOption($element, 'tool_id') . '_' . $mandant_id] === '1')
            ) {
                $ar_user_mandant[$this->getAddElementOption($element, 'tool_id')][$mandant_id] = 1;
            } else {
                $ar_user_mandant[$this->getAddElementOption($element, 'tool_id')][$mandant_id] = 0;
                if (!isset($_POST[$element . '_' . $this->getAddElementOption($element, 'tool_id') . '_' . $mandant_id])) {
                    $_POST[$element . '_' . $this->getAddElementOption($element, 'tool_id') . '_' . $mandant_id] = 0;
                }
            }
        }
    }
    $this->setDoAddElementStorage($element, $ar_user_mandant);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
