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

$this->setAddElementStorage($element, []);

if (\osWFrame\Core\Settings::getAction()=='doadd') {
	$ar_tool_user=[];
	foreach (\VIS2\Core\Manager::getUsers() as $user_id=>$user_name) {
		if ((isset($_POST[$element.'_'.$user_id]))&&($_POST[$element.'_'.$user_id]==1)) {
			$ar_tool_user[$user_id]=1;
		} else {
			$ar_tool_user[$user_id]=0;
			if (!isset($_POST[$element.'_'.$user_id])) {
				$_POST[$element.'_'.$user_id]=0;
			}
		}
	}
	$this->setDoAddElementStorage($element, $ar_tool_user);
}

$users=\VIS2\Core\Manager::getUsers();

if(count($users)>intval($this->getAddElementOption($element, 'search_mod_counter'))) {

$this->getTemplate()->addJSCodeHead('
function ddm4_function_'.$element.'() {
	$(".ddm_element_'.$element.' .form-check").each(function() {
		search=$("#'.$element.'_search").val().toLowerCase();
		if (((search!="")&&($(this).find("label").html().toLowerCase().indexOf(search)>=0))||($(this).find("input[type=checkbox]").is(":checked"))||(search=="*")) {
			$(this).removeClass("d-none");
		} else {
			$(this).addClass("d-none");
		}
	});
}

$(function () {
	$("#'.$element.'_search").attr("autocomplete","off");
	ddm4_function_'.$element.'();
});'
);

}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>