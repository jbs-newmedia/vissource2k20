<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license MIT License
 */

if ($this->getListElementOption($element, 'display_create_time')==true) {
	if (($view_data[$this->getListElementOption($element, 'prefix').'create_time']=='')||($view_data[$this->getListElementOption($element, 'prefix').'create_time']=='0')) {
		$view_data[$this->getListElementOption($element, 'prefix').'create_time']='---';
	} else {
		if ($this->getListElementOption($element, 'month_asname')===true) {
			$view_data[$this->getListElementOption($element, 'prefix').'create_time']=strftime(str_replace('%m.', ' %B ', $this->getListElementOption($element, 'date_format')), $view_data[$this->getListElementOption($element, 'prefix').'create_time']).' '.\osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock'));
		} else {
			$view_data[$this->getListElementOption($element, 'prefix').'create_time']=strftime($this->getListElementOption($element, 'date_format'), $view_data[$this->getListElementOption($element, 'prefix').'create_time']).' '.\osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock'));
		}
	}
}

if ($this->getListElementOption($element, 'display_create_user')==true) {
	if (($view_data[$this->getListElementOption($element, 'prefix').'create_user_id']=='')||($view_data[$this->getListElementOption($element, 'prefix').'create_user_id']=='0')||(\VIS2\Core\Manager::getUsernameById($view_data[$this->getListElementOption($element, 'prefix').'create_user_id'])=='')) {
		$view_data[$this->getListElementOption($element, 'prefix').'create_user_id']='---';
	} else {
		$view_data[$this->getListElementOption($element, 'prefix').'create_user_id']=\osWFrame\Core\HTML::outputString(\VIS2\Core\Manager::getUsernameById($view_data[$this->getListElementOption($element, 'prefix').'create_user_id']));
	}
}

if ($this->getListElementOption($element, 'display_update_time')==true) {
	if (($view_data[$this->getListElementOption($element, 'prefix').'update_time']=='')||($view_data[$this->getListElementOption($element, 'prefix').'update_time']=='0')) {
		$view_data[$this->getListElementOption($element, 'prefix').'update_time']='---';
	} else {
		if ($this->getListElementOption($element, 'month_asname')===true) {
			$view_data[$this->getListElementOption($element, 'prefix').'update_time']=strftime(str_replace('%m.', ' %B ', $this->getListElementOption($element, 'date_format')), $view_data[$this->getListElementOption($element, 'prefix').'update_time']).' '.\osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock'));
		} else {
			$view_data[$this->getListElementOption($element, 'prefix').'update_time']=strftime($this->getListElementOption($element, 'date_format'), $view_data[$this->getListElementOption($element, 'prefix').'update_time']).' '.\osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock'));
		}
	}
}

if ($this->getListElementOption($element, 'display_update_user')==true) {
	if (($view_data[$this->getListElementOption($element, 'prefix').'update_user_id']=='')||($view_data[$this->getListElementOption($element, 'prefix').'update_user_id']=='0')||(\VIS2\Core\Manager::getUsernameById($view_data[$this->getListElementOption($element, 'prefix').'update_user_id'])=='')) {
		$view_data[$this->getListElementOption($element, 'prefix').'update_user_id']='---';
	} else {
		$view_data[$this->getListElementOption($element, 'prefix').'update_user_id']=\osWFrame\Core\HTML::outputString(\VIS2\Core\Manager::getUsernameById($view_data[$this->getListElementOption($element, 'prefix').'update_user_id']));
	}
}

?>