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
use osWFrame\Core\HTML;
use VIS2\Core\Manager;

?>

<div class="form-group ddm_element_<?php echo $this->getAddElementValue($element, 'id') ?>">

    <?php /* label */ ?>
    <label class="form-label"
           for="<?php echo $element ?>"><?php echo HTML::outputString(
               $this->getAddElementValue($element, 'title')
           ) ?><?php if ($this->getAddElementOption($element, 'required') === true): ?><?php echo $this->getGroupMessage(
               'form_title_required_icon'
           ) ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

    <?php if ($this->getAddElementOption($element, 'read_only') === true): ?>

        <?php /* read only */ ?>

        <?php $ar_tool_user = $this->getAddElementStorage($element); ?>

        <?php $users = Manager::getUsers(); ?>

        <?php $i = 0;
        foreach ($users as $user_id => $user_name):$i++; ?><?php if ((isset($ar_tool_user[$user_id])) && ($ar_tool_user[$user_id] === 1)): ?>
            <div class="custom-checkbox">
                <?php if ((isset($ar_tool_user[$user_id])) && ($ar_tool_user[$user_id] === 1)): ?><?php echo $this->getGroupMessage(
                    'log_char_true'
                ) . ' ' . HTML::outputString($user_name) ?><?php else: ?><?php echo $this->getGroupMessage(
                    'log_char_false'
                ) . ' ' . HTML::outputString($user_name) ?><?php endif ?>
            </div>
        <?php endif ?><?php endforeach ?>

    <?php else: ?>

        <?php /* input */ ?>

        <?php $ar_tool_user = $this->getAddElementStorage($element); ?>

        <?php $users = Manager::getUsers(); ?>

        <?php if (count($users) > (int)($this->getAddElementOption($element, 'search_mod_counter'))): ?>

            <div class="input-group">
                <?php echo $this->getTemplate()->Form()->drawTextField($element . '_search', '', [
                            'input_class' => 'form-control form-control-rborder',
                            'input_parameter' => 'placeholder="Suchen ..." oninput="ddm4_function_' . $element . '();"',
                        ]); ?>
                <button type="button" class="btn bg-transparent" style="margin-left: -40px; z-index: 100; border:0;"
                        onclick="$('#<?php echo $element ?>_search').val('');ddm4_function_<?php echo $element ?>();">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <br/>

        <?php endif ?>

        <?php foreach ($users as $user_id => $user_name): ?>

            <div class="form-check">
                <?php echo $this->getTemplate()->Form()->drawCheckBoxField(
                    $element . '_' . $user_id,
                    '1',
                    ((isset($ar_tool_user[$user_id]) && ($ar_tool_user[$user_id] === 1)) ? 1 : 0),
                    [
                        'input_parameter' => 'title="' . HTML::outputString($user_name) . '"',
                        'input_class' => 'form-check-input',
                    ]
                ) ?>
                <label
                    class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage(
                        $element
                    ) !== null
                    ): ?> text-danger<?php endif ?>"
                    for="<?php echo $element . '_' . $user_id ?>0"><?php echo HTML::outputString($user_name) ?></label>
            </div>

        <?php endforeach ?>

    <?php endif ?>

    <?php /* error */ ?>
    <?php if ($this->getTemplate()->Form()->getErrorMessage($element) !== null): ?>
        <div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
    <?php endif ?>

    <?php /* notice */ ?>
    <?php if ($this->getAddElementOption($element, 'notice') !== ''): ?>
        <div
            class="text-info"><?php echo HTML::outputString($this->getAddElementOption($element, 'notice')) ?></div>
    <?php endif ?>

    <?php /* buttons */ ?>
    <?php if ($this->getAddElementOption($element, 'buttons') !== ''): ?>
        <div>
            <?php echo implode(' ', $this->getAddElementOption($element, 'buttons')) ?>
        </div>
    <?php endif ?>

</div>
