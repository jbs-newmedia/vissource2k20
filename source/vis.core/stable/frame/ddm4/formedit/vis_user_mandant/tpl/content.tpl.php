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

		<?php /* read only */ ?>

		<?php $ar_user_mandant=$this->getEditElementStorage($element); ?>

		<?php if ($this->getEditElementOption($element, 'manager')===true): ?>

			<br/>

			<?php foreach (\VIS\Core\Manager::getTools() as $tool_id=>$tool_name): ?>

				<?php $tool_details=\VIS\Core\Manager::getToolDetails($tool_id) ?>

				<?php if ($tool_details['tool_use_mandant']=='1'): ?>

					<strong><?php echo \osWFrame\Core\HTML::outputString($tool_name) ?></strong>

					<?php foreach (\VIS\Core\Manager::getMandantenByToolId($tool_id, true) as $mandant_id=>$mandant_name): ?>

						<div class="custom-checkbox">
							<?php if ((isset($ar_user_mandant[$tool_id])&&(isset($ar_user_mandant[$tool_id][$mandant_id])))&&($ar_user_mandant[$tool_id][$mandant_id]==1)): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($mandant_name) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($mandant_name) ?><?php endif ?>
						</div>

					<?php endforeach ?>

					<p></p>

				<?php endif ?>

			<?php endforeach ?>

		<?php else: ?>

			<?php foreach (\VIS\Core\Manager::getMandantenByToolId($this->getEditElementOption($element, 'tool_id'), true) as $mandant_id=>$mandant_name): ?>

				<div class="custom-checkbox">
					<?php if ((isset($ar_user_mandant[$this->getEditElementOption($element, 'tool_id')])&&(isset($ar_user_mandant[$this->getEditElementOption($element, 'tool_id')][$mandant_id])))&&($ar_user_mandant[$this->getEditElementOption($element, 'tool_id')][$mandant_id]==1)): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($mandant_name) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($mandant_name) ?><?php endif ?>
				</div>

			<?php endforeach ?>

		<?php endif ?>

	<?php else: ?>

		<?php /* input */ ?>

		<?php $ar_user_mandant=$this->getEditElementStorage($element); ?>

		<?php if ($this->getEditElementOption($element, 'manager')===true): ?>

			<br/>

			<?php foreach (\VIS\Core\Manager::getTools() as $tool_id=>$tool_name): ?>

				<?php $tool_details=\VIS\Core\Manager::getToolDetails($tool_id) ?>

				<?php if ($tool_details['tool_use_mandant']=='1'): ?>

					<strong><?php echo \osWFrame\Core\HTML::outputString($tool_name) ?></strong>

					<?php foreach (\VIS\Core\Manager::getMandantenByToolId($tool_id, true) as $mandant_id=>$mandant_name): ?>

						<div class="form-check">
							<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$tool_id.'_'.$mandant_id, '1', ((isset($ar_user_mandant[$tool_id])&&(isset($ar_user_mandant[$tool_id][$mandant_id]))&&($ar_user_mandant[$tool_id][$mandant_id]==1))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($mandant_name).'"', 'input_class'=>'form-check-input']) ?>
							<label class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$tool_id.'_'.$mandant_id ?>0"><?php echo \osWFrame\Core\HTML::outputString($mandant_name) ?></label>
						</div>

					<?php endforeach ?>

					<p></p>

				<?php endif ?>

			<?php endforeach ?>

		<?php else: ?>

			<?php foreach (\VIS\Core\Manager::getMandantenByToolId($this->getEditElementOption($element, 'tool_id'), true) as $mandant_id=>$mandant_name): ?>

				<div class="form-check">
					<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$this->getEditElementOption($element, 'tool_id').'_'.$mandant_id, '1', ((isset($ar_user_mandant[$this->getEditElementOption($element, 'tool_id')])&&(isset($ar_user_mandant[$this->getEditElementOption($element, 'tool_id')][$mandant_id]))&&($ar_user_mandant[$this->getEditElementOption($element, 'tool_id')][$mandant_id]==1))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($mandant_name).'"', 'input_class'=>'form-check-input']) ?>
					<label class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$this->getEditElementOption($element, 'tool_id').'_'.$mandant_id ?>0"><?php echo \osWFrame\Core\HTML::outputString($mandant_name) ?></label>
				</div>

			<?php endforeach ?>

		<?php endif ?>

	<?php endif ?>

	<?php /* error */ ?> <?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
	<?php endif ?>

	<?php /* notice */ ?> <?php if ($this->getEditElementOption($element, 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'notice')) ?></div>
	<?php endif ?>

	<?php /* buttons */ ?> <?php if ($this->getEditElementOption($element, 'buttons')!=''): ?>
		<div>
			<?php echo implode(' ', $this->getEditElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>

</div>