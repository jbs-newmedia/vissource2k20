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

foreach ($this->getAddElements() as $element=>$element_details) {
	if ((isset($element_details['name']))&&($element_details['name']!='')) {
		$vars_key[]=$element_details['name'];
		$vars_value[]=$element;
	}
}

// build selector
$selector_value=[];
$ddm_selector_array=$this->getGroupOption('selector', 'database');
if (($ddm_selector_array!='')&&($ddm_selector_array!=[])) {
	foreach ($ddm_selector_array as $key=>$value) {
		$vars_key[]=$key;
		$vars_value[]=$key;
		if (is_int($value)==true) {
			$selector_value[$key]=$value;
		} else {
			$selector_value[$key]='\''.$value.'\'';
		}
		$selector_value[$key]=$value;
	}
}

/**
 * @var \osWFrame\Core\Database $QsaveData
 */
$QsaveData=self::getConnection();
$QsaveData->prepare('INSERT INTO :table: (:vars_name:) VALUES (:vars_value:)');
$QsaveData->bindTable(':table:', $this->getGroupOption('table', 'database'));
$QsaveData->bindRaw(':vars_name:', implode(', ', $vars_key));
$QsaveData->bindRaw(':vars_value:', ':'.implode(':, :', $vars_value).':');
foreach ($this->getAddElements() as $element=>$element_details) {
	if ((isset($element_details['name']))&&($element_details['name']!='')) {
		$bindName=":$element:";
		$value=$this->getDoAddElementStorage($element);
		switch ($this->getAddElementValidation($element, 'module')) {
			case 'integer':
				$QsaveData->bindInt($bindName, intval($value));
				break;
			case 'float':
				$QsaveData->bindFloat($bindName, floatval($value));
				break;
			case 'crypt':
				$QsaveData->bindCrypt($bindName, strval($value));
				break;
			case 'raw':
				$QsaveData->bindRaw($bindName, strval($value));
				break;
			case 'select':
				$value===''?$QsaveData->bindRaw($bindName, 'NULL'):$QsaveData->bindString($bindName, strval($value));
				break;
			case 'string':
			default:
				$QsaveData->bindString($bindName, strval($value));
				break;
		}
	}
}
if ($selector_value!=[]) {
	foreach ($selector_value as $key=>$value) {
		if (is_int($value)===true) {
			$QsaveData->bindInt(':'.$key.':', intval($value));
		} else {
			$QsaveData->bindString(':'.$key.':', strval($value));
		}
	}
}
$QsaveData->execute();
$this->setIndexElementStorage($QsaveData->lastInsertId());
\osWFrame\Core\MessageStack::addMessage('ddm4_'.$this->getName(), 'success', ['msg'=>$this->getGroupMessage('add_success_title')]);

?>