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

$ddm4_element=$this->getPreViewElement($element);

$ajax=false;

foreach ($ddm4_element['options']['data'] as $key=>$navigation_element) {
	if (isset($navigation_element['counter'])) {
		$ajax=true;
		$navigation_element['counter']->execute();
		$navigation_element['counter']->fetch();
		$ddm4_element['options']['data'][$key]['text'].=' ('.$navigation_element['counter']->getInt('counter').')';
	}
}

if (($ajax===true)&&(\osWFrame\Core\Settings::catchStringValue('ajax_element', '', 'pg')==$element)) {
	$json=[];
	foreach ($ddm4_element['options']['data'] as $key=>$navigation_element) {
		$json[$element.'_'.$key]=$navigation_element['text'];
	}
	\osWFrame\Core\Network::dieJSON($json);
}

if ($ajax===true) {
	$this->getTemplate()->addJSCodeHead('function ddm4_element_'.$element.'() {
		$.getJSON( "'.$this->getTemplate()->buildhrefLink($this->getDirectModule(), $this->getDirectParameters().'&ajax_element='.$element).'", function( data ) {
			var items = [];
			$.each( data, function( key, val ) {
				$(".ddm_element_"+key+" a").html(val);
			});
		});
 	}');

	$this->addAjaxFunction($element, 'ddm4_element_'.$element.'();');
}

$this->setViewElementOption($element, 'data', $ddm4_element['options']['data']);

?>