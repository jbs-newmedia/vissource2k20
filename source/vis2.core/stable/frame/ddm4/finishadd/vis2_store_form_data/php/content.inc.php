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

foreach ($this->getAddElements() as $element=>$element_details) {
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
foreach ($this->getAddElements() as $element=>$element_details) {
	if ((isset($element_details['name']))&&($element_details['name']!='')) {
		switch ($this->getAddElementValidation($element, 'module')) {
			case 'integer':
				$QsaveData->bindInt(':'.$element.':', intval($this->getDoAddElementStorage($element)));
				break;
			case 'float':
				$QsaveData->bindFloat(':'.$element.':', floatval($this->getDoAddElementStorage($element)));
				break;
			case 'crypt':
				$QsaveData->bindCrypt(':'.$element.':', $this->getDoAddElementStorage($element));
				break;
			case 'raw':
				$QsaveData->bindRaw(':'.$element.':', $this->getDoAddElementStorage($element));
				break;
			case 'string':
			default:
				$QsaveData->bindString(':'.$element.':', $this->getDoAddElementStorage($element));
				break;
		}
	}
}
$QsaveData->execute();
$this->setIndexElementStorage($QsaveData->lastInsertId());
\osWFrame\Core\MessageStack::addMessage('ddm4_'.$this->getName(), 'success', ['msg'=>$this->getGroupMessage('add_success_title')]);

?>