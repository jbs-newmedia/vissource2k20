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

    <?php $ar_tool_user = $this->getDeleteElementStorage($element); ?>

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
