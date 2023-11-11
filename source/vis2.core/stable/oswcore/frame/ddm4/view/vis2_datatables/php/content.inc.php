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
 * @var array $options
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use osWFrame\Core\Settings;

if ($this !== null) {
    foreach ($this->getListElements() as $element => $options) {
        $file = \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'list' . \DIRECTORY_SEPARATOR . $options['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
        $file_core = \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'list' . \DIRECTORY_SEPARATOR . $options['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
        if (file_exists($file)) {
            include $file;
        } elseif (file_exists($file_core)) {
            include $file_core;
        }
    }
}
