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

use osWFrame\Core\HTML;
use VIS2\Core\Manager;

?>

<div class="form-group ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>">

    <?php /* label */ ?>
    <label class="form-label"
           for="<?php echo $element ?>"><?php echo HTML::outputString(
               $this->getDeleteElementValue($element, 'title')
           ) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

    <?php /* read only */ ?>

    <?php $ar_user_group = $this->getDeleteElementStorage($element); ?>

    <?php if ($this->getDeleteElementOption($element, 'manager') === true): ?>

        <br/>

        <?php foreach (Manager::getTools() as $tool_id => $tool_name): ?>

            <strong><?php echo HTML::outputString($tool_name) ?></strong>

            <?php foreach (Manager::getGroupsByToolId($tool_id) as $group_id => $group_name): ?>

                <div class="custom-checkbox">
                    <?php if ((isset($ar_user_group[$tool_id]) && (isset($ar_user_group[$tool_id][$group_id]))) && ($ar_user_group[$tool_id][$group_id] === 1)): ?><?php echo $this->getGroupMessage(
                        'log_char_true'
                    ) . ' ' . HTML::outputString($group_name) ?><?php else: ?><?php echo $this->getGroupMessage(
                        'log_char_false'
                    ) . ' ' . HTML::outputString($group_name) ?><?php endif ?>
                </div>

            <?php endforeach ?>

            <p></p>

        <?php endforeach ?>

    <?php else: ?>

        <?php foreach (Manager::getGroupsByToolId(
            $this->getDeleteElementOption($element, 'tool_id')
        ) as $group_id => $group_name): ?>

            <div class="custom-checkbox">
                <?php if ((isset(
                    $ar_user_group[$this->getDeleteElementOption(
                        $element,
                        'tool_id'
                    )]
                ) && (isset(
                    $ar_user_group[$this->getDeleteElementOption(
                        $element,
                        'tool_id'
                    )][$group_id]
                ))) && ($ar_user_group[$this->getDeleteElementOption($element, 'tool_id')][$group_id] === 1)
                ): ?><?php echo $this->getGroupMessage('log_char_true') . ' ' . HTML::outputString(
                    $group_name
                ) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false') . ' ' . HTML::outputString(
                    $group_name
                ) ?><?php endif ?>
            </div>

        <?php endforeach ?>

    <?php endif ?>

    <?php /* error */ ?>
    <?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
        <div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
    <?php endif ?>

    <?php /* notice */ ?>
    <?php if ($this->getDeleteElementOption($element, 'notice') !== ''): ?>
        <div
            class="text-info"><?php echo HTML::outputString($this->getDeleteElementOption($element, 'notice')) ?></div>
    <?php endif ?>

    <?php /* buttons */ ?>
    <?php if ($this->getDeleteElementOption($element, 'buttons') !== ''): ?>
        <div>
            <?php echo implode(' ', $this->getDeleteElementOption($element, 'buttons')) ?>
        </div>
    <?php endif ?>

</div>
