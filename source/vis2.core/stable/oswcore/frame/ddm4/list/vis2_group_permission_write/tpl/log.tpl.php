<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var \osWFrame\Core\DDM4 $this
 * @var array $view_data
 * @var string $element
 *
 */


$view_data[$this->getListElementValue($element, 'name')] = str_replace(
    '#1#',
    $this->getGroupMessage('log_char_true'),
    $view_data[$this->getListElementValue($element, 'name')]
);
$view_data[$this->getListElementValue($element, 'name')] = str_replace(
    '#0#',
    $this->getGroupMessage('log_char_false'),
    $view_data[$this->getListElementValue($element, 'name')]
);
