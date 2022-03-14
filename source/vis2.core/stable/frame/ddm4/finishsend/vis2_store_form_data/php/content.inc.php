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

switch ($this->getFinishElementOption($element, 'mode')) {
	case 'update':
		$mode='update';
		break;
	default:
		$mode='insert';
		break;
}

if ($mode=='insert') {
	foreach ($this->getSendElements() as $element=>$element_details) {
		if ((isset($element_details['name']))&&($element_details['name']!='')) {
			$vars_key[]=$element_details['name'];
			$vars_value[]=$element;
		}
	}

	$QsaveData=self::getConnection();
	$QsaveData->prepare('INSERT INTO :table: (:vars_name:) VALUES (:vars_value:)');
	$QsaveData->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QsaveData->bindRaw(':vars_name:', implode(', ', $vars_key));
	$QsaveData->bindRaw(':vars_value:', ':'.implode(':, :', $vars_value).':');
	foreach ($this->getSendElements() as $element=>$element_details) {
		if ((isset($element_details['name']))&&($element_details['name']!='')) {
			switch ($this->getSendElementValidation($element, 'module')) {
				case 'integer':
					$QsaveData->bindInt(':'.$element.':', intval($this->getDoSendElementStorage($element)));
					break;
				case 'float':
					$QsaveData->bindFloat(':'.$element.':', floatval($this->getDoSendElementStorage($element)));
					break;
				case 'crypt':
					$QsaveData->bindCrypt(':'.$element.':', $this->getDoSendElementStorage($element));
					break;
				case 'raw':
					$QsaveData->bindRaw(':'.$element.':', $this->getDoSendElementStorage($element));
					break;
				case 'string':
				default:
					$QsaveData->bindString(':'.$element.':', $this->getDoSendElementStorage($element));
					break;
			}
		}
	}
	$QsaveData->execute();
	$this->setIndexElementStorage($QsaveData->lastInsertId());
	\osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>$this->getGroupMessage('send_success_title')]);
}

if ($mode=='update') {
	$vars=[];
	foreach ($this->getSendElements() as $element_name=>$element_details) {
		if ((isset($element_details['name']))&&($element_details['name']!='')) {
			$vars[]=$element_details['name'].'=:'.$element_name.':';
		}
	}

	$QsaveData=self::getConnection();
	$QsaveData->prepare('UPDATE :table: AS :alias: SET :vars: WHERE :name_index:=:value_index:');
	$QsaveData->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QsaveData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$QsaveData->bindRaw(':vars:', $this->getGroupOption('alias', 'database').'.'.implode(',  '.$this->getGroupOption('alias', 'database').'.', $vars));
	$QsaveData->bindRaw(':name_index:', $this->getGroupOption('alias', 'database').'.'.$this->getGroupOption('index', 'database'));
	if ($this->getGroupOption('db_index_type', 'database')=='string') {
		$QsaveData->bindString(':value_index:', $this->getIndexElementStorage());
	} else {
		$QsaveData->bindInt(':value_index:', intval($this->getIndexElementStorage()));
	}

	foreach ($this->getSendElements() as $element_name=>$element_details) {
		if ((isset($element_details['name']))&&($element_details['name']!='')) {
			switch ($this->getSendElementValidation($element_name, 'module')) {
				case 'integer':
					$QsaveData->bindInt(':'.$element_name.':', $this->getDoSendElementStorage($element_name));
					break;
				case 'float':
					$QsaveData->bindFloat(':'.$element_name.':', $this->getDoSendElementStorage($element_name));
					break;
				case 'crypt':
					$QsaveData->bindCrypt(':'.$element_name.':', $this->getDoSendElementStorage($element_name));
					break;
				case 'raw':
					$QsaveData->bindRaw(':'.$element_name.':', $this->getDoSendElementStorage($element_name));
					break;
				case 'string':
				default:
					$QsaveData->bindString(':'.$element_name.':', $this->getDoSendElementStorage($element_name));
					break;
			}
		}
	}
	$QsaveData->execute();

	\osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>$this->getGroupMessage('send_success_title')]);
}

?>