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

<div class="form-group ddm_element_<?php echo $this->getAddElementValue($element, 'id') ?>">

	<?php /* label */ ?>

	<label for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getAddElementValue($element, 'title')) ?><?php if ($this->getAddElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getAddElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>

		<?php $ar_user_group=$this->getAddElementStorage($element); ?>

		<?php if ($this->getAddElementOption($element, 'manager')===true): ?>

			<br/>

			<?php foreach (\VIS2\Core\Manager::getTools() as $tool_id=>$tool_name): ?>

				<strong><?php echo \osWFrame\Core\HTML::outputString($tool_name) ?></strong>

				<?php foreach (\VIS2\Core\Manager::getGroupsByToolId($tool_id) as $group_id=>$group_name): ?>

					<div class="custom-checkbox">
						<?php if ((isset($ar_user_group[$tool_id])&&(isset($ar_user_group[$tool_id][$group_id])))&&($ar_user_group[$tool_id][$group_id]==1)): ?><?php echo '#1# '.\osWFrame\Core\HTML::outputString($group_name) ?><?php else: ?><?php echo '#0# '.\osWFrame\Core\HTML::outputString($group_name) ?><?php endif ?>
					</div>

				<?php endforeach ?>

				<p></p>

			<?php endforeach ?>

		<?php else: ?>

			<?php foreach (\VIS2\Core\Manager::getGroupsByToolId($this->getAddElementOption($element, 'tool_id')) as $group_id=>$group_name): ?>

				<div class="custom-checkbox">
					<?php if ((isset($ar_user_group[$this->getAddElementOption($element, 'tool_id')])&&(isset($ar_user_group[$this->getAddElementOption($element, 'tool_id')][$group_id])))&&($ar_user_group[$this->getAddElementOption($element, 'tool_id')][$group_id]==1)): ?><?php echo '#1# '.\osWFrame\Core\HTML::outputString($group_name) ?><?php else: ?><?php echo '#0# '.\osWFrame\Core\HTML::outputString($group_name) ?><?php endif ?>
				</div>

			<?php endforeach ?>

		<?php endif ?>

	<?php else: ?>

		<?php /* input */ ?>

		<?php $ar_user_group=$this->getAddElementStorage($element); ?>

		<?php if ($this->getAddElementOption($element, 'manager')===true): ?>

			<?php foreach (\VIS2\Core\Manager::getTools() as $tool_id=>$tool_name): ?>

				<strong><?php echo \osWFrame\Core\HTML::outputString($tool_name) ?></strong>

				<?php foreach (\VIS2\Core\Manager::getGroupsByToolId($tool_id) as $group_id=>$group_name): ?>

					<div class="custom-control custom-checkbox">
						<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$group_id, '1', ((isset($ar_user_group[$tool_id])&&(isset($ar_user_group[$tool_id][$group_id]))&&($ar_user_group[$tool_id][$group_id]==1))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($group_name).'"', 'input_class'=>'custom-control-input']) ?>
						<label class="custom-control-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$group_id ?>0"><?php echo \osWFrame\Core\HTML::outputString($group_name) ?></label>
					</div>

				<?php endforeach ?>

				<p></p>

			<?php endforeach ?>

		<?php else: ?>

			<?php foreach (\VIS2\Core\Manager::getGroupsByToolId($this->getAddElementOption($element, 'tool_id')) as $group_id=>$group_name): ?>

				<div class="custom-control custom-checkbox">
					<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$group_id, '1', ((isset($ar_user_group[$this->getAddElementOption($element, 'tool_id')])&&(isset($ar_user_group[$this->getAddElementOption($element, 'tool_id')][$group_id]))&&($ar_user_group[$this->getAddElementOption($element, 'tool_id')][$group_id]==1))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($group_name).'"', 'input_class'=>'custom-control-input']) ?>
					<label class="custom-control-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$group_id ?>0"><?php echo \osWFrame\Core\HTML::outputString($group_name) ?></label>
				</div>

			<?php endforeach ?>

		<?php endif ?>

	<?php endif ?>

	<?php /* error */ ?>

	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)!==null): ?>

		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>

	<?php endif ?>

	<?php /* notice */ ?>

	<?php if ($this->getAddElementOption($element, 'notice')!=''): ?>

		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getAddElementOption($element, 'notice')) ?></div>

	<?php endif ?>

	<?php /* buttons */ ?>

	<?php if ($this->getAddElementOption($element, 'buttons')!=''): ?>

		<div><?php echo implode(' ', $this->getAddElementOption($element, 'buttons')) ?></div>

	<?php endif ?>

</div>