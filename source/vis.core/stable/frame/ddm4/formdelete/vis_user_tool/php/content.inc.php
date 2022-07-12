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

if ($this->getDeleteElementOption($element, 'manager')===true) {
	$this->setDeleteElementStorage($element, \VIS\Core\Manager::loadUserTool($this->getIndexElementStorage(), 0));
} else {
	$this->setDeleteElementStorage($element, \VIS\Core\Manager::loadUserTool($this->getIndexElementStorage(), $this->getDeleteElementOption($element, 'tool_id')));
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>