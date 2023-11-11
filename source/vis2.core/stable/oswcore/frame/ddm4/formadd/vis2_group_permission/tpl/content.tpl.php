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

if (!function_exists('vis2_manager_group_permission')) {
    function vis2_manager_group_permission($object, $element, $navigation_element, $ar_permission, $ro = false): void
    {
        ?>
        <div style="padding-left:<?php echo (($navigation_element['info']['navigation_level']) * 20) ?>px">
            <strong><?php echo HTML::outputString($navigation_element['info']['navigation_title']) ?></strong>

            <?php foreach ($navigation_element['info']['permission'] as $flag): ?>

                <?php if ($ro === true): ?>
                    <div class="custom-checkbox">
                        <?php if (isset($ar_permission[$navigation_element['info']['page_name_intern']][$flag])): ?><?php echo $object->getGroupMessage(
                            'log_char_true'
                        ) . ' ' . HTML::outputString(
                            Manager::getPermissionText($flag, $object->getAddElementOption($element, 'tool_id'))
                        ) ?><?php else: ?><?php echo $object->getGroupMessage(
                            'log_char_false'
                        ) . ' ' . HTML::outputString(
                            Manager::getPermissionText($flag, $object->getAddElementOption($element, 'tool_id'))
                        ) ?><?php endif ?>

                        <?php echo $object->getTemplate()->Form()->drawHiddenField(
                            'page_' . $navigation_element['info']['page_name_intern'] . '_' . $flag,
                            0
                        ) ?>
                    </div>
                <?php else: ?>
                    <div class="form-check">
                        <?php echo $object->getTemplate()->Form()->drawCheckBoxField(
                            'page_' . $navigation_element['info']['page_name_intern'] . '_' . $flag,
                            '1',
                            (isset($ar_permission[$navigation_element['info']['page_name_intern']][$flag]) ? $ar_permission[$navigation_element['info']['page_name_intern']][$flag] : 0),
                            [
                                'input_class' => 'form-check-input',
                            ]
                        ) ?>
                        <label
                            class="form-check-label<?php if ($object->getTemplate()->Form()->getErrorMessage(
                                $element
                            ) !== null
                            ): ?> text-danger<?php endif ?>"
                            for="<?php echo 'page_' . $navigation_element['info']['page_name_intern'] . '_' . $flag ?>0"><?php echo HTML::outputString(
                                Manager::getPermissionText($flag, $object->getAddElementOption($element, 'tool_id'))
                            ) ?></label>
                    </div>
                <?php endif ?>

            <?php endforeach ?>


            <?php if (count($navigation_element['links']) > 0): ?>

                <?php foreach ($navigation_element['links'] as $_navigation_element): ?>

                    <?php vis2_manager_group_permission($object, $element, $_navigation_element, $ar_permission, $ro) ?>

                <?php endforeach ?>

            <?php endif ?>
        </div>
        <?php
    }
}

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

        <?php $ar_permission = $this->getAddElementStorage($element) ?>

        <?php foreach (Manager::getNavigationReal(
            0,
            $this->getGroupOption('navigation_level'),
            $this->getAddElementOption($element, 'tool_id')
        ) as $navigation_element): ?>

            <?php vis2_manager_group_permission($this, $element, $navigation_element, $ar_permission, true) ?>

        <?php endforeach ?>

    <?php else: ?>

        <?php /* input */ ?>

        <?php $ar_permission = $this->getAddElementStorage($element) ?>

        <?php foreach (Manager::getNavigationReal(
            0,
            $this->getGroupOption('navigation_level'),
            $this->getAddElementOption($element, 'tool_id')
        ) as $navigation_element): ?>

            <?php vis2_manager_group_permission($this, $element, $navigation_element, $ar_permission) ?>

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
