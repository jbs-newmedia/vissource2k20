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

<?php
/*
 * <div class="container">
 * <div class="row justify-content-center mt-5">
 * <div class="col-sm-10 col-md-8 col-lg-6 text-muted justify-content-center text-center">
 * <?php echo \osWFrame\Core\ImageOptimizer::getOptimizedImage(vOut('vis2_logo_login_name'), array('parameter'=>'class="mb-5 img-responsive center-block"', 'module'=>vOut('vis2_logo_login_module'), 'title'=>vOut('vis2_logo_login_title'), 'longest'=>vOut('vis2_logo_login_longest'), 'height'=>vOut('vis2_logo_login_height'), 'width'=>vOut('vis2_logo_login_width')))?>
 * <?php if(vOut('vis2_logon_message')!=''):?><h1><?php echo vOut('vis2_logon_message')?></h1><?php endif?>
 * </div>
 * </div>
 * </div>
 *
 */
?>

<div class="container">
	<div class="row justify-content-center mt-5">
		<div class="col-sm-10 col-md-8 col-lg-6 text-muted justify-content-center text-center">
			<?php echo $this->getOptimizedImage(\osWFrame\Core\Settings::getStringVar('vis2_logo_login_name'), ['parameter'=>'class="mb-5 img-responsive center-block"', 'module'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_module'), 'title'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_title'), 'longest'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_longest'), 'height'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_height'), 'width'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_width')])?>
			<?php if(\osWFrame\Core\Settings::getStringVar('vis2_logon_message')!=''):?><h1><?php echo \osWFrame\Core\Settings::getStringVar('vis2_logon_message')?></h1><?php endif?>
		</div>
	</div>
</div>

<?php echo $this->Form()->startForm('form_change', 'current', 'vistool='.$VIS2_Main->getTool()); ?>
<div class="container">
	<div class="row justify-content-center mt-5 mb-3">
		<div class="col-sm-10 col-md-8 col-lg-6">
			<div class="login-panel card card-default">
				<div class="card-header">Programm wählen</div>
				<div class="card-body">
					<div class="form-group">
						<label for="vis2_login_email" class="form-control-label">Benutzer:</label>
						<div class="form-control"><?php echo \osWFrame\Core\HTML::outputString($VIS2_User->getName()) ?>
							<span style="float: right;"><a href="<?php echo $this->buildhrefLink('current') ?>">ändern</a></span>
						</div>
					</div>
					<div class="form-group">
						<label for="vis2_login_tool" class="form-control-label">Programm:</label>
						<?php echo $this->Form()->drawSelectField('vis2_login_tool', [''=>'']+$VIS2_User->getToolsSelectArray(), '', ['input_class'=>'selectpicker form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="btn btn-outline-default"']) ?>
						<div class="invalid-feedback"><?php echo $this->Form()->getErrorMessage('vis2_login_tool') ?></div>
					</div>
				</div>
				<div class="card-footer">
					<?php echo $this->Form()->drawSubmit('btn_change', 'Absenden', ['input_class'=>'btn btn-primary btn-block']) ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form()->drawHiddenField('action', 'dochange') ?>
<?php echo $this->Form()->endForm() ?>

<div class="container">
	<div class="row justify-content-center mb-3">
		<div class="col-sm-10 col-md-8 col-lg-6 text-muted text-center">
			<small>v<?php echo $VIS2_Main::getVersion() ?></small>
		</div>
	</div>
</div>