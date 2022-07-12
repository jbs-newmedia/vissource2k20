<?php

$this->settings=['page_title'=>'VIS: Settings'];

if (($position=='run')&&(isset($_POST['next']))&&($_POST['next']=='next')) {
	$VIS=new \osWFrame\Tools\VIS($this);

	if ($VIS->getJSONs()!==[]) {
		foreach ($VIS->getJSONs() as $json) {
			if ($this->getJSONIntValue('vis_tool_'.$json['name_intern'])==1) {
				if ($VIS->runJSON($json['file'])===true) {
					\osWFrame\Core\MessageStack::addMessage('configure', 'success', ['msg'=>'VIS: '.$json['name'].' configured successfully.']);
				} else {
					\osWFrame\Core\MessageStack::addMessage('configure', 'danger', ['msg'=>'VIS: '.$json['name'].' could not configured.']);
				}
			}
		}
	} else {
		$VIS->runJSON('');
		\osWFrame\Core\MessageStack::addMessage('configure', 'info', ['msg'=>'VIS: creation/update was skipped (no projects available).']);
	}
}

if (($position=='run')&&(isset($_POST['prev']))&&($_POST['prev']=='prev')) {
	\osWFrame\Core\MessageStack::addMessage('configure', 'info', ['msg'=>'VIS: creation/update was skipped (go to previous page).']);
}

?>