<?php

/**
 * This file is part of the VIS2:Lab package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:Lab
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$data=[];
$data['navigation_id']=$run_counter;
$data['custom']=true;
$data['tool_id']=$VIS2_Main->getToolId();
$data['navigation_parent_id']=$header_counter;
$data['navigation_title']='File';
$data['navigation_sortorder']=$run_counter+100;
$data['navigation_ispublic']=1;
$data['page_name']='lab_ddm4_file';
$data['page_description']='File';
$data['page_ispublic']=1;
$data['navigation_path']='';
$data['navigation_path_array']=[];
$data['permission']=['link', 'view'];
$VIS2_Navigation->addNavigationElement($data);

?>