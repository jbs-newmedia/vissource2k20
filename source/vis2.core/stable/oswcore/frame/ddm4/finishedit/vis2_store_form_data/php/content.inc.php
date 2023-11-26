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

use osWFrame\Core\DDM4_Log;
use osWFrame\Core\MessageStack;
use osWFrame\Core\Settings;

$vars = [];
foreach ($this->getEditElements() as $element_name => $element_details) {
    if (((isset($element_details['enabled'])) && ($element_details['enabled'] === true)) && ((isset($element_details['name'])) && ($element_details['name'] !== ''))) {
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
    $this->getGroupOption('alias', 'database') . '.' . implode(',  ' . $this->getGroupOption('alias', 'database') . '.', $vars)
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

foreach ($this->getEditElements() as $element_name => $element_details) {
    if ((isset($element_details['name'])) && ($element_details['name'] !== '')) {
        switch ($this->getEditElementValidation($element_name, 'module')) {
            case 'integer':
                $QsaveData->bindInt(':' . $element_name . ':', (int)($this->getDoEditElementStorage($element_name)));

                break;
            case 'float':
                $QsaveData->bindFloat(':' . $element_name . ':', (float)($this->getDoEditElementStorage($element_name)));

                break;
            case 'crypt':
                $QsaveData->bindCrypt(':' . $element_name . ':', $this->getDoEditElementStorage($element_name));

                break;
            case 'raw':
                $QsaveData->bindRaw(':' . $element_name . ':', $this->getDoEditElementStorage($element_name));

                break;
            case 'string':
            default:
                $QsaveData->bindString(':' . $element_name . ':', $this->getDoEditElementStorage($element_name));

                break;
        }
    }
}
$QsaveData->execute();

if ($this->getGroupOption('enable_log') === true) {
    $vars = [];

    if ($this->getFinishElementOption($element, 'group') !== '') {
        $group = $this->getFinishElementOption($element, 'group');
    } else {
        $group = $this->getGroupOption('table', 'database');
    }

    foreach ($this->getEditElements() as $element_name => $element_details) {
        if ((isset($element_details['name'])) && ($element_details['name'] !== '')) {
            $value_old = $this->getEditElementStorage($element_name);
            $value_new = $this->getDoEditElementStorage($element_name);

            switch ($this->getEditElementValidation($element_name, 'module')) {
                case 'integer':
                    $value_new = (int)$value_new;
                    $value_new = trim($value_new);
                    $value_old = (int)$value_old;
                    $value_old = trim($value_old);

                    break;
                case 'float':
                    $value_new = (float)$value_new;
                    $value_new = trim($value_new);
                    $value_old = (float)$value_old;
                    $value_old = trim($value_old);
                    // no break
                case 'raw':
                    break;
                case 'crypt':
                    break;
                case 'raw':
                    break;
                case 'string':
                default:
                    $value_new = (string)$value_new;
                    $value_new = trim($value_new);
                    $value_old = (string)$value_old;
                    $value_old = trim($value_old);

                    break;
            }

            if ($value_old !== $value_new) {
                $file = Settings::getStringVar(
                    'settings_abspath'
                ) . 'oswproject' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'list' . \DIRECTORY_SEPARATOR . $element_details['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'log.inc.php';
                $file_core = Settings::getStringVar(
                    'settings_abspath'
                ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'list' . \DIRECTORY_SEPARATOR . $element_details['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'log.inc.php';
                if (file_exists($file)) {
                    include $file;
                } elseif (file_exists($file_core)) {
                    include $file_core;
                }

                if ($this->getFinishElementOption($element, 'createupdatestatus_prefix') !== '') {
                    if (!in_array(
                        $element_name,
                        [
                            $this->getFinishElementOption($element, 'createupdatestatus_prefix') . 'update_user_id',
                            $this->getFinishElementOption($element, 'createupdatestatus_prefix') . 'update_time',
                        ],
                        true
                    )
                    ) {
                        DDM4_Log::addValue(
                            $group,
                            $element_name,
                            $this->getEditElementValue($element_name, 'module'),
                            $value_old,
                            $value_new,
                            $this->getEditElementStorage(
                                $this->getFinishElementOption($element, 'createupdatestatus_prefix') . 'update_user_id'
                            ),
                            $this->getEditElementStorage(
                                $this->getFinishElementOption($element, 'createupdatestatus_prefix') . 'update_time'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption($element, 'createupdatestatus_prefix') . 'update_user_id'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption($element, 'createupdatestatus_prefix') . 'update_time'
                            )
                        );
                    }
                } else {
                    DDM4_Log::addValue(
                        $group,
                        $element_name,
                        $this->getEditElementValue($element_name, 'module'),
                        $value_old,
                        $value_new
                    );
                }
            }
        }
    }
    DDM4_Log::writeValues(
        $group,
        $this->getGroupOption('index', 'database'),
        $this->getIndexElementStorage(),
        $this->getGroupOption('connection_log', 'database')
    );
}

MessageStack::addMessage('ddm4_' . $this->getName(), 'success', [
    'msg' => $this->getGroupMessage('edit_success_title'),
]);
