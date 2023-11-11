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
$element_current = 'vis2_user_group';
$element_more = 'vis2_group_user';
$group_more = 'vis2_group';

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
if ($this->getFinishElementOption($element, 'manager') === true) {
    foreach (Manager::getTools() as $tool_id => $tool_name) {
        $tool_groups = Manager::getGroupsByToolId($tool_id);
        if (isset($ar_tool_user_do[$tool_id])) {
            foreach ($ar_tool_user_do[$tool_id] as $group_id => $flag) {
                if (((!isset($ar_tool_user[$tool_id])) || (!isset($ar_tool_user[$tool_id][$group_id]))) || ($ar_tool_user[$tool_id][$group_id] !== $flag)) {
                    if ($flag === 1) {
                        Manager::addUserGroup(
                            $this->getIndexElementStorage(),
                            $group_id,
                            $tool_id,
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
                                        $this->getFinishElementOption(
                                            $element_storage,
                                            'createupdatestatus_prefix'
                                        ) . 'update_time',
                                    ],
                                    true
                                )
                                ) {
                                    DDM4_Log::addValue(
                                        $group,
                                        $element_current,
                                        $this->getFinishElementValue($element, 'module'),
                                        '#0# ' . $tool_name . ':' . $tool_groups[$group_id],
                                        '#1# ' . $tool_name . ':' . $tool_groups[$group_id],
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
                                    '#0# ' . $tool_name . ':' . $tool_groups[$group_id],
                                    '#1# ' . $tool_name . ':' . $tool_groups[$group_id]
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
                                        $this->getFinishElementOption(
                                            $element_storage,
                                            'createupdatestatus_prefix'
                                        ) . 'update_time',
                                    ],
                                    true
                                )
                                ) {
                                    DDM4_Log::addValue(
                                        $group_more,
                                        $element_more,
                                        $this->getFinishElementValue($element, 'module'),
                                        '#0# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
                                        '#1# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
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
                                    '#0# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
                                    '#1# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)'
                                );
                            }
                            DDM4_Log::writeValues(
                                $group_more,
                                'group_id',
                                $group_id,
                                $this->getGroupOption('connection_log', 'database')
                            );
                        }
                    } elseif (((isset($ar_tool_user[$tool_id])) && (isset($ar_tool_user[$tool_id][$group_id]))) && ($flag === 0)) {
                        Manager::delUserGroup($this->getIndexElementStorage(), $group_id, $tool_id);
                        if ($this->getGroupOption('enable_log') === true) {
                            if ($this->getFinishElementOption($element_storage, 'createupdatestatus_prefix') !== '') {
                                if (!in_array(
                                    $element_current,
                                    [
                                        $this->getFinishElementOption(
                                            $element_storage,
                                            'createupdatestatus_prefix'
                                        ) . 'update_user_id',
                                        $this->getFinishElementOption(
                                            $element_storage,
                                            'createupdatestatus_prefix'
                                        ) . 'update_time',
                                    ],
                                    true
                                )
                                ) {
                                    DDM4_Log::addValue(
                                        $group,
                                        $element_current,
                                        $this->getFinishElementValue($element, 'module'),
                                        '#1# ' . $tool_name . ':' . $tool_groups[$group_id],
                                        '#0# ' . $tool_name . ':' . $tool_groups[$group_id],
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
                                    '#1# ' . $tool_name . ':' . $tool_groups[$group_id],
                                    '#0# ' . $tool_name . ':' . $tool_groups[$group_id]
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
                                        $this->getFinishElementOption(
                                            $element_storage,
                                            'createupdatestatus_prefix'
                                        ) . 'update_time',
                                    ],
                                    true
                                )
                                ) {
                                    DDM4_Log::addValue(
                                        $group_more,
                                        $element_more,
                                        $this->getFinishElementValue($element, 'module'),
                                        '#1# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
                                        '#0# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
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
                                    '#1# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
                                    '#0# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)'
                                );
                            }
                            DDM4_Log::writeValues(
                                $group_more,
                                'group_id',
                                $group_id,
                                $this->getGroupOption('connection_log', 'database')
                            );
                        }
                    }
                }
            }
        }
    }
} else {
    $tool_groups = Manager::getGroupsByToolId($this->getFinishElementOption($element, 'tool_id'));
    if (isset($ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')])) {
        foreach ($ar_tool_user_do[$this->getFinishElementOption($element, 'tool_id')] as $group_id => $flag) {
            if (((!isset(
                $ar_tool_user[$this->getFinishElementOption(
                    $element,
                    'tool_id'
                )]
            )) || (!isset(
                $ar_tool_user[$this->getFinishElementOption(
                    $element,
                    'tool_id'
                )][$group_id]
            ))) || ($ar_tool_user[$this->getFinishElementOption($element, 'tool_id')][$group_id] !== $flag)
            ) {
                if ($flag === 1) {
                    Manager::addUserGroup(
                        $this->getIndexElementStorage(),
                        $group_id,
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
                                    $this->getFinishElementOption(
                                        $element_storage,
                                        'createupdatestatus_prefix'
                                    ) . 'update_time',
                                ],
                                true
                            )
                            ) {
                                DDM4_Log::addValue(
                                    $group,
                                    $element_current,
                                    $this->getFinishElementValue($element, 'module'),
                                    '#0# ' . $this->getFinishElementOption(
                                        $element,
                                        'tool_name'
                                    ) . ':' . $tool_groups[$group_id],
                                    '#1# ' . $this->getFinishElementOption(
                                        $element,
                                        'tool_name'
                                    ) . ':' . $tool_groups[$group_id],
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
                                '#0# ' . $this->getFinishElementOption($element, 'tool_name') . ':' . $tool_groups[$group_id],
                                '#1# ' . $this->getFinishElementOption($element, 'tool_name') . ':' . $tool_groups[$group_id]
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
                                    $this->getFinishElementOption(
                                        $element_storage,
                                        'createupdatestatus_prefix'
                                    ) . 'update_time',
                                ],
                                true
                            )
                            ) {
                                DDM4_Log::addValue(
                                    $group_more,
                                    $element_more,
                                    $this->getFinishElementValue($element, 'module'),
                                    '#0# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
                                    '#1# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
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
                                '#0# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
                                '#1# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)'
                            );
                        }
                        DDM4_Log::writeValues(
                            $group_more,
                            'group_id',
                            $group_id,
                            $this->getGroupOption('connection_log', 'database')
                        );
                    }
                } elseif (((isset(
                    $ar_tool_user[$this->getFinishElementOption(
                        $element,
                        'tool_id'
                    )]
                )) && (isset(
                    $ar_tool_user[$this->getFinishElementOption(
                        $element,
                        'tool_id'
                    )][$group_id]
                ))) && ($flag === 0)
                ) {
                    Manager::delUserGroup(
                        $this->getIndexElementStorage(),
                        $group_id,
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
                                    $this->getFinishElementOption(
                                        $element_storage,
                                        'createupdatestatus_prefix'
                                    ) . 'update_time',
                                ],
                                true
                            )
                            ) {
                                DDM4_Log::addValue(
                                    $group,
                                    $element_current,
                                    $this->getFinishElementValue($element, 'module'),
                                    '#1# ' . $this->getFinishElementOption(
                                        $element,
                                        'tool_name'
                                    ) . ':' . $tool_groups[$group_id],
                                    '#0# ' . $this->getFinishElementOption(
                                        $element,
                                        'tool_name'
                                    ) . ':' . $tool_groups[$group_id],
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
                                '#1# ' . $this->getFinishElementOption($element, 'tool_name') . ':' . $tool_groups[$group_id],
                                '#0# ' . $this->getFinishElementOption($element, 'tool_name') . ':' . $tool_groups[$group_id]
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
                                    $this->getFinishElementOption(
                                        $element_storage,
                                        'createupdatestatus_prefix'
                                    ) . 'update_time',
                                ],
                                true
                            )
                            ) {
                                DDM4_Log::addValue(
                                    $group_more,
                                    $element_more,
                                    $this->getFinishElementValue($element, 'module'),
                                    '#1# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
                                    '#0# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
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
                                '#1# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)',
                                '#0# ' . $ar_user[$this->getIndexElementStorage()] . ' (Über Benutzer geändert)'
                            );
                        }
                        DDM4_Log::writeValues(
                            $group_more,
                            'group_id',
                            $group_id,
                            $this->getGroupOption('connection_log', 'database')
                        );
                    }
                }
            }
        }
    }
}
