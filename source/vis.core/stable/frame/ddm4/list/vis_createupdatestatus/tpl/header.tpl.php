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

<?php if ($this->getListElementOption($element, 'display_create_time')==true): ?>
	<th class="ddm_element_<?php echo $this->getListElementValue($element, 'id') ?>_create_time">
		<?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'text_create_time')) ?>
	</th>
<?php endif ?>

<?php if ($this->getListElementOption($element, 'display_create_user')==true): ?>
	<th class="ddm_element_<?php echo $this->getListElementValue($element, 'id') ?>_create_user">
		<?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'text_create_user')) ?>
	</th>
<?php endif ?>

<?php if ($this->getListElementOption($element, 'display_update_time')==true): ?>
	<th class="ddm_element_<?php echo $this->getListElementValue($element, 'id') ?>_update_time">
		<?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'text_update_time')) ?>
	</th>
<?php endif ?>

<?php if ($this->getListElementOption($element, 'display_update_user')==true): ?>
	<th class="ddm_element_<?php echo $this->getListElementValue($element, 'id') ?>_update_user">
		<?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'text_update_user')) ?>
	</th>
<?php endif ?>