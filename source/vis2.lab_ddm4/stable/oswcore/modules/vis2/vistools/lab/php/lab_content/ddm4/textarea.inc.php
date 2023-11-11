<?php declare(strict_types=0);

/**
 * This file is part of the VIS2:Lab package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2:Lab
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var int $run_counter
 * @var int $header_counter
 * @var \VIS2\Core\Main $VIS2_Main
 * @var \VIS2\Core\Navigation $VIS2_Navigation
 *
 */

use VIS2\Core\Main;
use VIS2\Core\Navigation;

$data = [];
$data['navigation_id'] = $run_counter;
$data['custom'] = true;
$data['tool_id'] = $VIS2_Main->getToolId();
$data['navigation_parent_id'] = $header_counter;
$data['navigation_title'] = 'TextArea';
$data['navigation_sortorder'] = $run_counter + 100;
$data['navigation_ispublic'] = 1;
$data['page_name'] = 'lab_ddm4_textarea';
$data['page_description'] = 'TextArea';
$data['page_ispublic'] = 1;
$data['navigation_path'] = '';
$data['navigation_path_array'] = [];
$data['permission'] = ['link', 'view'];
$VIS2_Navigation->addNavigationElement($data);
