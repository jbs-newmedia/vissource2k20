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

<?php if ($this->getEditElementValue($element, 'title')!=''): ?>
	<h4 class="form-group bg-primary text-white ddm4_element_header ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementValue($element, 'title')) ?></h4>
<?php endif ?>

<?php if ($this->getEditElementOption($element, 'display_create_time')==true): ?>
	<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">
		<label class="form-control-label"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'text_create_time')) ?><?php if ($this->getEditElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
		<?php if (($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'create_time')=='')||($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'create_time')=='0')): ?>
			<div class="form-control readonly">---</div>
		<?php else: ?><?php if ($this->getEditElementOption($element, 'month_asname')===true): ?>
			<div class="form-control readonly"><?php echo strftime(str_replace('%m.', ' %B ', $this->getEditElementOption($element, 'date_format')), $this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'create_time')) ?> <?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock')) ?></div>
		<?php else: ?>
			<div class="form-control readonly"><?php echo strftime($this->getEditElementOption($element, 'date_format'), $this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'create_time')) ?> <?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock')) ?></div>
		<?php endif ?><?php endif ?>
	</div>
<?php endif ?>

<?php if ($this->getEditElementOption($element, 'display_create_user')==true): ?>
	<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">
		<label class="form-control-label"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'text_create_user')) ?><?php if ($this->getEditElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
		<?php if (($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'create_user_id')=='')||($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'create_user_id')=='0')||(\VIS2\Core\Manager::getUsernameById($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'create_user_id'))=='')): ?>
			<div class="form-control readonly">---</div>
		<?php else: ?>
			<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString(\VIS2\Core\Manager::getUsernameById($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'create_user_id'))) ?></div>
		<?php endif ?>
	</div>
<?php endif ?>

<?php if ($this->getEditElementOption($element, 'display_update_time')==true): ?>
	<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">
		<label class="form-control-label"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'text_update_time')) ?><?php if ($this->getEditElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
		<?php if (($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'update_time')=='')||($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'update_time')=='0')): ?>
			<div class="form-control readonly">---</div>
		<?php else: ?><?php if ($this->getEditElementOption($element, 'month_asname')===true): ?>
			<div class="form-control readonly"><?php echo strftime(str_replace('%m.', ' %B ', $this->getEditElementOption($element, 'date_format')), $this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'update_time')) ?> <?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock')) ?></div>
		<?php else: ?>
			<div class="form-control readonly"><?php echo strftime($this->getEditElementOption($element, 'date_format'), $this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'update_time')) ?> <?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock')) ?></div>
		<?php endif ?><?php endif ?>
	</div>
<?php endif ?>

<?php if ($this->getEditElementOption($element, 'display_update_user')==true): ?>
	<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">
		<label class="form-control-label"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'text_update_user')) ?><?php if ($this->getEditElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
		<?php if (($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'update_user_id')=='')||($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'update_user_id')=='0')||(\VIS2\Core\Manager::getUsernameById($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'update_user_id'))=='')): ?>
			<div class="form-control readonly">---</div>
		<?php else: ?>
			<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString(\VIS2\Core\Manager::getUsernameById($this->getEditElementStorage($this->getEditElementOption($element, 'prefix').'update_user_id'))) ?></div>
		<?php endif ?>
	</div>
<?php endif ?>