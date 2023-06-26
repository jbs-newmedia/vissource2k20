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

	<?php $ar_tool_user=$this->getDeleteElementStorage($element); ?>

	<?php $users=\VIS\Core\Manager::getUsers(); ?>

	<?php $i=0;
	foreach ($users as $user_id=>$user_name):$i++; ?><?php if ((isset($ar_tool_user[$user_id]))&&($ar_tool_user[$user_id]==1)): ?>
		<div class="custom-checkbox">
			<?php if ((isset($ar_tool_user[$user_id]))&&($ar_tool_user[$user_id]==1)): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($user_name) ?><?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($user_name) ?><?php endif ?><?php echo $this->getTemplate()->Form()->drawHiddenField('page_'.$navigation_element['info']['page_name_intern'].'_'.$flag, 0) ?>
		</div>
	<?php endif ?><?php endforeach ?>

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