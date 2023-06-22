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

<div class="container mx-auto" style="max-width: 600px;">

	<div class="row justify-content-center mt-5 mb-3">
		<div class="col text-muted justify-content-center text-center">
			<?php if (pathinfo( \osWFrame\Core\Settings::getStringVar('vis2_logo_login_name') , PATHINFO_EXTENSION)=='svg'):?>
				<img style="width: <?php echo \osWFrame\Core\Settings::getIntVar('vis2_logo_login_longest');?>%" src="<?php echo $VIS2_Main->getResourceLink('img'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('vis2_logo_login_name'))?>" title="<?php echo \osWFrame\Core\Settings::getStringVar('vis2_logo_login_title')?>" alt="<?php echo \osWFrame\Core\Settings::getStringVar('vis2_logo_login_title')?>"/>
			<?php else:?>
				<?php echo $this->getOptimizedImage(\osWFrame\Core\Settings::getStringVar('vis2_logo_login_name'), ['parameter'=>'class="img-fluid"', 'module'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_module'), 'path'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_path'), 'title'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_title'), 'longest'=>\osWFrame\Core\Settings::getIntVar('vis2_logo_login_longest'), 'height'=>\osWFrame\Core\Settings::getIntVar('vis2_logo_login_height'), 'width'=>\osWFrame\Core\Settings::getIntVar('vis2_logo_login_width')]) ?>
			<?php endif?>
			<?php if (\osWFrame\Core\Settings::getStringVar('vis2_logon_message')!=''): ?>
				<h1 class="mt-5 "><?php echo \osWFrame\Core\Settings::getStringVar('vis2_logon_message') ?></h1>
			<?php endif ?>
		</div>
	</div>

	<?php echo $this->Form()->startForm('form_login', 'current'); ?>
	<div class="row justify-content-center mt-5 mb-3">
		<div class="col">
			<div class="login-panel card card-default">
				<div class="card-header">Anmelden</div>
				<div class="card-body mb-2">
					<div class="form-group">
						<label for="vis2_login_email" class="form-control-label mb-1">E-Mail:</label>
						<?php echo $this->Form()->drawTextField('vis2_login_email', '', ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
						<div class="invalid-feedback"><?php echo $this->Form()->getErrorMessage('vis2_login_email') ?></div>
					</div>
					<div class="form-group mt-3">
						<label for="vis2_login_password" class="form-control-label mb-1">Password:</label>
						<?php echo $this->Form()->drawPasswordField('vis2_login_password', '', ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
						<div class="invalid-feedback"><?php echo $this->Form()->getErrorMessage('vis2_login_password') ?></div>
					</div>
					<?php if ((\osWFrame\Core\Settings::getBoolVar('vis2_protect_login_remember')===true)&&(\osWFrame\Core\Cookie::isCookiesEnabled()===true)): ?>
					<div class="form-group mt-3">
						<div class="form-check mb-3 mb-md-0 ms-2">
							<?php echo $this->Form()->drawCheckboxField('vis2_login_remember', '1', '0', ['input_class'=>'form-check-input me-1']) ?>
							<label class="form-check-label" for="vis2_login_remember"> Login merken </label>
						</div>
					</div>
					<?php endif ?>
				</div>
				<div class="card-footer d-grid gap-2">
					<?php echo $this->Form()->drawSubmit('btn_login', 'Absenden', ['input_class'=>'btn btn-primary']) ?>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->Form()->drawHiddenField('action', 'dologin') ?>
	<?php echo $this->Form()->endForm() ?>

	<div class="row justify-content-center mb-3">
		<div class="col text-muted text-center">
			<small>v<?php echo $VIS2_Main::getVersion() ?></small>
		</div>
	</div>
</div>
