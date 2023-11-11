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
 * @var string $element
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use VIS2\Core\Manager;

if ($this->getDeleteElementOption($element, 'manager') === true) {
    $this->setDeleteElementStorage($element, Manager::loadUserTool($this->getIndexElementStorage(), 0));
} else {
    $this->setDeleteElementStorage(
        $element,
        Manager::loadUserTool($this->getIndexElementStorage(), $this->getDeleteElementOption($element, 'tool_id'))
    );
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
