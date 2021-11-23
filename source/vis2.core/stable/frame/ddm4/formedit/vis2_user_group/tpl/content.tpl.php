<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

?>

<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementValue($element, 'title')) ?><?php if ($this->getEditElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getEditElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>

		<?php /* read only */ ?>

		<?php $ar_user_group=$this->getEditElementStorage($element); ?>

		<?php if ($this->getEditElementOption($element, 'manager')===true): ?>

			<br/>

			<?php foreach (\VIS2\Core\Manager::getTools() as $tool_id=>$tool_name): ?>

				<strong><?php echo \osWFrame\Core\HTML::outputString($tool_name) ?></strong>

				<?php foreach (\VIS2\Core\Manager::getGroupsByToolId($tool_id) as $group_id=>$group_name): ?>

					<div class="custom-checkbox">
						<?php if ((isset($ar_user_group[$tool_id])&&(isset($ar_user_group[$tool_id][$group_id])))&&($ar_user_group[$tool_id][$group_id]==1)): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($group_name) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($group_name) ?><?php endif ?>
					</div>

				<?php endforeach ?>

				<p></p>

			<?php endforeach ?>

		<?php else: ?>

			<?php foreach (\VIS2\Core\Manager::getGroupsByToolId($this->getEditElementOption($element, 'tool_id')) as $group_id=>$group_name): ?>

				<div class="custom-checkbox">
					<?php if ((isset($ar_user_group[$this->getEditElementOption($element, 'tool_id')])&&(isset($ar_user_group[$this->getEditElementOption($element, 'tool_id')][$group_id])))&&($ar_user_group[$this->getEditElementOption($element, 'tool_id')][$group_id]==1)): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($group_name) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($group_name) ?><?php endif ?>
				</div>

			<?php endforeach ?>

		<?php endif ?>

	<?php else: ?>

		<?php /* input */ ?>

		<?php $ar_user_group=$this->getEditElementStorage($element); ?>

		<?php if ($this->getEditElementOption($element, 'manager')===true): ?>

			<br/>

			<?php foreach (\VIS2\Core\Manager::getTools() as $tool_id=>$tool_name): ?>

				<strong><?php echo \osWFrame\Core\HTML::outputString($tool_name) ?></strong>

				<?php foreach (\VIS2\Core\Manager::getGroupsByToolId($tool_id) as $group_id=>$group_name): ?>

					<div class="form-check">
						<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$group_id, '1', ((isset($ar_user_group[$tool_id])&&(isset($ar_user_group[$tool_id][$group_id]))&&($ar_user_group[$tool_id][$group_id]==1))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($group_name).'"', 'input_class'=>'form-check-input']) ?>
						<label class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$group_id ?>0"><?php echo \osWFrame\Core\HTML::outputString($group_name) ?></label>
					</div>

				<?php endforeach ?>

				<p></p>

			<?php endforeach ?>

		<?php else: ?>

			<?php foreach (\VIS2\Core\Manager::getGroupsByToolId($this->getEditElementOption($element, 'tool_id')) as $group_id=>$group_name): ?>

				<div class="form-check">
					<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$group_id, '1', ((isset($ar_user_group[$this->getEditElementOption($element, 'tool_id')])&&(isset($ar_user_group[$this->getEditElementOption($element, 'tool_id')][$group_id]))&&($ar_user_group[$this->getEditElementOption($element, 'tool_id')][$group_id]==1))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($group_name).'"', 'input_class'=>'form-check-input']) ?>
					<label class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$group_id ?>0"><?php echo \osWFrame\Core\HTML::outputString($group_name) ?></label>
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