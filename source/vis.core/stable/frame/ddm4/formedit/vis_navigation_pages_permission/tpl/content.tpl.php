<?php

/**
 * This file is part of the VIS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS
 * @link https://oswframe.com
 * @license MIT License
 */

?>

<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">

	<?php /* label */ ?>

	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementValue($element, 'title')) ?><?php if ($this->getEditElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getEditElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>

		<?php $ar_navigation_permission=$this->getEditElementStorage($element) ?>

		<?php $permission_list=\VIS\Core\Manager::getPermissionTextList($this->getEditElementOption($element, 'tool_id')) ?>

		<?php if (!empty($permission_list)): ?>

			<?php foreach ($permission_list as $permission_flag=>$permission_text): ?>

				<div class="custom-checkbox">
					<?php if (isset($ar_navigation_permission[$permission_flag])&&($ar_navigation_permission[$permission_flag]==1)): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($permission_text) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($permission_text) ?><?php endif ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_'.$permission_flag, 0) ?>
				</div>

			<?php endforeach ?>

		<?php endif ?>

	<?php else: ?>

		<?php /* input */ ?>

		<?php $ar_navigation_permission=$this->getEditElementStorage($element) ?>

		<?php $permission_list=\VIS\Core\Manager::getPermissionTextList($this->getEditElementOption($element, 'tool_id')) ?>

		<?php if (!empty($permission_list)): ?>

			<?php foreach ($permission_list as $permission_flag=>$permission_text): ?>

				<div class="form-check">
					<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$permission_flag, '1', ((isset($ar_navigation_permission[$permission_flag])&&($ar_navigation_permission[$permission_flag]==1))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($permission_text).'"', 'input_class'=>'form-check-input']) ?>
					<label class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$permission_flag ?>0"><?php echo \osWFrame\Core\HTML::outputString($permission_text) ?></label>
				</div>

			<?php endforeach ?>

		<?php endif ?>

	<?php endif ?>

	<?php /* error */ ?>

	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
	<?php endif ?>

	<?php /* notice */ ?>

	<?php if ($this->getEditElementOption($element, 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'notice')) ?></div>
	<?php endif ?>

	<?php /* buttons */ ?>

	<?php if ($this->getEditElementOption($element, 'buttons')!=''): ?>
		<div>
			<?php echo implode(' ', $this->getEditElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>

</div>