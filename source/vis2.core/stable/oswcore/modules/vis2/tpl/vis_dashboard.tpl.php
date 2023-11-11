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
 * @var array $dashboard_tpls
 *
 */

$dashboard_tpls = $dashboard_tpls ?? [];

foreach ($dashboard_tpls as $file) {
    include $file;
}
