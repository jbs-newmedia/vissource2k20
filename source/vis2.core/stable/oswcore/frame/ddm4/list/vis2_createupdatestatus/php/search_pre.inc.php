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
 * @var array $element
 * @var array $options
 * @var array $_search
 * @var \osWFrame\Core\DDM4 $this
 *
 */


if ($this->getListElementOption($element, 'display_create_time') === true) {
    $_search[$options['options']['prefix'] . 'create_time'] = $options['options']['prefix'] . 'create_time';
}

if ($this->getListElementOption($element, 'display_create_user') === true) {
    $_search[$options['options']['prefix'] . 'create_user_id'] = $options['options']['prefix'] . 'create_user_id';
}

if ($this->getListElementOption($element, 'display_update_time') === true) {
    $_search[$options['options']['prefix'] . 'update_time'] = $options['options']['prefix'] . 'update_time';
}

if ($this->getListElementOption($element, 'display_update_user') === true) {
    $_search[$options['options']['prefix'] . 'update_user_id'] = $options['options']['prefix'] . 'update_user_id';
}
