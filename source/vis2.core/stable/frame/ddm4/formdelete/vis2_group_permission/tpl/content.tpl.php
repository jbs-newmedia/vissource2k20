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

if (!function_exists('vis2_manager_group_permission')) {
	function vis2_manager_group_permission($object, $element, $navigation_element, $ar_permission, $ro=false) {
		?>
		<div style="padding-left:<?php echo(($navigation_element['info']['navigation_level'])*20) ?>px">
			<strong><?php echo \osWFrame\Core\HTML::outputString($navigation_element['info']['navigation_title']) ?></strong>
			<?php if (count($navigation_element['info']['permission'])>0): ?><?php foreach ($navigation_element['info']['permission'] as $flag): ?><?php if ($ro===true): ?>
				<div class="custom-checkbox">
					<?php if (isset($ar_permission[$navigation_element['info']['page_name_intern']][$flag])): ?><?php echo $object->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString(\VIS2\Core\Manager::getPermissionText($flag, $object->getDeleteElementOption($element, 'tool_id'))) ?><?php else: ?><?php echo $object->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString(\VIS2\Core\Manager::getPermissionText($flag, $object->getDeleteElementOption($element, 'tool_id'))) ?><?php endif ?><?php echo $object->getTemplate()->Form()->drawHiddenField('page_'.$navigation_element['info']['page_name_intern'].'_'.$flag, 0) ?>
				</div>
			<?php else: ?>
				<div class="custom-control custom-checkbox">
					<?php echo $object->getTemplate()->Form()->drawCheckBoxField('page_'.$navigation_element['info']['page_name_intern'].'_'.$flag, '1', (isset($ar_permission[$navigation_element['info']['page_name_intern']][$flag])?$ar_permission[$navigation_element['info']['page_name_intern']][$flag]:0), ['input_class'=>'custom-control-input']) ?>
					<label class="custom-control-label<?php if ($object->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo 'page_'.$navigation_element['info']['page_name_intern'].'_'.$flag ?>0"><?php echo \osWFrame\Core\HTML::outputString(\VIS2\Core\Manager::getPermissionText($flag, $object->getDeleteElementOption($element, 'tool_id'))) ?></label>
				</div>
			<?php endif ?><?php endforeach ?><?php endif ?>
			<?php if (count($navigation_element['links'])>0): ?><?php foreach ($navigation_element['links'] as $_navigation_element): ?><?php vis2_manager_group_permission($object, $element, $_navigation_element, $ar_permission, $ro) ?><?php endforeach ?><?php endif ?>
		</div>
		<?php
	}
}

?>

<div class="form-group ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementValue($element, 'title')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php /* read only */ ?>
	<?php $ar_permission=$this->getDeleteElementStorage($element) ?>
	<?php foreach (\VIS2\Core\Manager::getNavigationReal(0, $this->getGroupOption('navigation_level'), $this->getDeleteElementOption($element, 'tool_id')) as $navigation_element): ?><?php echo vis2_manager_group_permission($this, $element, $navigation_element, $ar_permission, true) ?><?php endforeach ?>

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