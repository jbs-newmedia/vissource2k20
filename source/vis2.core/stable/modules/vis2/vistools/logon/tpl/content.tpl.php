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

<div class="container">
	<div class="row justify-content-center mt-5">
		<div class="col-sm-10 col-md-8 col-lg-6 text-muted justify-content-center text-center">
			<?php echo $this->getOptimizedImage(\osWFrame\Core\Settings::getStringVar('vis2_logo_login_name'), ['parameter'=>'class="mb-5 img-responsive center-block"', 'module'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_module'), 'title'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_title'), 'longest'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_longest'), 'height'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_height'), 'width'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_login_width')]) ?>
			<?php if (\osWFrame\Core\Settings::getStringVar('vis2_logon_message')!=''): ?>
				<h1><?php echo \osWFrame\Core\Settings::getStringVar('vis2_logon_message') ?></h1><?php endif ?>
		</div>
	</div>
</div>

<?php echo $this->Form()->startForm('form_login', 'current'); ?>
<div class="container">
	<div class="row justify-content-center mt-5 mb-3">
		<div class="col-sm-10 col-md-8 col-lg-6">
			<div class="login-panel card card-default">
				<div class="card-header">Anmelden</div>
				<div class="card-body">
					<div class="form-group">
						<label for="vis2_login_email" class="form-control-label">E-Mail:</label>
						<?php echo $this->Form()->drawTextField('vis2_login_email', '', ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
						<div class="invalid-feedback"><?php echo $this->Form()->getErrorMessage('vis2_login_email') ?></div>
					</div>
					<div class="form-group">
						<label for="vis2_login_password" class="form-control-label">Password:</label>
						<?php echo $this->Form()->drawPasswordField('vis2_login_password', '', ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
						<div class="invalid-feedback"><?php echo $this->Form()->getErrorMessage('vis2_login_password') ?></div>
					</div>
				</div>
				<div class="card-footer">
					<?php echo $this->Form()->drawSubmit('btn_login', 'Absenden', ['input_class'=>'btn btn-primary btn-block']) ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form()->drawHiddenField('action', 'dologin') ?>
<?php echo $this->Form()->endForm() ?>

<div class="container">
	<div class="row justify-content-center mb-3">
		<div class="col-sm-10 col-md-8 col-lg-6 text-muted text-center">
			<small>v<?php echo $VIS2_Main::getVersion() ?></small>
		</div>
	</div>
</div>
