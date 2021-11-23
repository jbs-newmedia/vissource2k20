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
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getAddElementValue($element, 'title')) ?><?php if ($this->getAddElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getAddElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>

		<?php $ar_tool_user=$this->getAddElementStorage($element); ?>

		<?php $users=\VIS2\Core\Manager::getUsers(); ?>

		<?php foreach ($users as $user_id=>$user_name):$i++; ?>
			<?php if ((isset($ar_tool_user[$user_id]))&&($ar_tool_user[$user_id]==1)): ?>
			<div class="custom-checkbox">
				<?php if ((isset($ar_tool_user[$user_id]))&&($ar_tool_user[$user_id]==1)): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($user_name) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($user_name) ?><?php endif ?><?php echo $this->getTemplate()->Form()->drawHiddenField('page_'.$navigation_element['info']['page_name_intern'].'_'.$flag, 0) ?>
			</div>
			<?php endif?>
		<?php endforeach ?>

	<?php else: ?>

		<?php /* input */ ?>

		<?php $ar_tool_user=$this->getAddElementStorage($element); ?>

		<?php $users=\VIS2\Core\Manager::getUsers(); ?>

		<?php if(count($users)>intval($this->getAddElementOption($element, 'search_mod_counter'))):?>

		<div class="input-group">
			<?php echo $this->getTemplate()->Form()->drawTextField($element.'_search', '', ['input_class'=>'form-control form-control-rborder', 'input_parameter'=>'placeholder="Suchen ..." oninput="ddm4_function_'.$element.'();"']); ?>
			<button type="button" class="btn bg-transparent" style="margin-left: -40px; z-index: 100; border:0;" onclick="javascript:$('#<?php echo $element?>_search').val('');ddm4_function_<?php echo $element?>();">
				<i class="fa fa-times"></i>
			</button>
		</div>

		<br/>

		<?php endif?>

		<?php foreach ($users as $user_id=>$user_name): ?>

		<div class="form-check">
			<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$user_id, '1', ((isset($ar_tool_user[$user_id])&&($ar_tool_user[$user_id]==1))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($user_name).'"', 'input_class'=>'form-check-input']) ?>
			<label class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)!==null): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$user_id ?>0"><?php echo \osWFrame\Core\HTML::outputString($user_name) ?></label>
		</div>

		<?php endforeach ?>

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
		<div>
			<?php echo implode(' ', $this->getAddElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>

</div>