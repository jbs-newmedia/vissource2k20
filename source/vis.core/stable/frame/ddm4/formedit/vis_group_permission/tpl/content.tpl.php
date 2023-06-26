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

if (!function_exists('vis_manager_group_permission')) {
	function vis_manager_group_permission($object, $element, $navigation_element, $ar_permission, $ro=false) {
		?>
		<div style="padding-left:<?php echo(($navigation_element['info']['navigation_level'])*20) ?>px">
			<strong><?php echo \osWFrame\Core\HTML::outputString($navigation_element['info']['navigation_title']) ?></strong>
			<?php if (count($navigation_element['info']['permission'])>0): ?><?php foreach ($navigation_element['info']['permission'] as $flag): ?><?php if ($ro===true): ?>
				<div class="custom-checkbox">
					<?php if (isset($ar_permission[$navigation_element['info']['page_name_intern']][$flag])): ?><?php echo $object->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString(\VIS\Core\Manager::getPermissionText($flag, $object->getEditElementOption($element, 'tool_id'))) ?><?php else: ?><?php echo $object->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString(\VIS\Core\Manager::getPermissionText($flag, $object->getEditElementOption($element, 'tool_id'))) ?><?php endif ?><?php echo $object->getTemplate()->Form()->drawHiddenField('page_'.$navigation_element['info']['page_name_intern'].'_'.$flag, 0) ?>
				</div>
			<?php else: ?>
				<div class="form-check">
					<?php echo $object->getTemplate()->Form()->drawCheckBoxField('page_'.$navigation_element['info']['page_name_intern'].'_'.$flag, '1', (isset($ar_permission[$navigation_element['info']['page_name_intern']][$flag])?$ar_permission[$navigation_element['info']['page_name_intern']][$flag]:0), ['input_class'=>'form-check-input']) ?>
					<label class="form-check-label<?php if ($object->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo 'page_'.$navigation_element['info']['page_name_intern'].'_'.$flag ?>0"><?php echo \osWFrame\Core\HTML::outputString(\VIS\Core\Manager::getPermissionText($flag, $object->getEditElementOption($element, 'tool_id'))) ?></label>
				</div>
			<?php endif ?><?php endforeach ?><?php endif ?>
			<?php if (count($navigation_element['links'])>0): ?>

				<?php foreach ($navigation_element['links'] as $_navigation_element): ?>

					<?php vis_manager_group_permission($object, $element, $_navigation_element, $ar_permission, $ro) ?><?php endforeach ?>

			<?php endif ?>
		</div>
		<?php
	}
}

?>

<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementValue($element, 'title')) ?><?php if ($this->getEditElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getEditElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?><?php $ar_permission=$this->getEditElementStorage($element) ?><?php foreach (\VIS\Core\Manager::getNavigationReal(0, $this->getGroupOption('navigation_level'), $this->getEditElementOption($element, 'tool_id')) as $navigation_element): ?><?php echo vis_manager_group_permission($this, $element, $navigation_element, $ar_permission, true) ?><?php endforeach ?>

	<?php else: ?>

		<?php /* input */ ?><?php $ar_permission=$this->getEditElementStorage($element) ?><?php foreach (\VIS\Core\Manager::getNavigationReal(0, $this->getGroupOption('navigation_level'), $this->getEditElementOption($element, 'tool_id')) as $navigation_element): ?><?php echo vis_manager_group_permission($this, $element, $navigation_element, $ar_permission) ?><?php endforeach ?>

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