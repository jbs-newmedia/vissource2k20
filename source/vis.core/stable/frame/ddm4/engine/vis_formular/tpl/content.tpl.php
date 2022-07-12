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

if (\osWFrame\Core\Settings::getAction()=='send') {
	echo '<div class="card shadow mb-4"><div class="card-body page-wrapper-form">';

	echo $this->getTemplate()->Form()->startForm('form_send', 'current', $this->getDirectParameters(), ['form_parameter'=>'enctype="multipart/form-data"']);
	foreach ($this->getSendElements() as $element=>$options) {
		echo $this->parseFormSendElementTPL($element, $options);
	}
	echo $this->getTemplate()->Form()->drawHiddenField('action', 'dosend');
	echo $this->getTemplate()->Form()->drawHiddenField($this->getGroupOption('index', 'database'), $this->getIndexElementStorage());
	echo $this->getTemplate()->Form()->endForm();

	echo '</div></div>';
}

?>