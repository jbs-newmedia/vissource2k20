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

use osWFrame\Core\DDM4;
use osWFrame\Core\SessionMessageStack;

switch ($this->getFinishElementOption($element, 'mode')) {
    case 'update':
        $mode = 'update';

        break;
    default:
        $mode = 'insert';

        break;
}

if ($mode === 'insert') {
    $vars_key = [];
    $vars_value = [];
    foreach ($this->getSendElements() as $element => $element_details) {
        if ((isset($element_details['name'])) && ($element_details['name'] !== '')) {
            $vars_key[] = $element_details['name'];
            $vars_value[] = $element;
        }
    }

    // build selector
    $ddm_search_case_array_keys = '';
    $ddm_search_case_array_values = '';
    $ddm_selector_array = $this->getGroupOption('selector', 'database');
    if (($ddm_selector_array !== '') && ($ddm_selector_array !== [])) {
        $ar_keys = [];
        $ar_values = [];
        foreach ($ddm_selector_array as $key => $value) {
            $ar_keys[] = $key;
            if (is_int($value) === true) {
                $ar_values[] = $value;
            } else {
                $ar_values[] = '\'' . $value . '\'';
            }
        }
        if ($ar_values !== []) {
            $ddm_search_case_array_keys = ', ' . implode(', ', $ar_keys);
        }
        if ($ar_values !== []) {
            $ddm_search_case_array_values = ', ' . implode(', ', $ar_values);
        }
    }

    if ($this->getGroupOption('index', 'database') !== '') {
        $ddm_search_case_array_keys .= ',' . $this->getGroupOption('index', 'database');
        if ($this->getGroupOption('db_index_type', 'database') === 'string') {
            $ddm_search_case_array_values .= ', \'' . $this->getIndexElementStorage() . '\'';
        } else {
            $ddm_search_case_array_values .= ', ' . (int)($this->getIndexElementStorage());
        }
    }

    $QsaveData = $this::getConnection($this->getGroupOption('connection', 'database'));
    $QsaveData->prepare(
        'INSERT INTO :table: (:vars_name: :ddm_search_case_array_keys:) VALUES (:vars_value: :ddm_search_case_array_values:)'
    );
    $QsaveData->bindTable(':table:', $this->getGroupOption('table', 'database'));
    $QsaveData->bindRaw(':vars_name:', implode(', ', $vars_key));
    $QsaveData->bindRaw(':vars_value:', ':' . implode(':, :', $vars_value) . ':');
    $QsaveData->bindRaw(':ddm_search_case_array_keys:', $ddm_search_case_array_keys);
    $QsaveData->bindRaw(':ddm_search_case_array_values:', $ddm_search_case_array_values);
    foreach ($this->getSendElements() as $element => $element_details) {
        if ((isset($element_details['name'])) && ($element_details['name'] !== '')) {
            switch ($this->getSendElementValidation($element, 'module')) {
                case 'integer':
                    $QsaveData->bindInt(':' . $element . ':', (int)($this->getDoSendElementStorage($element)));

                    break;
                case 'float':
                    $QsaveData->bindFloat(':' . $element . ':', (float)($this->getDoSendElementStorage($element)));

                    break;
                case 'crypt':
                    $QsaveData->bindCrypt(':' . $element . ':', $this->getDoSendElementStorage($element));

                    break;
                case 'raw':
                    $QsaveData->bindRaw(':' . $element . ':', $this->getDoSendElementStorage($element));

                    break;
                case 'string':
                default:
                    $QsaveData->bindString(':' . $element . ':', $this->getDoSendElementStorage($element));

                    break;
            }
        }
    }
    $QsaveData->execute();

    $this->setIndexElementStorage($QsaveData->lastInsertId());
    SessionMessageStack::addMessage('session', 'success', [
        'msg' => $this->getGroupMessage('send_success_title'),
    ]);
}

if ($mode === 'update') {
    $vars = [];
    foreach ($this->getSendElements() as $element_name => $element_details) {
        if ((isset($element_details['name'])) && ($element_details['name'] !== '')) {
            $vars[] = $element_details['name'] . '=:' . $element_name . ':';
        }
    }

    // build selector
    $ddm_search_case_array = '';
    $ddm_selector_array = $this->getGroupOption('selector', 'database');
    if (($ddm_selector_array !== '') && ($ddm_selector_array !== [])) {
        $ar_values = [];
        foreach ($ddm_selector_array as $key => $value) {
            if (is_int($value) === true) {
                $ar_values[] = $this->getGroupOption('alias', 'database') . '.' . $key . '=' . $value;
            } else {
                $ar_values[] = $this->getGroupOption('alias', 'database') . '.' . $key . '=\'' . $value . '\'';
            }
        }
        $ddm_search_case_array = 'AND (' . implode(' AND ', $ar_values) . ')';
    }

    $QsaveData = $this::getConnection($this->getGroupOption('connection', 'database'));
    $QsaveData->prepare('UPDATE :table: AS :alias: SET :vars: WHERE :name_index:=:value_index: :search_filter:');
    $QsaveData->bindTable(':table:', $this->getGroupOption('table', 'database'));
    $QsaveData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
    $QsaveData->bindRaw(
        ':vars:',
        $this->getGroupOption('alias', 'database') . '.' . implode(
            ',  ' . $this->getGroupOption('alias', 'database') . '.',
            $vars
        )
    );
    $QsaveData->bindRaw(
        ':name_index:',
        $this->getGroupOption('alias', 'database') . '.' . $this->getGroupOption('index', 'database')
    );
    if ($this->getGroupOption('db_index_type', 'database') === 'string') {
        $QsaveData->bindString(':value_index:', $this->getIndexElementStorage());
    } else {
        $QsaveData->bindInt(':value_index:', (int)($this->getIndexElementStorage()));
    }
    $QsaveData->bindRaw(':search_filter:', $ddm_search_case_array);

    foreach ($this->getSendElements() as $element_name => $element_details) {
        if ((isset($element_details['name'])) && ($element_details['name'] !== '')) {
            switch ($this->getSendElementValidation($element_name, 'module')) {
                case 'integer':
                    $QsaveData->bindInt(':' . $element_name . ':', $this->getDoSendElementStorage($element_name));

                    break;
                case 'float':
                    $QsaveData->bindFloat(':' . $element_name . ':', $this->getDoSendElementStorage($element_name));

                    break;
                case 'crypt':
                    $QsaveData->bindCrypt(':' . $element_name . ':', $this->getDoSendElementStorage($element_name));

                    break;
                case 'raw':
                    $QsaveData->bindRaw(':' . $element_name . ':', $this->getDoSendElementStorage($element_name));

                    break;
                case 'string':
                default:
                    $QsaveData->bindString(':' . $element_name . ':', $this->getDoSendElementStorage($element_name));

                    break;
            }
        }
    }
    $QsaveData->execute();

    SessionMessageStack::addMessage('session', 'success', [
        'msg' => $this->getGroupMessage('send_success_title'),
    ]);
}
