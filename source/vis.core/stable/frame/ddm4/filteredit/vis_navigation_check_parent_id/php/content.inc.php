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

if ($this->getIndexElementStorage()==$this->getDoEditElementStorage('navigation_parent_id')) {
	$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_incorrect'), $this->getFilterElementStorage($element)));
	$this->setFilterErrorElementStorage($element, true);
}

?>