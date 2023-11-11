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

// build selector
$ddm_search_case_array = '';
$ddm_selector_array = $this->getGroupOption('selector', 'database');
if (($ddm_selector_array !== '') && ($ddm_selector_array !== [])) {
    $ar_values = [];
    foreach ($ddm_selector_array as $key => $value) {
        if (is_int($value) === true) {
            $ar_values[] = $key . '=' . $value;
        } else {
            $ar_values[] = $key . '=\'' . $value . '\'';
        }
    }
    $ddm_search_case_array = 'AND (' . implode(' AND ', $ar_values) . ')';
}

$QdeleteData = $this::getConnection($this->getGroupOption('connection', 'database'));
$QdeleteData->prepare('DELETE FROM :table: WHERE :name_index:=:value_index: :search_selector:');
$QdeleteData->bindTable(':table:', $this->getGroupOption('table', 'database'));
$QdeleteData->bindRaw(':name_index:', $this->getGroupOption('index', 'database'));
if ($this->getGroupOption('db_index_type', 'database') === 'string') {
    $QdeleteData->bindString(':value_index:', $this->getIndexElementStorage());
} else {
    $QdeleteData->bindInt(':value_index:', (int)($this->getIndexElementStorage()));
}
$QdeleteData->bindRaw(':search_selector:', $ddm_search_case_array);
$QdeleteData->execute();

MessageStack::addMessage('ddm4_' . $this->getName(), 'success', [
    'msg' => $this->getGroupMessage('delete_success_title'),
]);
