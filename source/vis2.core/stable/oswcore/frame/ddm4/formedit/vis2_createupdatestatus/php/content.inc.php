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

if ((Settings::getAction() === 'edit') || (Settings::getAction() === 'doedit')) {
    $database_where_string = '';

    // build selector
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
        $database_where_string .= ' AND (' . implode(' AND ', $ar_values) . ')';
    }

    // build filter
    $ddm_filter_array = $this->getGroupOption('filter', 'database');
    if (($ddm_filter_array !== '') && ($ddm_filter_array !== [])) {
        $ddm_filter = [];
        foreach ($ddm_filter_array as $filter) {
            $ar_values = [];
            $logic = 'and';
            foreach ($filter as $logic => $elements) {
                foreach ($elements as $_element) {
                    $ar_values[] = $this->getGroupOption(
                        'alias',
                        'database'
                    ) . '.' . $_element['key'] . $_element['operator'] . $_element['value'];
                }
            }
            $ddm_filter[] = '(' . implode(' ' . strtoupper($logic) . ' ', $ar_values) . ')';
        }
        $database_where_string .= ' AND (' . implode(' OR ', $ddm_filter) . ')';
    }

    $Qselect = $this::getConnection($this->getGroupOption('connection', 'database'));
    $Qselect->prepare('SELECT :elements: FROM :table: AS :alias: WHERE :name_index:=:value_index: :where:');
    $Qselect->bindRaw(
        ':elements:',
        implode(
            ', ',
            [
                $this->getGroupOption('alias', 'database') . '.' . $this->getEditElementOption(
                    $element,
                    'prefix'
                ) . 'create_time',
                $this->getGroupOption('alias', 'database') . '.' . $this->getEditElementOption(
                    $element,
                    'prefix'
                ) . 'create_user_id',
                $this->getGroupOption('alias', 'database') . '.' . $this->getEditElementOption(
                    $element,
                    'prefix'
                ) . 'update_time',
                $this->getGroupOption('alias', 'database') . '.' . $this->getEditElementOption(
                    $element,
                    'prefix'
                ) . 'update_user_id',
            ]
        )
    );
    $Qselect->bindTable(':table:', $this->getGroupOption('table', 'database'));
    $Qselect->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
    $Qselect->bindRaw(
        ':name_index:',
        $this->getGroupOption('alias', 'database') . '.' . $this->getGroupOption('index', 'database')
    );
    if ($this->getGroupOption('db_index_type', 'database') === 'string') {
        $Qselect->bindString(':value_index:', $this->getIndexElementStorage());
    } else {
        $Qselect->bindInt(':value_index:', $this->getIndexElementStorage());
    }
    $Qselect->bindRaw(':where:', $database_where_string);
    if ($Qselect->exec() === 1) {
        $result = $Qselect->fetch();

        if (Settings::getAction() === 'edit') {
            $this->setEditElementStorage(
                $this->getEditElementOption($element, 'prefix') . 'create_time',
                $result[$this->getEditElementOption($element, 'prefix') . 'create_time']
            );
            $this->setEditElementStorage(
                $this->getEditElementOption($element, 'prefix') . 'create_user_id',
                $result[$this->getEditElementOption($element, 'prefix') . 'create_user_id']
            );
            $this->setEditElementStorage(
                $this->getEditElementOption($element, 'prefix') . 'update_time',
                $result[$this->getEditElementOption($element, 'prefix') . 'update_time']
            );
            $this->setEditElementStorage(
                $this->getEditElementOption($element, 'prefix') . 'update_user_id',
                $result[$this->getEditElementOption($element, 'prefix') . 'update_user_id']
            );
        }

        if (Settings::getAction() === 'doedit') {
            $this->setDoEditElementStorage(
                $this->getEditElementOption($element, 'prefix') . 'create_time',
                $result[$this->getEditElementOption($element, 'prefix') . 'create_time']
            );
            $this->setDoEditElementStorage(
                $this->getEditElementOption($element, 'prefix') . 'create_user_id',
                $result[$this->getEditElementOption($element, 'prefix') . 'create_user_id']
            );
            $this->setDoEditElementStorage(
                $this->getEditElementOption($element, 'prefix') . 'update_time',
                $this->getEditElementOption($element, 'time')
            );
            $this->setDoEditElementStorage(
                $this->getEditElementOption($element, 'prefix') . 'update_user_id',
                $this->getEditElementOption($element, 'user_id')
            );

            $this->addDataElement($this->getEditElementOption($element, 'prefix') . 'update_time', [
                'module' => 'hidden',
                'name' => $this->getEditElementOption($element, 'prefix') . 'update_time',
                'options' => [
                    'default_value' => $this->getDoEditElementStorage(
                        $this->getEditElementOption($element, 'prefix') . 'update_time'
                    ),
                ],
                'validation' => [
                    'module' => 'integer',
                ],
            ]);
            $this->addDataElement($this->getEditElementOption($element, 'prefix') . 'update_user_id', [
                'module' => 'hidden',
                'name' => $this->getEditElementOption($element, 'prefix') . 'update_user_id',
                'options' => [
                    'default_value' => $this->getDoEditElementStorage(
                        $this->getEditElementOption($element, 'prefix') . 'update_user_id'
                    ),
                ],
                'validation' => [
                    'module' => 'integer',
                ],
            ]);
        }
    }
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
