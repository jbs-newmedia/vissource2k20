<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$default_options['enabled']=true;
$default_options['options']['prefix']='';
$default_options['options']['order']=false;
$default_options['options']['display_create_time']=true;
$default_options['options']['display_create_user']=true;
$default_options['options']['display_update_time']=true;
$default_options['options']['display_update_user']=true;
$default_options['options']['date_format']='%d.%m.%Y %H:%M';
$default_options['options']['month_asname']=false;
$default_options['options']['text_create_time']=$this->getGroupMessage('create_time');
$default_options['options']['text_create_user']=$this->getGroupMessage('create_user');
$default_options['options']['text_update_time']=$this->getGroupMessage('update_time');
$default_options['options']['text_update_user']=$this->getGroupMessage('update_user');
$default_options['name_array']=['create_time', 'create_user_id', 'update_time', 'update_user_id'];
$default_options['_search']['enabled']=false;

?>