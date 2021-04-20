<?php

$this->settings=['page_title'=>'VIS2:Manager Settings'];

if (($position=='run')&&(isset($_POST['next']))&&($_POST['next']=='next')) {
	$VIS2=new \osWFrame\Tools\VIS2($this);

	if ($VIS2->getJSONs()!==[]) {
		foreach ($VIS2->getJSONs() as $json) {
			if ($this->getJSONIntValue('vis2_tool_'.$json['name_intern'])==1) {
				if ($VIS2->runJSON($json['file'])===true) {
					\osWFrame\Core\MessageStack::addMessage('configure', 'success', ['msg'=>'VIS2: '.$json['name'].' configured successfully.']);
				} else {
					\osWFrame\Core\MessageStack::addMessage('configure', 'danger', ['msg'=>'VIS2: '.$json['name'].' could not configured.']);
				}
			}
		}
	} else {
		\osWFrame\Core\MessageStack::addMessage('configure', 'info', ['msg'=>'VIS2: creation/update was skipped (no projects available).']);
	}
}

if (($position=='run')&&(isset($_POST['prev']))&&($_POST['prev']=='prev')) {
	\osWFrame\Core\MessageStack::addMessage('configure', 'info', ['msg'=>'VIS2: creation/update was skipped (go to previous page).']);
}

?>