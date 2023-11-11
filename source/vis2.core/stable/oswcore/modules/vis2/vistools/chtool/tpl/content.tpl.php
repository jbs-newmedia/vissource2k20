<?php declare(strict_types=0);

/**
 * This file is part of the VIS2 package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var \osWFrame\Core\Template $this
 * @var \VIS2\Core\Main $VIS2_Main
 * @var \VIS2\Core\User $VIS2_User
 *
 */

use osWFrame\Core\HTML;
use osWFrame\Core\Settings;

?>

<div class="container mx-auto" style="max-width: 600px;">

    <div class="row justify-content-center mt-5 mb-3">
        <div class="col text-muted justify-content-center text-center">
            <?php if (pathinfo(Settings::getStringVar('vis2_logo_login_name'), \PATHINFO_EXTENSION) === 'svg'): ?>
                <img style="width: <?php echo Settings::getIntVar('vis2_logo_login_longest'); ?>%"
                     src="<?php echo $VIS2_Main->getResourceLink(
                         'img' . \DIRECTORY_SEPARATOR . Settings::getStringVar('vis2_logo_login_name')
                     ) ?>"
                     title="<?php echo Settings::getStringVar('vis2_logo_login_title') ?>"
                     alt="<?php echo Settings::getStringVar('vis2_logo_login_title') ?>"/>
            <?php else: ?><?php echo $this->getOptimizedImage(Settings::getStringVar('vis2_logo_login_name'), [
                                     'parameter' => 'class="img-fluid"',
                                     'module' => Settings::getStringVar('vis2_logo_login_module'),
                                     'path' => Settings::getStringVar('vis2_logo_path'),
                                     'title' => Settings::getStringVar('vis2_logo_login_title'),
                                     'longest' => Settings::getIntVar('vis2_logo_login_longest'),
                                     'height' => Settings::getIntVar('vis2_logo_login_height'),
                                     'width' => Settings::getIntVar('vis2_logo_login_width'),
                                 ]) ?><?php endif ?>
            <?php if (Settings::getStringVar('vis2_logon_message') !== ''): ?>
                <h1 class="mt-5 "><?php echo Settings::getStringVar('vis2_logon_message') ?></h1>
            <?php endif ?>
        </div>
    </div>

    <?php echo $this->Form()->startForm('form_change', 'current', 'vistool=' . $VIS2_Main->getTool()); ?>
    <div class="row justify-content-center mt-5 mb-3">
        <div class="col">
            <div class="login-panel card card-default">
                <div class="card-header">Programm wählen</div>
                <div class="card-body mb-2">
                    <div class="form-group">
                        <label for="vis2_login_email" class="form-control-label mb-1">Benutzer:</label>
                        <div class="form-control"><?php echo HTML::outputString($VIS2_User->getName()) ?>
                            <span style="float: right;"><a
                                    href="<?php echo $this->buildhrefLink(
                                        'current',
                                        'vistool=' . Settings::getStringVar('vis2_logout_module')
                                    ) ?>">ändern</a></span>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="vis2_login_tool" class="form-control-label mb-1">Programm:</label>
                        <?php echo $this->Form()->drawSelectField(
                            'vis2_login_tool',
                            [
                                '' => '',
                            ] + $VIS2_User->getToolsSelectArray(),
                            '',
                            [
                                'input_class' => 'selectpicker form-control',
                                'input_errorclass' => 'is-invalid',
                                'input_parameter' => ' data-style="form-select custom-select"',
                            ]
                        ) ?>
                        <div
                            class="invalid-feedback"><?php echo $this->Form()->getErrorMessage(
                                'vis2_login_tool'
                            ) ?></div>
                    </div>
                </div>
                <div class="card-footer d-grid gap-2">
                    <?php echo $this->Form()->drawSubmit('btn_change', 'Absenden', [
                        'input_class' => 'btn btn-primary',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form()->drawHiddenField('action', 'dochange') ?>
    <?php echo $this->Form()->endForm() ?>


    <div class="row justify-content-center mb-3">
        <div class="col text-muted text-center">
            <small>v<?php echo $VIS2_Main::getVersion() ?></small>
        </div>
    </div>
</div>
