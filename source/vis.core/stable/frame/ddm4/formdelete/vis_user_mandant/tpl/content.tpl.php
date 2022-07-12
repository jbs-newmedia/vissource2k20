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

<div class="form-group ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementValue($element, 'title')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php /* read only */ ?>

	<?php $ar_user_mandant=$this->getDeleteElementStorage($element); ?>

	<?php if ($this->getDeleteElementOption($element, 'manager')===true): ?>

		<br/>

		<?php foreach (\VIS\Core\Manager::getTools() as $tool_id=>$tool_name): ?>

			<?php $tool_details=\VIS\Core\Manager::getToolDetails($tool_id) ?>

			<?php if ($tool_details['tool_use_mandant']=='1'): ?>

				<strong><?php echo \osWFrame\Core\HTML::outputString($tool_name) ?></strong>

				<?php foreach (\VIS\Core\Manager::getMandantenByToolId($tool_id, true) as $group_id=>$group_name): ?>

					<div class="custom-checkbox">
						<?php if ((isset($ar_user_mandant[$tool_id])&&(isset($ar_user_mandant[$tool_id][$group_id])))&&($ar_user_mandant[$tool_id][$group_id]==1)): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($group_name) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($group_name) ?><?php endif ?>
					</div>

				<?php endforeach ?>

				<p></p>

			<?php endif ?>

		<?php endforeach ?>

	<?php else: ?>

		<?php foreach (\VIS\Core\Manager::getMandantenByToolId($this->getDeleteElementOption($element, 'tool_id'), true) as $group_id=>$group_name): ?>

			<div class="custom-checkbox">
				<?php if ((isset($ar_user_mandant[$this->getDeleteElementOption($element, 'tool_id')])&&(isset($ar_user_mandant[$this->getDeleteElementOption($element, 'tool_id')][$group_id])))&&($ar_user_mandant[$this->getDeleteElementOption($element, 'tool_id')][$group_id]==1)): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($group_name) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($group_name) ?><?php endif ?>
			</div>

		<?php endforeach ?>

	<?php endif ?>

	<?php /* error */ ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
	<?php endif ?>

	<?php /* notice */ ?>
	<?php if ($this->getDeleteElementOption($element, 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementOption($element, 'notice')) ?></div>
	<?php endif ?>

	<?php /* buttons */ ?>
	<?php if ($this->getDeleteElementOption($element, 'buttons')!=''): ?>
		<div>
			<?php echo implode(' ', $this->getDeleteElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>

</div>