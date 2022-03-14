<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license MIT License
 */

?>

<?php if ($this->getDeleteElementValue($element, 'title')!=''): ?>
	<h4 class="form-group bg-primary text-white ddm4_element_header ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementValue($element, 'title')) ?></h4>
<?php endif ?>

<?php if ($this->getDeleteElementOption($element, 'display_create_time')==true): ?>
	<div class="form-group<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> has-error<?php endif ?> ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>">
		<label class="control-label"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementOption($element, 'text_create_time')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
		<?php if (($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_time')=='')||($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_time')=='0')): ?>
			<div class="form-control readonly">---</div>
		<?php else: ?><?php if ($this->getDeleteElementOption($element, 'month_asname')===true): ?>
			<div class="form-control readonly"><?php echo strftime(str_replace('%m.', ' %B ', $this->getDeleteElementOption($element, 'date_format')), $this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_time')) ?> <?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock')) ?></div>
		<?php else: ?>
			<div class="form-control readonly"><?php echo strftime($this->getDeleteElementOption($element, 'date_format'), $this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_time')) ?> <?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock')) ?></div>
		<?php endif ?><?php endif ?>
	</div>
<?php endif ?>

<?php if ($this->getDeleteElementOption($element, 'display_create_user')==true): ?>
	<div class="form-group<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> has-error<?php endif ?> ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>">
		<label class="control-label"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementOption($element, 'text_create_user')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
		<?php if (($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_user_id')=='')||($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_user_id')=='0')||(\VIS2\Core\Manager::getUsernameById($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_user_id'))=='')): ?>
			<div class="form-control readonly">---</div>
		<?php else: ?>
			<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString(\VIS2\Core\Manager::getUsernameById($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_user_id'))) ?></div>
		<?php endif ?>
	</div>
<?php endif ?>

<?php if ($this->getDeleteElementOption($element, 'display_update_time')==true): ?>
	<div class="form-group<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> has-error<?php endif ?> ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>">
		<label class="control-label"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementOption($element, 'text_update_time')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
		<?php if (($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_time')=='')||($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_time')=='0')): ?>
			<div class="form-control readonly">---</div>
		<?php else: ?><?php if ($this->getDeleteElementOption($element, 'month_asname')===true): ?>
			<div class="form-control readonly"><?php echo strftime(str_replace('%m.', ' %B ', $this->getDeleteElementOption($element, 'date_format')), $this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_time')) ?> <?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock')) ?></div>
		<?php else: ?>
			<div class="form-control readonly"><?php echo strftime($this->getDeleteElementOption($element, 'date_format'), $this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_time')) ?> <?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock')) ?></div>
		<?php endif ?><?php endif ?>
	</div>
<?php endif ?>

<?php if ($this->getDeleteElementOption($element, 'display_update_user')==true): ?>
	<div class="form-group<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> has-error<?php endif ?> ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>">
		<label class="control-label"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementOption($element, 'text_update_user')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
		<?php if (($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_user_id')=='')||($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_user_id')=='0')||(\VIS2\Core\Manager::getUsernameById($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_user_id'))=='')): ?>
			<div class="form-control readonly">---</div>
		<?php else: ?>
			<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString(\VIS2\Core\Manager::getUsernameById($this->getDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_user_id'))) ?></div>
		<?php endif ?>
	</div>
<?php endif ?>