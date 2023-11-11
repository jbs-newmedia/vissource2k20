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

        <?php $ar_navigation_permission = $this->getAddElementStorage($element) ?>

        <?php $permission_list = Manager::getPermissionTextList($this->getAddElementOption($element, 'tool_id')) ?>

        <?php if (!empty($permission_list)): ?>

            <?php foreach ($permission_list as $permission_flag => $permission_text): ?>

                <div class="custom-checkbox">
                    <?php if (isset($ar_navigation_permission[$permission_flag]) && ($ar_navigation_permission[$permission_flag] === 1)): ?><?php echo $this->getGroupMessage(
                        'log_char_true'
                    ) . ' ' . HTML::outputString($permission_text) ?><?php else: ?><?php echo $this->getGroupMessage(
                        'log_char_false'
                    ) . ' ' . HTML::outputString($permission_text) ?><?php endif ?><?php echo $this->getTemplate(
                    )->Form()->drawHiddenField(
                        $element . '_' . $permission_flag,
                        $ar_navigation_permission[$permission_flag]
                    ) ?>
                </div>

            <?php endforeach ?>

        <?php endif ?>

    <?php else: ?>

        <?php /* input */ ?>

        <?php $ar_navigation_permission = $this->getAddElementStorage($element) ?>

        <?php $permission_list = Manager::getPermissionTextList($this->getAddElementOption($element, 'tool_id')) ?>

        <?php if ($permission_list !== []): ?>

            <?php foreach ($permission_list as $permission_flag => $permission_text): ?>

                <div class="form-check">
                    <?php echo $this->getTemplate()->Form()->drawCheckBoxField(
                        $element . '_' . $permission_flag,
                        '1',
                        ((isset($ar_navigation_permission[$permission_flag]) && ($ar_navigation_permission[$permission_flag] === 1)) ? 1 : 0),
                        [
                            'input_parameter' => 'title="' . HTML::outputString($permission_text) . '"',
                            'input_class' => 'form-check-input',
                        ]
                    ) ?>
                    <label
                        class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage(
                            $element
                        ) !== null
                        ): ?> text-danger<?php endif ?>"
                        for="<?php echo $element . '_' . $permission_flag ?>0"><?php echo HTML::outputString(
                            $permission_text
                        ) ?></label>
                </div>

            <?php endforeach ?>

        <?php endif ?>

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
        <div><?php echo implode(' ', $this->getAddElementOption($element, 'buttons')) ?></div>
    <?php endif ?>

</div>
