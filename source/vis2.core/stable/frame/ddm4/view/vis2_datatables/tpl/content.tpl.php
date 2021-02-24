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

<?php if (\osWFrame\Core\Settings::getAction()!='log'): ?>

	<?php if (($this->getCounter('add_elements')>0)&&($this->getListElementOption($element, 'disable_add')!==true)&&($this->getCounter('search_elements')>0)&&($this->getListElementOption($element, 'disable_search')!==true)): ?>

		<?php if (($this->getCounter('add_elements')>0)&&($this->getListElementOption($element, 'disable_add')!==true)): ?>
			<a class="btn btn-primary float-left" onclick="openDDM4Modal_<?php echo $this->getName() ?>(this, '<?php echo $this->getGroupOption('add_title', 'messages') ?>', 'add', <?php echo $this->getCounter('add_elements') ?>)" title="<?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('data_add')) ?>" pageName="<?php echo $this->getTemplate()->buildhrefLink($this->getDirectModule(), 'action=add&modal=1&'.$this->getDirectParameters()) ?>"><i class="fa fa-plus fa-fw"></i> <?php echo $this->getGroupOption('add_title', 'messages') ?>
			</a>
		<?php endif ?>

		<?php if (($this->getCounter('search_elements')>0)&&($this->getListElementOption($element, 'disable_search')!==true)): ?>
			<a id="ddm4_button_search_edit"<?php if ($this->getParameter('ddm_search_data')==[]): ?> style="display:none;"<?php endif ?> class="btn btn-primary float-right" onclick="openDDM4Modal_<?php echo $this->getName() ?>(this, '<?php echo $this->getGroupOption('search_title', 'messages') ?>', 'search_edit', <?php echo $this->getCounter('search_elements') ?>)" title="<?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('data_search')) ?>" pageName="<?php echo $this->getTemplate()->buildhrefLink($this->getDirectModule(), 'action=search&modal=1&'.$this->getDirectParameters()) ?>"><i class="fa fa-search fa-fw"></i> <?php echo $this->getGroupOption('edit_search_title', 'messages') ?>
			</a>
			<a id="ddm4_button_search_submit"<?php if ($this->getParameter('ddm_search_data')!=[]): ?> style="display:none;"<?php endif ?> class="btn btn-primary float-right" onclick="openDDM4Modal_<?php echo $this->getName() ?>(this, '<?php echo $this->getGroupOption('search_title', 'messages') ?>', 'search', <?php echo $this->getCounter('search_elements') ?>)" title="<?php echo \osWFrame\Core\HTML::outputString($this->getGroupMessage('data_search')) ?>" pageName="<?php echo $this->getTemplate()->buildhrefLink($this->getDirectModule(), 'action=search&modal=1&'.$this->getDirectParameters()) ?>"><i class="fa fa-search fa-fw"></i> <?php echo $this->getGroupOption('search_title', 'messages') ?>
			</a>
		<?php endif ?>
		<div class="clearfix"><!----></div><br/>
	<?php endif ?>

<?php endif ?>

<table id="ddm4_datatables_<?php echo $this->getName() ?>" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%;">
	<?php if ($this->getCounter('list_view_elements')): ?>
		<thead>
		<tr class="ddm_element_<?php echo $this->getViewElementValue($element, 'id') ?>">
			<?php
			foreach ($this->getListElements() as $element=>$options) {
				$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/list/'.$options['module'].'/tpl/header.tpl.php';
				if (file_exists($file)) {
					include $file;
				}
			}
			?>
		</tr>
		</thead>
	<?php endif ?>
</table>