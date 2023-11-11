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

use osWFrame\Core\DDM4;

if ($this->getIndexElementStorage() === $this->getDoEditElementStorage('navigation_parent_id')) {
    $this->getTemplate()->Form()->addErrorMessage(
        $element,
        osWFrame\Core\StringFunctions::parseTextWithVars(
            $this->getGroupMessage('validation_element_incorrect'),
            $this->getFilterElementStorage($element)
        )
    );
    $this->setFilterErrorElementStorage($element, true);
}
