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

if (in_array(\osWFrame\Core\Settings::getAction(), ['log', 'log_ajax'])) {
	$titles=[];
	foreach ($this->getEditElements() as $element=>$element_details) {
		$titles[$element]=$element_details['title'];
		if (is_array($this->getEditElementOption($element, 'titles'))) {
			foreach ($this->getEditElementOption($element, 'titles') as $_key=>$_value) {
				$titles[$_key]=$_value;
			}
		}

	}

	if (is_array($this->getFinishElementOption($element, 'titles'))) {
		foreach ($this->getFinishElementOption($element, 'titles') as $_key=>$_value) {
			$titles[$_key]=$_value;
		}
	}

	if ($this->getFinishElementOption($element, 'group')!='') {
		$group=$this->getFinishElementOption($element, 'group');
	} else {
		$group=$this->getGroupOption('table', 'database');
	}

	$index_key=$this->getGroupOption('index', 'database');
	$index_value=\osWFrame\Core\Settings::catchValue($index_key, '', 'gp');

	$user_id=$this->getGroupOption('user_id', 'data');
	$tool=$this->getGroupOption('tool', 'data');
	$page=$this->getGroupOption('page', 'data');

	if (substr($this->getName(), -4, 4)!='_log') {
		$this->reset($this->getName().'_log');
	}

	/**
	 * DDM4 initialisieren
	 */
	$ddm4_object=[];
	$ddm4_object['general']=[];
	$ddm4_object['general']['engine']='vis2_datatables';
	$ddm4_object['general']['cache']=\osWFrame\Core\Settings::catchValue('ddm_cache', '', 'pg');
	$ddm4_object['general']['elements_per_page']=50;
	$ddm4_object['general']['enable_log']=true;
	$ddm4_object['data']=[];
	$ddm4_object['data']['user_id']=$user_id;
	$ddm4_object['messages']=[];
	$ddm4_object['messages']['data_noresults']='Keine Daten vorhanden';
	$ddm4_object['direct']=[];
	$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
	$ddm4_object['direct']['parameters']=[];
	$ddm4_object['direct']['parameters']['vistool']=$tool;
	$ddm4_object['direct']['parameters']['vispage']=$page;
	$ddm4_object['database']=[];
	$ddm4_object['database']['table']='ddm4_log';
	$ddm4_object['database']['alias']='tbl1';
	$ddm4_object['database']['index']='log_id';
	$ddm4_object['database']['index_type']='integer';
	$ddm4_object['database']['order']=[];
	$ddm4_object['database']['order']['log_value_time_new']='desc';
	$ddm4_object['database']['order_case']=[];
	$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

	$this->addGroup($ddm4_object);

	$this->setGroupOption('filter', [['and'=>[['key'=>'log_group', 'operator'=>'=', 'value'=>'\''.$group.'\''], ['key'=>'name_index', 'operator'=>'=', 'value'=>'\''.$index_key.'\''], ['key'=>'value_index', 'operator'=>'=', 'value'=>$index_value]]]], 'database');

	/*
	 * View: VIS2_Datatables
	 */
	$ddm4_elements['view']['vis2_datatables']=[];
	$ddm4_elements['view']['vis2_datatables']['module']='vis2_datatables';

	/*
	 * Data: Feld
	 */
	$ddm4_elements['data']['log_key']=[];
	$ddm4_elements['data']['log_key']['module']='ddm4_log_select';
	$ddm4_elements['data']['log_key']['title']='Feld';
	$ddm4_elements['data']['log_key']['name']='log_key';
	$ddm4_elements['data']['log_key']['options']=[];
	$ddm4_elements['data']['log_key']['options']['data']=$titles;

	/*
	 * Data: Modul
	 */
	$ddm4_elements['data']['log_module']=[];
	$ddm4_elements['data']['log_module']['module']='hidden';
	$ddm4_elements['data']['log_module']['title']='Modul';
	$ddm4_elements['data']['log_module']['name']='log_module';
	$ddm4_elements['data']['log_module']['_list']=[];
	$ddm4_elements['data']['log_module']['_list']['enabled']=true;
	/*
	 * Data: Datum (Neu)
	 */
	$ddm4_elements['data']['log_value_time_new']=[];
	$ddm4_elements['data']['log_value_time_new']['module']='timestamp';
	$ddm4_elements['data']['log_value_time_new']['title']='Datum (Neu)';
	$ddm4_elements['data']['log_value_time_new']['name']='log_value_time_new';
	$ddm4_elements['data']['log_value_time_new']['options']=[];
	$ddm4_elements['data']['log_value_time_new']['options']['order']=true;

	/*
	 * Data: Datum (Alt)
	 */
	$ddm4_elements['data']['log_value_time_old']=[];
	$ddm4_elements['data']['log_value_time_old']['module']='timestamp';
	$ddm4_elements['data']['log_value_time_old']['title']='Datum (Alt)';
	$ddm4_elements['data']['log_value_time_old']['name']='log_value_time_old';
	$ddm4_elements['data']['log_value_time_old']['options']=[];
	$ddm4_elements['data']['log_value_time_old']['options']['order']=true;

	/*
	 * Data: Benutzer (Neu)
	 */
	$ddm4_elements['data']['log_value_user_id_new']=[];
	$ddm4_elements['data']['log_value_user_id_new']['module']='select';
	$ddm4_elements['data']['log_value_user_id_new']['title']='Benutzer (Neu)';
	$ddm4_elements['data']['log_value_user_id_new']['name']='log_value_user_id_new';
	$ddm4_elements['data']['log_value_user_id_new']['options']=[];
	$ddm4_elements['data']['log_value_user_id_new']['options']['search']=true;
	$ddm4_elements['data']['log_value_user_id_new']['options']['data']=VIS2\Core\Manager::getUsers();

	/*
	 * Data: Benutzer (Alt)
	 */
	$ddm4_elements['data']['log_value_user_id_old']=[];
	$ddm4_elements['data']['log_value_user_id_old']['module']='select';
	$ddm4_elements['data']['log_value_user_id_old']['title']='Benutzer (Alt)';
	$ddm4_elements['data']['log_value_user_id_old']['name']='log_value_user_id_old';
	$ddm4_elements['data']['log_value_user_id_old']['options']=[];
	$ddm4_elements['data']['log_value_user_id_old']['options']['search']=true;
	$ddm4_elements['data']['log_value_user_id_old']['options']['data']=VIS2\Core\Manager::getUsers();

	/*
	 * Data: Wert (Neu)
	 */
	$ddm4_elements['data']['log_value_new']=[];
	$ddm4_elements['data']['log_value_new']['module']='ddm4_log_text';
	$ddm4_elements['data']['log_value_new']['title']='Wert (Neu)';
	$ddm4_elements['data']['log_value_new']['name']='log_value_new';
	$ddm4_elements['data']['log_value_new']['options']=[];
	$ddm4_elements['data']['log_value_new']['options']['search']=true;

	/*
	 * Data: Wert (Alt)
	 */
	$ddm4_elements['data']['log_value_old']=[];
	$ddm4_elements['data']['log_value_old']['module']='ddm4_log_text';
	$ddm4_elements['data']['log_value_old']['title']='Wert (Alt)';
	$ddm4_elements['data']['log_value_old']['name']='log_value_old';
	$ddm4_elements['data']['log_value_old']['options']=[];
	$ddm4_elements['data']['log_value_old']['options']['search']=true;

	/*
	 * Datenelemente hinzufügen
	 */
	foreach ($ddm4_elements as $key=>$ddm4_key_elements) {
		if ($ddm4_key_elements!==[]) {
			foreach ($ddm4_key_elements as $element_name=>$element_options) {
				$this->addElement($key, $element_name, $element_options);
			}
		}
	}

}

$this->readParameters();

switch (\osWFrame\Core\Settings::getAction()) {
	case 'search':
		if ($this->getCounter('search_elements')>0) {
			\osWFrame\Core\Settings::setAction('search');
			$_POST['modal']=1;
		} else {
			\osWFrame\Core\Settings::setAction('');
		}
		break;
	case 'dosearch':
		if ($this->getCounter('search_elements')>0) {
			\osWFrame\Core\Settings::setAction('dosearch');
			$_POST['modal']=1;
		} else {
			\osWFrame\Core\Settings::setAction('');
		}
		break;
	case 'add':
		if ($this->getCounter('add_elements')>0) {
			\osWFrame\Core\Settings::setAction('add');
			$_POST['modal']=1;
		} else {
			\osWFrame\Core\Settings::setAction('');
		}
		break;
	case 'doadd':
		if ($this->getCounter('add_elements')>0) {
			\osWFrame\Core\Settings::setAction('doadd');
			$_POST['modal']=1;
		} else {
			\osWFrame\Core\Settings::setAction('');
		}
		break;
	case 'edit':
		if ($this->getCounter('edit_elements')>0) {
			\osWFrame\Core\Settings::setAction('edit');
			$_POST['modal']=1;
		} else {
			\osWFrame\Core\Settings::setAction('');
		}
		if ($this->setLock(\osWFrame\Core\Settings::catchStringValue($this->getGroupOption('index', 'database')), $this->getGroupOption('index', 'database'), $this->getGroupOption('user_id', 'data'))!==true) {
			foreach ($this->getEditElements() as $element=>$element_details) {
				$element_details['options']['read_only']=true;
				$this->setReadOnly($element);
			}
			$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_edit").hide();
	});
	');
		}
		break;
	case 'doedit':
		if ($this->getCounter('edit_elements')>0) {
			\osWFrame\Core\Settings::setAction('doedit');
			$_POST['modal']=1;
		} else {
			\osWFrame\Core\Settings::setAction('');
		}
		if ($this->setLock(\osWFrame\Core\Settings::catchStringValue($this->getGroupOption('index', 'database')), $this->getGroupOption('index', 'database'), $this->getGroupOption('user_id', 'data'))!==true) {
			$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.refreshDDM4Elements_'.$this->getName().'();
		window.parent.ddm4datatables.ajax.reload(null, false);
		window.parent.$(".modal").modal("hide");
		window.parent.vis2_notify("'.addslashes(\osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('lock_error'), ['user'=>$this->getLockUserId(\osWFrame\Core\Settings::catchStringValue($this->getGroupOption('index', 'database')), $this->getGroupOption('index', 'database'), $this->getGroupOption('user_id', 'data'))])).'", "danger");
	});
	');
			\osWFrame\Core\Settings::setAction('');
		}
		break;
	case 'delete':
		if ($this->getCounter('delete_elements')>0) {
			\osWFrame\Core\Settings::setAction('delete');
			$_POST['modal']=1;
		} else {
			\osWFrame\Core\Settings::setAction('');
		}
		break;
	case 'dodelete':
		if ($this->getCounter('delete_elements')>0) {
			\osWFrame\Core\Settings::setAction('dodelete');
			$_POST['modal']=1;
		} else {
			\osWFrame\Core\Settings::setAction('');
		}
		break;
	case 'dolock':
		if ($this->setLock(\osWFrame\Core\Settings::catchStringValue($this->getGroupOption('index', 'database')), $this->getGroupOption('index', 'database'), $this->getGroupOption('user_id', 'data'))===true) {
			\osWFrame\Core\Network::dieJSON(['status'=>'Ok']);
		}
		\osWFrame\Core\Network::dieJSON(['status'=>'Error']);
		break;
	case 'log':
		\osWFrame\Core\Settings::setAction('log');
		break;
	case 'log_ajax':
		\osWFrame\Core\Settings::setAction('log_ajax');
		break;
	case 'ajax':
		\osWFrame\Core\Settings::setAction('ajax');
		break;
	default:
		\osWFrame\Core\Settings::setAction('');
		break;
}

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchValue('ddm_navigation_id', $this->getParameter('ddm_navigation_id'), 'pg'));

// Search
if ((\osWFrame\Core\Settings::getAction()=='search')||(\osWFrame\Core\Settings::getAction()=='dosearch')) {
	$load=false;

	$result=$this->getParameter('ddm_search_data');
	foreach ($this->getSearchElements() as $element=>$element_details) {
		if ((isset($element_details['name']))&&($element_details['name']!='')) {
			if (isset($result[$element_details['name']])) {
				$load=true;
				$this->setSearchElementStorage($element, $result[$element_details['name']]);
			}
		}
		if ((isset($element_details['name_array']))&&($element_details['name_array']!=[])) {
			foreach ($element_details['name_array'] as $_name) {
				if ($element_details['options']['prefix']!='') {
					if (isset($result[$element_details['options']['prefix'].$_name])) {
						$load=true;
						$this->setSearchElementStorage($element_details['options']['prefix'].$_name, $result[$element_details['options']['prefix'].$_name]);
					}
				} else {
					if (isset($result[$_name])) {
						$load=true;
						$this->setSearchElementStorage($element.'_'.$_name, $result[$_name]);
					}
				}
			}
		}
	}

	foreach ($this->getSearchElements() as $element=>$options) {
		$this->parseFormSearchElementPHP($element, $options);
	}

	if (\osWFrame\Core\Settings::getAction()=='dosearch') {
		foreach ($this->getSearchElements() as $element=>$options) {
			$options=$this->getSearchElementValue($element, 'validation');
			if ($options!='') {
				$this->parseParserSearchElementPHP($element, $options);
			}
		}

		if ($this->getTemplate()->Form()->hasErrorMessages()===true) {
			\osWFrame\Core\Settings::setAction('search');
		} else {
			foreach ($this->getSearchElements() as $element=>$options) {
				$this->parseFinishSearchElementPHP($element, $options);
			}

			foreach ($this->getFinishElements() as $element=>$options) {
				$this->parseFinishSearchElementPHP($element, $options);
			}

			foreach ($this->getAfterFinishElements() as $element=>$options) {
				$this->parseFinishSearchElementPHP($element, $options);
			}

			$data=[];
			if (\osWFrame\Core\Settings::catchValue('ddm4_search_delete', '0', 'p')=='0') {
				foreach ($this->getSearchElements() as $element=>$element_details) {
					if ((isset($element_details['name']))&&($element_details['name']!='')) {
						if ($this->getSearchElementStorage($element)!='') {
							$data[$element]=$this->getSearchElementStorage($element);
						}
					}
					if ((isset($element_details['name_array']))&&($element_details['name_array']!=[])) {
						foreach ($element_details['name_array'] as $_name) {
							if ($element_details['options']['prefix']!='') {
								$data[$element_details['options']['prefix'].$_name]=$this->getSearchElementStorage($element_details['options']['prefix'].$_name);
							} else {
								$data[$element.'_'.$_name]=$this->getSearchElementStorage($element.'_'.$_name);
							}
						}
					}
				}
			}

			$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.refreshDDM4Elements_'.$this->getName().'();
		window.parent.ddm4datatables.ajax.reload(null, false);
		window.parent.$(".modal").modal("hide");
	});
					');
		}

		if ($data==[]) {
			$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.$("#ddm4_button_search_edit").removeClass("d-block d-lg-inline");
		window.parent.$("#ddm4_button_search_edit").addClass("d-none");
		window.parent.$("#ddm4_button_search_submit").removeClass("d-none");
		window.parent.$("#ddm4_button_search_submit").addClass("d-block d-lg-inline");
	});
				');
		} else {
			$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.$("#ddm4_button_search_edit").removeClass("d-none");
		window.parent.$("#ddm4_button_search_edit").addClass("d-block d-lg-inline");
		window.parent.$("#ddm4_button_search_submit").removeClass("d-block d-lg-inline");
		window.parent.$("#ddm4_button_search_submit").addClass("d-none");
	});
				');
		}
		$this->setParameter('ddm_search_data', $data);
		$this->storeParameters();
	}
}

// Add
if ((\osWFrame\Core\Settings::getAction()=='add')||(\osWFrame\Core\Settings::getAction()=='doadd')) {
	$this->setIndexElementStorage(\osWFrame\Core\Settings::catchValue(0, '', 'pg'));

	foreach ($this->getAddElements() as $element=>$options) {
		$this->parseFormAddElementPHP($element, $options);
	}

	if (\osWFrame\Core\Settings::getAction()=='doadd') {
		if (strlen(\osWFrame\Core\Settings::catchValue('btn_ddm_cancel', '', 'p'))>0) {
			\osWFrame\Core\Settings::setAction('');
			$_POST=[];
		}
	}

	if ((\osWFrame\Core\Settings::getAction()=='add')||(\osWFrame\Core\Settings::getAction()=='doadd')) {
		foreach ($this->getAddElements() as $element=>$element_details) {
			if ((isset($element_details['name']))&&($element_details['name']!='')) {
				$this->setAddElementStorage($element, $this->getAddElementOption($element, 'default_value'));
			}
		}

		if (\osWFrame\Core\Settings::getAction()=='doadd') {
			foreach ($this->getAddElements() as $element=>$options) {
				$options=$this->getAddElementValue($element, 'validation');
				if ($options!='') {
					$this->parseParserAddElementPHP($element, $options);
				}
			}

			if ($this->getTemplate()->Form()->hasErrorMessages()===true) {
				\osWFrame\Core\Settings::setAction('add');
			} else {
				foreach ($this->getAddElements() as $element=>$options) {
					$this->parseFinishAddElementPHP($element, $options);
				}

				foreach ($this->getFinishElements() as $element=>$options) {
					$this->parseFinishAddElementPHP($element, $options);
				}

				foreach ($this->getAfterFinishElements() as $element=>$options) {
					$this->parseFinishAddElementPHP($element, $options);
				}

				$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.refreshDDM4Elements_'.$this->getName().'();
		window.parent.ddm4datatables.ajax.reload(null, false);
		window.parent.$(".modal").modal("hide");
	});
					');
			}
		}
	} else {
		osWFrame\Core\SessionMessageStack::addMessage('session', 'danger', ['msg'=>$this->getGroupMessage('add_load_error_title')]);
		$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.ddm4datatables.ajax.reload(null, false);
		window.parent.$(".modal").modal("hide");
	});
		');
	}
}

// Edit
if ((\osWFrame\Core\Settings::getAction()=='edit')||(\osWFrame\Core\Settings::getAction()=='doedit')) {
	$this->setIndexElementStorage(\osWFrame\Core\Settings::catchValue($this->getGroupOption('index', 'database'), '', 'pg'));
	$database_where_string='';

	// build selector
	$ddm_selector_array=$this->getGroupOption('selector', 'database');
	if (($ddm_selector_array!='')&&($ddm_selector_array!=[])) {
		$ar_values=[];
		foreach ($ddm_selector_array as $key=>$value) {
			if (is_int($value)==true) {
				$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$key.'='.$value;
			} else {
				$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$key.'=\''.$value.'\'';
			}
		}
		$database_where_string.=' AND ('.implode(' AND ', $ar_values).')';
	}

	// build filter
	$ddm_filter_array=$this->getGroupOption('filter', 'database');
	if (($ddm_filter_array!='')&&($ddm_filter_array!=[])) {
		$ddm_filter=[];
		foreach ($ddm_filter_array as $filter) {
			$ar_values=[];
			foreach ($filter as $logic=>$elements) {
				foreach ($elements as $element) {
					$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$element['key'].$element['operator'].$element['value'];
				}
			}
			$ddm_filter[]='('.implode(' '.strtoupper($logic).' ', $ar_values).')';
		}
		$database_where_string.=' AND ('.implode(' OR ', $ddm_filter).')';
	}

	$QloadData=self::getConnection();
	$QloadData->prepare('SELECT :vars: FROM :table: AS :alias: WHERE :name_index:=:value_index: :where:');
	$QloadData->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QloadData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$QloadData->bindRaw(':vars:', $this->getGroupOption('alias', 'database').'.'.implode(', '.$this->getGroupOption('alias', 'database').'.', $this->getEditElementsName()));
	$QloadData->bindRaw(':name_index:', $this->getGroupOption('alias', 'database').'.'.$this->getGroupOption('index', 'database'));
	if ($this->getGroupOption('db_index_type', 'database')=='string') {
		$QloadData->bindString(':value_index:', $this->getIndexElementStorage());
	} else {
		$QloadData->bindInt(':value_index:', $this->getIndexElementStorage());
	}
	$QloadData->bindRaw(':where:', $database_where_string);
	if ($QloadData->exec()===1) {
		$result=$QloadData->fetch();
		foreach ($this->getEditElements() as $element=>$element_details) {
			if ((isset($element_details['enabled']))&&($element_details['enabled']===true)) {
				if ((isset($element_details['name']))&&($element_details['name']!='')) {
					$this->setEditElementStorage($element, $result[$element_details['name']]);
				} elseif (!isset($element_details['name'])) {
					if ((isset($element_details['options']))&&(isset($element_details['options']['default_value']))) {
						$this->setEditElementStorage($element, $element_details['options']['default_value']);
					}
				}
				if ((isset($element_details['name_array']))&&($element_details['name_array']!=[])) {
					foreach ($element_details['name_array'] as $_name) {
						if ($element_details['options']['prefix']!='') {
							$this->setEditElementStorage($element_details['options']['prefix'].$_name, $result[$element_details['options']['prefix'].$_name]);
						} else {
							$this->setEditElementStorage($element.'_'.$_name, $result[$_name]);
						}
					}
				}
			}
		}
	} else {
		osWFrame\Core\SessionMessageStack::addMessage('session', 'danger', ['msg'=>$this->getGroupMessage('edit_load_error_title')]);
		$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.ddm4datatables.ajax.reload(null, false);
		window.parent.$(".modal").modal("hide");
	});
		');
	}

	foreach ($this->getEditElements() as $element=>$options) {
		$this->parseFormEditElementPHP($element, $options);
	}

	if (\osWFrame\Core\Settings::getAction()=='doedit') {
		foreach ($this->getEditElements() as $element=>$options) {
			$options=$this->getEditElementValue($element, 'validation');
			if ($options!='') {
				$this->parseParserEditElementPHP($element, $options);
			}
		}

		if ($this->getTemplate()->Form()->hasErrorMessages()===true) {
			\osWFrame\Core\Settings::setAction('edit');
		} else {
			foreach ($this->getEditElements() as $element=>$options) {
				$this->parseFinishEditElementPHP($element, $options);
			}

			foreach ($this->getFinishElements() as $element=>$options) {
				$this->parseFinishEditElementPHP($element, $options);
			}

			foreach ($this->getAfterFinishElements() as $element=>$options) {
				$this->parseFinishEditElementPHP($element, $options);
			}

			$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.refreshDDM4Elements_'.$this->getName().'();
		window.parent.ddm4datatables.ajax.reload(null, false);
		window.parent.$(".modal").modal("hide");
	});
				');
		}
	}
}

// Delete
if ((\osWFrame\Core\Settings::getAction()=='delete')||(\osWFrame\Core\Settings::getAction()=='dodelete')) {
	$this->setIndexElementStorage(\osWFrame\Core\Settings::catchValue($this->getGroupOption('index', 'database'), '', 'pg'));
	$database_where_string='';

	// build selector
	$ddm_selector_array=$this->getGroupOption('selector', 'database');
	if (($ddm_selector_array!='')&&($ddm_selector_array!=[])) {
		$ar_values=[];
		foreach ($ddm_selector_array as $key=>$value) {
			if (is_int($value)==true) {
				$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$key.'='.$value;
			} else {
				$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$key.'=\''.$value.'\'';
			}
		}
		$database_where_string.=' AND ('.implode(' AND ', $ar_values).')';
	}

	// build filter
	$ddm_filter_array=$this->getGroupOption('filter', 'database');
	if (($ddm_filter_array!='')&&($ddm_filter_array!=[])) {
		$ddm_filter=[];
		foreach ($ddm_filter_array as $filter) {
			$ar_values=[];
			foreach ($filter as $logic=>$elements) {
				foreach ($elements as $element) {
					$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$element['key'].$element['operator'].$element['value'];
				}
			}
			$ddm_filter[]='('.implode(' '.strtoupper($logic).' ', $ar_values).')';
		}
		$database_where_string.=' AND ('.implode(' OR ', $ddm_filter).')';
	}

	$QloadData=self::getConnection();
	$QloadData->prepare('SELECT :vars: FROM :table: AS :alias: WHERE :name_index:=:value_index: :where:');
	$QloadData->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QloadData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$QloadData->bindRaw(':vars:', $this->getGroupOption('alias', 'database').'.'.implode(', '.$this->getGroupOption('alias', 'database').'.', $this->getDeleteElementsName()));
	$QloadData->bindRaw(':name_index:', $this->getGroupOption('alias', 'database').'.'.$this->getGroupOption('index', 'database'));
	if ($this->getGroupOption('db_index_type', 'database')=='string') {
		$QloadData->bindString(':value_index:', $this->getIndexElementStorage());
	} else {
		$QloadData->bindInt(':value_index:', $this->getIndexElementStorage());
	}
	$QloadData->bindRaw(':where:', $database_where_string);
	if ($QloadData->exec()===1) {
		$result=$QloadData->fetch();
		foreach ($this->getDeleteElements() as $element=>$element_details) {
			if ((isset($element_details['enabled']))&&($element_details['enabled']===true)) {
				if ((isset($element_details['name']))&&($element_details['name']!='')) {
					$this->setDeleteElementStorage($element, $result[$element_details['name']]);
				}
				if ((isset($element_details['name_array']))&&($element_details['name_array']!=[])) {
					foreach ($element_details['name_array'] as $_name) {
						if ($element_details['options']['prefix']!='') {
							$this->setDeleteElementStorage($element.'_'.$element_details['options']['prefix'].$_name, $result[$element_details['options']['prefix'].$_name]);
						} else {
							$this->setDeleteElementStorage($element.'_'.$_name, $result[$_name]);
						}
					}
				}
			}
		}
	} else {
		osWFrame\Core\SessionMessageStack::addMessage('session', 'danger', ['msg'=>$this->getGroupMessage('delete_load_error_title')]);
		$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.ddm4datatables.ajax.reload(null, false);
		window.parent.$(".modal").modal("hide");
	});
		');
	}

	foreach ($this->getDeleteElements() as $element=>$options) {
		$this->parseFormDeleteElementPHP($element, $options);
	}

	if (\osWFrame\Core\Settings::getAction()=='dodelete') {
		if (strlen(\osWFrame\Core\Settings::catchValue('btn_ddm_cancel', '', 'p'))>0) {
			\osWFrame\Core\Settings::setAction('');
			$_POST=[];
		}
	}

	if (\osWFrame\Core\Settings::getAction()=='dodelete') {
		if ($this->getTemplate()->Form()->hasErrorMessages()===true) {
			\osWFrame\Core\Settings::setAction('delete');
		} else {
			foreach ($this->getDeleteElements() as $element=>$options) {
				$this->parseFinishDeleteElementPHP($element, $options);
			}

			foreach ($this->getFinishElements() as $element=>$options) {
				$this->parseFinishDeleteElementPHP($element, $options);
			}

			foreach ($this->getAfterFinishElements() as $element=>$options) {
				$this->parseFinishDeleteElementPHP($element, $options);
			}

			$this->getTemplate()->addJSCodeHead('
	$(function() {
		window.parent.refreshDDM4Elements_'.$this->getName().'();
		window.parent.ddm4datatables.ajax.reload(null, false);
		window.parent.$(".modal").modal("hide");
	});
				');
		}
	}
}

if (in_array(\osWFrame\Core\Settings::getAction(), ['', 'ajax', 'log', 'log_ajax'])) {
	$vars=[];
	$_order=[];
	$_search=[];
	$_hidden=[];
	$_columns=[];

	$_elements=$this->getListElements();
	foreach ($_elements as $element=>$options) {
		if ((isset($options['name']))&&($options['name']!='')) {
			$vars[]=$options['name'];
		}

		if ((isset($options['options']))&&(isset($options['options']['order']))&&($options['options']['order']==true)) {
			if (isset($options['name'])) {
				$_order[$options['name']]=$options['name'];
			}
		}

		if ((isset($options['options']))&&(isset($options['options']['hidden']))&&($options['options']['hidden']==true)) {
			if (isset($options['name'])) {
				$_hidden[$options['name']]=$options['name'];
			}
		}

		if ((isset($options['options']))&&(isset($options['options']['search']))&&($options['options']['search']==true)) {
			$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/list/'.$options['module'].'/php/search_pre.inc.php';
			if (file_exists($file)) {
				include $file;
			}
		}

		if (isset($options['name_array'])) {
			foreach ($options['name_array'] as $name) {
				if ($options['options']['prefix']!='') {
					$vars[]=$options['options']['prefix'].$name;
				} else {
					$vars[]=$name;
				}
			}
		}
	}
}

// ajax
if (in_array(\osWFrame\Core\Settings::getAction(), ['ajax', 'log_ajax'])) {
	// get parameters
	$columns=\osWFrame\Core\Settings::catchValue('columns', '', 'p');
	$order=\osWFrame\Core\Settings::catchValue('order', '', 'p');
	$search=\osWFrame\Core\Settings::catchValue('search', '', 'p');

	// Get order form group-config
	$ddm_order_case_array=$this->getGroupOption('order_case', 'database');
	$ddm_order_case_array_new=[];
	if ((is_array($ddm_order_case_array))&&($ddm_order_case_array!=[])) {
		foreach ($ddm_order_case_array as $key=>$value) {
			$ddm_order_case_array_new[$this->getGroupOption('alias', 'database').'.'.$key]=$value;
		}
		$ddm_order_case_array=$ddm_order_case_array_new;
	} else {
		$ddm_order_case_array=[];
	}

	// build order by
	$database_order_array=[];
	$database_order_string='';
	if (($order!=[])&&($order!='')) {
		foreach ($order as $key=>$values) {
			if ((isset($columns[$order[$key]['column']]))&&(in_array($columns[$order[$key]['column']]['data'], $vars))) {
				if (isset($ddm_order_case_array[$this->getGroupOption('alias', 'database').'.'.$columns[$order[$key]['column']]['data']])) {
					$sql='';
					$sql.='CASE '.$this->getGroupOption('alias', 'database').'.'.$columns[$order[$key]['column']]['data'].' ';
					$i=0;
					if ($order[$key]['dir']=='asc') {
						foreach ($ddm_order_case_array[$this->getGroupOption('alias', 'database').'.'.$columns[$order[$key]['column']]['data']] as $k=>$v) {
							$i++;
							$sql.='WHEN '.$this->connection->escapteString($k).' THEN '.$i.' ';
						}
					} else {
						foreach (array_reverse($ddm_order_case_array[$this->getGroupOption('alias', 'database').'.'.$columns[$order[$key]['column']]['data']], true) as $k=>$v) {
							$i++;
							$sql.='WHEN '.$this->connection->escapteString($k).' THEN '.$i.' ';
						}
					}
					$sql.='END';
					$database_order_array[]=$sql;
				} else {
					$database_order_array[]=$this->getGroupOption('alias', 'database').'.'.$columns[$order[$key]['column']]['data'].' '.$order[$key]['dir'];
				}
			}
		}
	}
	if (($database_order_array!='')&&($database_order_array!=[])) {
		$database_order_string=' ORDER BY '.implode(', ', $database_order_array);
	}

	// init search
	$ddm_search_case_array_all=[];
	$ddm_search_filter_case_array_all=[];

	// build quick-search
	$ddm_search_case_array=[];
	if ((is_array($search)&&(isset($search['value']))&&($search['value']!=''))&&($_search!=[])) {
		foreach ($_search as $key) {
			$options=$_elements[$key];
			$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/list/'.$options['module'].'/php/search.inc.php';
			if (file_exists($file)) {
				include $file;
			}
		}
	}
	if ($ddm_search_case_array!=[]) {
		$ddm_search_case_array_all[]='('.implode(' OR ', $ddm_search_case_array).')';
	}

	// build extended-search
	$ddm_search_case_array=[];
	$data=$this->getParameter('ddm_search_data');
	foreach ($this->getSearchElements() as $element=>$options) {
		if ((isset($options['validation']))&&(isset($options['validation']['module']))) {
			$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/list/'.$options['module'].'/php/search_ext.inc.php';
			if (file_exists($file)) {
				include $file;
			}
		}
	}
	if ($ddm_search_case_array!=[]) {
		$ddm_search_case_array_all[]='('.implode(' AND ', $ddm_search_case_array).')';
	}

	// build selector
	$ddm_search_case_array=[];
	$ddm_selector_array=$this->getGroupOption('selector', 'database');
	if (($ddm_selector_array!='')&&($ddm_selector_array!=[])) {
		$ar_values=[];
		foreach ($ddm_selector_array as $key=>$value) {
			if (is_int($value)==true) {
				$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$key.'='.$value;
			} else {
				$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$key.'=\''.$value.'\'';
			}
		}
		$ddm_search_case_array[]='('.implode(' AND ', $ar_values).')';
	}
	if ($ddm_search_case_array!=[]) {
		$ddm_search_case_array_all[]='('.implode(' AND ', $ddm_search_case_array).')';
		$ddm_search_filter_case_array_all[]='('.implode(' AND ', $ddm_search_case_array).')';
	}

	// build filter
	$ddm_search_case_array=[];
	$ddm_filter_array=$this->getGroupOption('filter', 'database');
	if (($ddm_filter_array!='')&&($ddm_filter_array!=[])) {
		foreach ($ddm_filter_array as $filter) {
			$ar_values=[];
			foreach ($filter as $logic=>$elements) {
				foreach ($elements as $element) {
					$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$element['key'].$element['operator'].$element['value'];
				}
			}
			$ddm_search_case_array[]='('.implode(' '.strtoupper($logic).' ', $ar_values).')';
		}
	}
	if ($ddm_search_case_array!=[]) {
		$ddm_search_case_array_all[]='('.implode(' AND ', $ddm_search_case_array).')';
		$ddm_search_filter_case_array_all[]='('.implode(' AND ', $ddm_search_case_array).')';
	}

	// build select-query
	$database_search_string='1';
	if ($ddm_search_case_array_all!=[]) {
		$database_search_string='('.implode(' AND ', $ddm_search_case_array_all).')';
	}
	$database_search_filter_string='1';
	if ($ddm_search_filter_case_array_all!=[]) {
		$database_search_filter_string='('.implode(' AND ', $ddm_search_filter_case_array_all).')';
	}

	// load complete list-count

	$QgetDataLimit=self::getConnection();
	$QgetDataLimit->prepare('SELECT :index: FROM :table: AS :alias: WHERE :search_filter:');
	$QgetDataLimit->bindRaw(':index:', $this->getGroupOption('alias', 'database').'.'.$this->getGroupOption('index', 'database'));
	$QgetDataLimit->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QgetDataLimit->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$QgetDataLimit->bindRaw(':search_filter:', $database_search_filter_string);
	$datalimit=$QgetDataLimit->exec();

	$this->ddm['storage']['view']=[];
	$this->ddm['storage']['view']['data']=[];

	// load list
	$QgetData=self::getConnection();
	$QgetData->prepare('SELECT :index:, :vars: FROM :table: AS :alias: WHERE :search: :order:');
	$QgetData->bindRaw(':index:', $this->getGroupOption('alias', 'database').'.'.$this->getGroupOption('index', 'database'));
	$QgetData->bindRaw(':vars:', $this->getGroupOption('alias', 'database').'.'.implode(', '.$this->getGroupOption('alias', 'database').'.', $vars));
	$QgetData->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QgetData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$QgetData->bindRaw(':search:', $database_search_string);
	$QgetData->bindRaw(':order:', $database_order_string);
	$QgetData->bindLimit($this->getGroupOption('alias', 'database').'.'.$this->getGroupOption('index', 'database'), osWFrame\Core\Settings::catchValue('length', 1, 'gp'), ((\osWFrame\Core\Settings::catchValue('start', 0, 'gp')/osWFrame\Core\Settings::catchValue('length', 1, 'gp'))+1), 'draw');
	foreach ($QgetData->query() as $view_data) {
		$this->incCounter('storage_view_elements');
		foreach ($this->getListElements() as $element=>$options) {
			$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/list/'.$options['module'].'/tpl/content.tpl.php';
			if (file_exists($file)) {
				include $file;
			}
		}
		$this->ddm['storage']['view']['data'][]=$view_data;
	}
	$limitrows=$QgetData->getLimitRows();
	$this->addParameter('ddm_page', $limitrows['current_page_number']);

	$this->ddm['storage']['view']['limitrows']=$limitrows;

	\osWFrame\Core\Network::dieJSON(['draw'=>\osWFrame\Core\Settings::catchValue('draw', 1, 'gp'), 'recordsTotal'=>$datalimit, 'recordsFiltered'=>$this->ddm['storage']['view']['limitrows']['number_of_rows'], 'data'=>$this->ddm['storage']['view']['data']]);
}

// list
if (in_array(\osWFrame\Core\Settings::getAction(), ['', 'log'])) {
	if ($this->getPreViewElements()!=[]) {
		foreach ($this->getPreViewElements() as $element=>$options) {
			if (isset($options['module'])) {
				$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/view/'.$options['module'].'/php/content.inc.php';
				if (file_exists($file)) {
					include $file;
				}
			}
		}
	}

	if ($this->getViewElements()!=[]) {
		foreach ($this->getViewElements() as $element=>$options) {
			if (isset($options['module'])) {
				$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/view/'.$options['module'].'/php/content.inc.php';
				if (file_exists($file)) {
					include $file;
				}
			}
		}
	}

	$config_order=$this->getGroupOption('order', 'database');
	$ajax_order=$config_order;
	$ajax_columnDefs=[];

	$i=0;
	$c=count($_columns);
	foreach ($_columns as $element) {
		if ($i==($c-1)) {
			$p=0;
		} else {
			$p=$i+1;
		}
		$ajax_columnDefs[]='{ "data": "'.$element['name'].'", "responsivePriority": '.$p.', "orderable": '.(($element['order']===true)?'true':'false').', "searchable": '.(($element['search']===true)?'true':'false').', "visible": '.((isset($element['hidden'])&&$element['hidden']===true)?'false':'true').', "targets": '.$i.' }';
		if ((isset($config_order[$element['name']]))&&($element['order']===true)) {
			$config_order[$element['name']]='['.$i.', "'.$config_order[$element['name']].'"]';
			$ajax_order[$element['name']]=true;
		}
		$i++;
	}

	$status_keys=$this->getGroupOption('status_keys');
	if (!is_array($status_keys)) {
		$status_keys=[];
	}

	$ajax_statuskeys=[];
	if ($status_keys!=[]) {
		foreach ($status_keys as $_key=>$values) {
			foreach ($values as $_value) {
				$ajax_statuskeys[]='if (data.'.$_key.'=="'.$_value['value'].'") { $(row).addClass("table-'.$_value['class'].'"); }';
			}
		}

	}

	if ($ajax_order=='') {
		$ajax_order=[];
	}

	foreach ($ajax_order as $key=>$value) {
		if ($value===true) {
			$ajax_order[$key]=$config_order[$key];
		} else {
			unset($ajax_order[$key]);
		}
	}

	$ajax='ajax';
	$value='';
	$count=$this->getGroupOption('elements_per_page', 'general');
	if (\osWFrame\Core\Settings::getAction()=='log') {
		$ajax='log_ajax';

		#			$index_key=$this->getGroupOption('index', 'database');
		#			$index_value=\osWFrame\Core\Settings::catchValue($index_key, '', 'gp');
		$value='"'.$index_key.'": \''.$index_value.'\'';
		$count=$this->getGroupOption('elements_per_page', 'general');
	}

	$count=intval($count);
	if ($count<=10) {
		$count=10;
	} elseif (($count>10)&&($count<=25)) {
		$count=25;
	} elseif (($count>25)&&($count<=50)) {
		$count=50;
	} else {
		$count=100;
	}

	$ajax='$(document).ready(function() {
		ddm4datatables=$(\'#ddm4_datatables_'.$this->getName().'\').DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"pagingType": \'full_numbers\',
			"iDisplayLength": '.$count.',
			"ajax": {
				"url": \''.$this->getTemplate()->buildhrefLink($this->getDirectModule(), $this->getDirectParameters()).'\',
				"data": {
					"action":\''.$ajax.'\',
					'.$value.'
				},
				"type": \'POST\',
			},
			"language": {
				"decimal": ",",
				"emptyTable": "'.$this->getGroupMessage('data_noresults').'",
				"info": "_START_ bis _END_ von _TOTAL_ Einträgen",
				"infoEmpty": "0 bis 0 von 0 Einträgen",
				"infoFiltered": "(gefiltert von _MAX_ Einträgen)",
				"infoPostFix": "",
				"thousands": ".",
				"lengthMenu": "_MENU_ Einträge anzeigen",
				"loadingRecords": "Wird geladen...",
				"processing": "Bitte warten...",
				"search": "Suche:",
				"zeroRecords": "'.$this->getGroupMessage('data_noresults').'",
				"paginate": {
					"first": "Erste",
					"last": "Letzte",
					"next": "Nächste",
					"previous": "Zurück"
				},
				"aria": {
					"sortAscending": ": aktivieren, um Spalte aufsteigend zu sortieren",
					"sortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
				}
			},
	 		"order": ['.implode(",", $ajax_order).'],
			"columnDefs": [
				'.implode(",\n			", $ajax_columnDefs).'
			],
			"rowCallback": function( row, data, index ) {
	'.implode("\n			", $ajax_statuskeys).'
			}
		});
	});';

	$this->getTemplate()->addJSCodeHead($ajax);

	$this->getTemplate()->addJSCodeHead('function refreshDDM4Elements_'.$this->getName().'(elem) {
		'.implode("\n", $this->getAjaxFunctions()).'
	}');

	$this->getTemplate()->addJSCodeHead('function openDDM4Dialog_'.$this->getName().'(elem) {
		$("#ddm4modal_dialog_'.$this->getName().' .modal-header h5").html($(elem).attr("pageTitle"));
		$("#ddm4modal_dialog_'.$this->getName().' .modal-body p").html($(elem).attr("pageName"));
		$("#ddm4modal_dialog_'.$this->getName().'.modal").modal("show");
	}');

	if (in_array(\osWFrame\Core\Settings::getAction(), [''])) {
		if (in_array(\osWFrame\Core\Settings::catchStringValue('ao'), ['add', 'edit', 'delete', 'search'])) {
			$this->getTemplate()->addJSCodeHead('$(document).ready(function() {
			openDDM4CoreModal_'.$this->getName().'(\''.$this->getTemplate()->buildhrefLink($this->getDirectModule(), 'action='.\osWFrame\Core\Settings::catchStringValue('ao').'&preload_id='.\osWFrame\Core\Settings::catchStringValue('preload_id').'&modal=1&'.$this->getDirectParameters(), false).'\', \''.$this->getGroupOption('add_title', 'messages').'\', \'add\', '.$this->getCounter('add_elements').');
			});');
		}
	}
}

$this->storeParameters();

?>