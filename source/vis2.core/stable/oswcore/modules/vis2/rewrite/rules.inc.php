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
 * @var array $ar_parameters
 * @var string $base_uri
 * @var bool $check_parameters
 * @var bool $seowrite_inpage
 * @var bool $add_session
 *
 */

use osWFrame\Core\Settings;

$check_parameters = false;
$parameters = $ar_parameters;
$go_default = false;

if (isset($parameters['vistool'])) {
    $base_uri .= Settings::getStringVar('vis2_path') . '/' . $parameters['vistool'] . '/';
} else {
    $base_uri .= Settings::getStringVar('vis2_path') . '/' . Settings::getStringVar('vis2_login_module') . '/';
}

if ($seowrite_inpage === true) {
    global $VIS2_Navigation;
    $base_uri .= $VIS2_Navigation->getPage();
} else {
    if (isset($parameters['vispage'])) {
        $base_uri .= $parameters['vispage'];
    }
}

if (isset($parameters['module'])) {
    unset($parameters['module']);
}
if (isset($parameters['vistool'])) {
    unset($parameters['vistool']);
}
if (isset($parameters['vispage'])) {
    unset($parameters['vispage']);
}
if ((isset($parameters[Settings::getStringVar('session_name')])) && ($add_session !== true)) {
    unset($parameters[Settings::getStringVar('session_name')]);
}

$base_uri .= '?';
foreach ($parameters as $key => $value) {
    $base_uri .= $key . '=' . $value . '&';
}
$base_uri = substr($base_uri, 0, -1);

if (isset($extend[1])) {
    $base_uri .= '#' . $extend[1];
}
