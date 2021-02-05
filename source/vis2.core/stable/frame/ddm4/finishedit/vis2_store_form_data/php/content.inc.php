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

$vars=[];
foreach ($this->getEditElements() as $element_name=>$element_details) {
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

foreach ($this->getEditElements() as $element_name=>$element_details) {
	if ((isset($element_details['name']))&&($element_details['name']!='')) {
		switch ($this->getEditElementValidation($element_name, 'module')) {
			case 'integer':
				$QsaveData->bindInt(':'.$element_name.':', intval($this->getDoEditElementStorage($element_name)));
				break;
			case 'float':
				$QsaveData->bindFloat(':'.$element_name.':', floatval($this->getDoEditElementStorage($element_name)));
				break;
			case 'crypt':
				$QsaveData->bindCrypt(':'.$element_name.':', $this->getDoEditElementStorage($element_name));
				break;
			case 'raw':
				$QsaveData->bindRaw(':'.$element_name.':', $this->getDoEditElementStorage($element_name));
				break;
			case 'string':
			default:
				$QsaveData->bindString(':'.$element_name.':', $this->getDoEditElementStorage($element_name));
				break;
		}
	}
}
$QsaveData->execute();

if ($this->getGroupOption('enable_log')===true) {
	$vars=[];

	if ($this->getFinishElementOption($element, 'group')!='') {
		$group=$this->getFinishElementOption($element, 'group');
	} else {
		$group=$this->getGroupOption('table', 'database');
	}

	foreach ($this->getEditElements() as $element_name=>$element_details) {
		if ((isset($element_details['name']))&&($element_details['name']!='')) {
			$value_old=$this->getEditElementStorage($element_name);
			$value_new=$this->getDoEditElementStorage($element_name);

			switch ($this->getEditElementValidation($element_name, 'module')) {
				case 'integer':
					$value_new=intval($value_new);
					break;
				case 'float':
					$value_new=floatval($value_new);
					$value_new=trim($value_new);
				case 'raw':
					break;
				case 'crypt':
					break;
				case 'raw':
					break;
				case 'string':
				default:
					break;
			}

			if ($value_old!=$value_new) {
				$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/list/'.$element_details['module'].'/php/log.inc.php';
				if (file_exists($file)) {
					include $file;
				}

				if ($this->getFinishElementOption($element, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_name, [$this->getFinishElementOption($element, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($element, 'createupdatestatus_prefix').'update_time'])) {
						\osWFrame\Core\DDM4_Log::addValue($group, $element_name, $this->getEditElementValue($element_name, 'module'), $value_old, $value_new, $this->getEditElementStorage($this->getFinishElementOption($element, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($this->getFinishElementOption($element, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($this->getFinishElementOption($element, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($this->getFinishElementOption($element, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					\osWFrame\Core\DDM4_Log::addValue($group, $element_name, $this->getEditElementValue($element_name, 'module'),$value_old, $value_new);
				}
			}
		}
	}
	\osWFrame\Core\DDM4_Log::writeValues($group, $this->getGroupOption('index', 'database'), $this->getIndexElementStorage());
}

\osWFrame\Core\MessageStack::addMessage('ddm4_'.$this->getName(), 'success', ['msg'=>$this->getGroupMessage('edit_success_title')]);

?>