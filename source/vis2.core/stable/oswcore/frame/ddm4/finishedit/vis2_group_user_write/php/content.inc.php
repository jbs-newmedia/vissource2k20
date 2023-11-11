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
use VIS2\Core\Manager;

$ar_tool_user = $this->getEditElementStorage(substr($element, 0, -6));
$ar_tool_user_do = $this->getDoEditElementStorage(substr($element, 0, -6));

$element_storage = 'vis2_store_form_data';
$element_current = 'vis2_group_user';
$element_more = 'vis2_user_group';
$group_more = 'vis2_user';

if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') !== '') {
    $vis_user_id = $this->getDoEditElementStorage(
        $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') . 'update_user_id'
    );
    $vis_time = $this->getDoEditElementStorage(
        $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') . 'update_time'
    );
} else {
    $vis_time = time();
    $vis_user_id = $this->getGroupOption('user_id', 'data');
}

if ($this->getFinishElementOption($element, 'group') !== '') {
    $group = $this->getFinishElementOption($element, 'group');
} else {
    $group = $this->getGroupOption('table', 'database');
}

$ar_user = Manager::getUsers();
$tool_groups = Manager::getGroupsByToolId($this->getFinishElementOption($element, 'tool_id'));
foreach ($ar_tool_user_do as $user_id => $flag) {
    if ((!isset($ar_tool_user[$user_id])) || ($ar_tool_user[$user_id] !== $flag)) {
        if ($flag === 1) {
            Manager::addUserGroup(
                $user_id,
                $this->getIndexElementStorage(),
                $this->getFinishElementOption($element, 'tool_id'),
                $vis_time,
                $vis_user_id
            );
            if ($this->getGroupOption('enable_log') === true) {
                if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') !== '') {
                    if (!in_array(
                        $element_current,
                        [
                            $this->getFinishElementOption(
                                $element_storage,
                                'createupdatestatus_prefix'
                            ) . 'update_user_id',
                            $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') . 'update_time',
                        ],
                        true
                    )
                    ) {
                        DDM4_Log::addValue(
                            $group,
                            $element_current,
                            $this->getFinishElementValue($element, 'module'),
                            '#0# ' . $ar_user[$user_id],
                            '#1# ' . $ar_user[$user_id],
                            $this->getEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_user_id'
                            ),
                            $this->getEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_time'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_user_id'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_time'
                            )
                        );
                    }
                } else {
                    DDM4_Log::addValue(
                        $group,
                        $element_current,
                        $this->getFinishElementValue($element, 'module'),
                        '#0# ' . $ar_user[$user_id],
                        '#1# ' . $ar_user[$user_id]
                    );
                }
                DDM4_Log::writeValues(
                    $group,
                    $this->getGroupOption('index', 'database'),
                    $this->getIndexElementStorage(),
                    $this->getGroupOption('connection_log', 'database')
                );
            }
            if ($this->getGroupOption('enable_log') === true) {
                if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') !== '') {
                    if (!in_array(
                        $element_current,
                        [
                            $this->getFinishElementOption(
                                $element_storage,
                                'createupdatestatus_prefix'
                            ) . 'update_user_id',
                            $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') . 'update_time',
                        ],
                        true
                    )
                    ) {
                        DDM4_Log::addValue(
                            $group_more,
                            $element_more,
                            $this->getFinishElementValue($element, 'module'),
                            '#0# ' . $this->getFinishElementOption(
                                $element,
                                'tool_name'
                            ) . ':' . $tool_groups[$this->getIndexElementStorage()] . ' (Über Gruppe geändert)',
                            '#1# ' . $this->getFinishElementOption(
                                $element,
                                'tool_name'
                            ) . ':' . $tool_groups[$this->getIndexElementStorage()] . ' (Über Gruppe geändert)',
                            $this->getEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_user_id'
                            ),
                            $this->getEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_time'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_user_id'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_time'
                            )
                        );
                    }
                } else {
                    DDM4_Log::addValue(
                        $group_more,
                        $element_more,
                        $this->getFinishElementValue($element, 'module'),
                        '#0# ' . $this->getFinishElementOption(
                            $element,
                            'tool_name'
                        ) . ':' . $tool_groups[$this->getIndexElementStorage()] . ' (Über Gruppe geändert)',
                        '#1# ' . $this->getFinishElementOption(
                            $element,
                            'tool_name'
                        ) . ':' . $tool_groups[$this->getIndexElementStorage()] . ' (Über Gruppe geändert)'
                    );
                }
                DDM4_Log::writeValues(
                    $group_more,
                    'user_id',
                    $user_id,
                    $this->getGroupOption('connection_log', 'database')
                );
            }
        } elseif ((isset($ar_tool_user[$user_id])) && ($flag === 0)) {
            Manager::delUserGroup(
                $user_id,
                $this->getIndexElementStorage(),
                $this->getFinishElementOption($element, 'tool_id')
            );
            if ($this->getGroupOption('enable_log') === true) {
                if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') !== '') {
                    if (!in_array(
                        $element_current,
                        [
                            $this->getFinishElementOption(
                                $element_storage,
                                'createupdatestatus_prefix'
                            ) . 'update_user_id',
                            $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') . 'update_time',
                        ],
                        true
                    )
                    ) {
                        DDM4_Log::addValue(
                            $group,
                            $element_current,
                            $this->getFinishElementValue($element, 'module'),
                            '#1# ' . $ar_user[$user_id],
                            '#0# ' . $ar_user[$user_id],
                            $this->getEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_user_id'
                            ),
                            $this->getEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_time'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_user_id'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_time'
                            )
                        );
                    }
                } else {
                    DDM4_Log::addValue(
                        $group,
                        $element_current,
                        $this->getFinishElementValue($element, 'module'),
                        '#1# ' . $ar_user[$user_id],
                        '#0# ' . $ar_user[$user_id]
                    );
                }
                DDM4_Log::writeValues(
                    $group,
                    $this->getGroupOption('index', 'database'),
                    $this->getIndexElementStorage(),
                    $this->getGroupOption('connection_log', 'database')
                );
            }
            if ($this->getGroupOption('enable_log') === true) {
                if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') !== '') {
                    if (!in_array(
                        $element_current,
                        [
                            $this->getFinishElementOption(
                                $element_storage,
                                'createupdatestatus_prefix'
                            ) . 'update_user_id',
                            $this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') . 'update_time',
                        ],
                        true
                    )
                    ) {
                        DDM4_Log::addValue(
                            $group_more,
                            $element_more,
                            $this->getFinishElementValue($element, 'module'),
                            '#1# ' . $this->getFinishElementOption(
                                $element,
                                'tool_name'
                            ) . ':' . $tool_groups[$this->getIndexElementStorage()] . ' (Über Gruppe geändert)',
                            '#0# ' . $this->getFinishElementOption(
                                $element,
                                'tool_name'
                            ) . ':' . $tool_groups[$this->getIndexElementStorage()] . ' (Über Gruppe geändert)',
                            $this->getEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_user_id'
                            ),
                            $this->getEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_time'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_user_id'
                            ),
                            $this->getDoEditElementStorage(
                                $this->getFinishElementOption(
                                    $element_storage,
                                    'createupdatestatus_prefix'
                                ) . 'update_time'
                            )
                        );
                    }
                } else {
                    DDM4_Log::addValue(
                        $group_more,
                        $element_more,
                        $this->getFinishElementValue($element, 'module'),
                        '#1# ' . $this->getFinishElementOption(
                            $element,
                            'tool_name'
                        ) . ':' . $tool_groups[$this->getIndexElementStorage()] . ' (Über Gruppe geändert)',
                        '#0# ' . $this->getFinishElementOption(
                            $element,
                            'tool_name'
                        ) . ':' . $tool_groups[$this->getIndexElementStorage()] . ' (Über Gruppe geändert)'
                    );
                }
                DDM4_Log::writeValues(
                    $group_more,
                    'user_id',
                    $user_id,
                    $this->getGroupOption('connection_log', 'database')
                );
            }
        }
    }
}
