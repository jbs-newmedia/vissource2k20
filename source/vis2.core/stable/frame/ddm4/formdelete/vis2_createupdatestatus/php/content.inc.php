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

if ((\osWFrame\Core\Settings::getAction()=='delete')||(\osWFrame\Core\Settings::getAction()=='dodelete')) {
	$Qselect=self::getConnection($this->getGroupOption('connection', 'database'));
	$Qselect->prepare('SELECT :elements: FROM :table: AS :alias: WHERE :name_index:=:value_index:');
	$Qselect->bindRaw(':elements:', implode(', ', [$this->getGroupOption('alias', 'database').'.'.$this->getDeleteElementOption($element, 'prefix').'create_time', $this->getGroupOption('alias', 'database').'.'.$this->getDeleteElementOption($element, 'prefix').'create_user_id', $this->getGroupOption('alias', 'database').'.'.$this->getDeleteElementOption($element, 'prefix').'update_time', $this->getGroupOption('alias', 'database').'.'.$this->getDeleteElementOption($element, 'prefix').'update_user_id']));
	$Qselect->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$Qselect->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$Qselect->bindRaw(':name_index:', $this->getGroupOption('alias', 'database').'.'.$this->getGroupOption('index', 'database'));
	if ($this->getGroupOption('db_index_type', 'database')=='string') {
		$Qselect->bindString(':value_index:', $this->getIndexElementStorage());
	} else {
		$Qselect->bindInt(':value_index:', intval($this->getIndexElementStorage()));
	}
	if ($Qselect->exec()==1) {
		$result=$Qselect->fetch();

		if (\osWFrame\Core\Settings::getAction()=='delete') {
			$this->setDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_time', $result[$this->getDeleteElementOption($element, 'prefix').'create_time']);
			$this->setDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'create_user_id', $result[$this->getDeleteElementOption($element, 'prefix').'create_user_id']);
			$this->setDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_time', $result[$this->getDeleteElementOption($element, 'prefix').'update_time']);
			$this->setDeleteElementStorage($this->getDeleteElementOption($element, 'prefix').'update_user_id', $result[$this->getDeleteElementOption($element, 'prefix').'update_user_id']);
		}
	}
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>