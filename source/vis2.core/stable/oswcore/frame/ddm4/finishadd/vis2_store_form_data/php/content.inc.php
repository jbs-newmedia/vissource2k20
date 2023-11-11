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
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use osWFrame\Core\MessageStack;

if ($this !== null) {
    $vars_key = [];
    $vars_value = [];

    foreach ($this->getAddElements() as $element => $element_details) {
        if ((isset($element_details['name'])) && ($element_details['name'] !== '')) {
            $vars_key[] = $element_details['name'];
            $vars_value[] = $element;
        }
    }

    // build selector
    $selector_value = [];
    $ddm_selector_array = $this->getGroupOption('selector', 'database');
    if (($ddm_selector_array !== '') && ($ddm_selector_array !== [])) {
        foreach ($ddm_selector_array as $key => $value) {
            $vars_key[] = $key;
            $vars_value[] = $key;
            if (is_int($value) === true) {
                $selector_value[$key] = $value;
            } else {
                $selector_value[$key] = '\'' . $value . '\'';
            }
            $selector_value[$key] = $value;
        }
    }

    $QsaveData = $this::getConnection($this->getGroupOption('connection', 'database'));
    $QsaveData->prepare('INSERT INTO :table: (:vars_name:) VALUES (:vars_value:)');
    $QsaveData->bindTable(':table:', $this->getGroupOption('table', 'database'));
    $QsaveData->bindRaw(':vars_name:', implode(', ', $vars_key));
    $QsaveData->bindRaw(':vars_value:', ':' . implode(':, :', $vars_value) . ':');
    foreach ($this->getAddElements() as $element => $element_details) {
        if ((isset($element_details['name'])) && ($element_details['name'] !== '')) {
            switch ($this->getAddElementValidation($element, 'module')) {
                case 'integer':
                    $QsaveData->bindInt(':' . $element . ':', (int)($this->getDoAddElementStorage($element)));

                    break;
                case 'float':
                    $QsaveData->bindFloat(':' . $element . ':', (float)($this->getDoAddElementStorage($element)));

                    break;
                case 'crypt':
                    $QsaveData->bindCrypt(':' . $element . ':', $this->getDoAddElementStorage($element));

                    break;
                case 'raw':
                    $QsaveData->bindRaw(':' . $element . ':', $this->getDoAddElementStorage($element));

                    break;
                case 'string':
                default:
                    $QsaveData->bindString(':' . $element . ':', (string)($this->getDoAddElementStorage($element)));

                    break;
            }
        }
    }
    if ($selector_value !== []) {
        foreach ($selector_value as $key => $value) {
            if (is_int($value) === true) {
                $QsaveData->bindInt(':' . $key . ':', (int)$value);
            } else {
                $QsaveData->bindString(':' . $key . ':', (string)$value);
            }
        }
    }
    $QsaveData->execute();
    $this->setIndexElementStorage($QsaveData->lastInsertId());
    MessageStack::addMessage('ddm4_' . $this->getName(), 'success', [
        'msg' => $this->getGroupMessage('add_success_title'),
    ]);
}
