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

if (in_array(\osWFrame\Core\Settings::getAction(), ['search', 'add', 'edit', 'delete', '', 'log'])) {
	$this->addCSSCodeHead('
.page-wrapper-modal {
	width:100%;
}
');
}

# search
if (\osWFrame\Core\Settings::getAction()=='search') {
	$this->getTemplate()->addJSCodeHead('
function submitDDM4(del) {
	if (del===true) {
		$("input[name=ddm4_search_delete]").val(1);
	}
	$("form").submit();
}
function resetDDM4() {
	$("form").trigger("reset");
	$(".selectpicker").selectpicker("render");
}
');
	echo '<div class="page-wrapper-modal">';
	echo $this->getTemplate()->Form()->startForm('form_search', 'current', $this->getDirectParameters(), ['form_parameter'=>'enctype="multipart/form-data"']);
	foreach ($this->getSearchElements() as $element=>$options) {
		echo $this->parseFormSearchElementTPL($element, $options);
	}
	echo $this->getTemplate()->Form()->drawHiddenField('action', 'dosearch');
	echo $this->getTemplate()->Form()->drawHiddenField('ddm4_search_delete', 0);
	echo $this->getTemplate()->Form()->drawHiddenField($this->getGroupOption('index', 'database'), $this->getIndexElementStorage());
	echo $this->getTemplate()->Form()->endForm();
	echo '</div>';
}

# add
if (\osWFrame\Core\Settings::getAction()=='add') {
	$this->getTemplate()->addJSCodeHead('
function submitDDM4() {
	$("form").submit();
}
function resetDDM4() {
	$("form").trigger("reset");
	$(".selectpicker").selectpicker("render");
}
');
	echo '<div class="page-wrapper-modal">';
	echo $this->getTemplate()->Form()->startForm('form_add', 'current', $this->getDirectParameters(), ['form_parameter'=>'enctype="multipart/form-data"']);
	foreach ($this->getAddElements() as $element=>$options) {
		echo $this->parseFormAddElementTPL($element, $options);
	}
	echo $this->getTemplate()->Form()->drawHiddenField('action', 'doadd');
	echo $this->getTemplate()->Form()->drawHiddenField($this->getGroupOption('index', 'database'), $this->getIndexElementStorage());
	echo $this->getTemplate()->Form()->endForm();
	echo '</div>';
}

# edit
if (\osWFrame\Core\Settings::getAction()=='edit') {
	$this->getTemplate()->addJSCodeHead('
function submitDDM4() {
	$("form").submit();
}
function resetDDM4() {
	$("form").trigger("reset");
	$(".selectpicker").selectpicker("render");
}

run=true;

function checkDMM4() {
	if (run===true) {
		url="'.$this->getTemplate()->buildhrefLink('current', $this->getDirectParameters()).'";
		$.post(url, {
			action: "dolock",
			'.$this->getGroupOption('index', 'database').': "'.$this->getIndexElementStorage().'"
		}, function(data){});
		setTimeout(checkDMM4, 5000);
	}
}

$(function() {
	checkDMM4();
});

');
	echo '<div class="page-wrapper-modal">';

	if ($this->setLock(\osWFrame\Core\Settings::catchStringValue($this->getGroupOption('index', 'database')), $this->getGroupOption('index', 'database'), $this->getGroupOption('user_id', 'data'))!==true) {
		echo '<div class="alert alert-danger" role="alert" style="margin:15px 0px;">'.\osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('lock_error'), ['user'=>\VIS2\Core\Manager::getUsernameById($this->getLockUserId(\osWFrame\Core\Settings::catchStringValue($this->getGroupOption('index', 'database')), $this->getGroupOption('index', 'database'), $this->getGroupOption('user_id', 'data')))]).'</div>';
	}
	echo $this->getTemplate()->Form()->startForm('form_edit', 'current', $this->getDirectParameters(), ['form_parameter'=>'enctype="multipart/form-data"']);
	foreach ($this->getEditElements() as $element=>$options) {
		echo $this->parseFormEditElementTPL($element, $options);
	}
	echo $this->getTemplate()->Form()->drawHiddenField('action', 'doedit');
	echo $this->getTemplate()->Form()->drawHiddenField($this->getGroupOption('index', 'database'), $this->getIndexElementStorage());
	echo $this->getTemplate()->Form()->endForm();
	echo '</div>';
}

# delete
if (\osWFrame\Core\Settings::getAction()=='delete') {
	$this->getTemplate()->addJSCodeHead('
function submitDDM4() {
	$("form").submit();
}
function resetDDM4() {
	$("form").trigger("reset");
	$(".selectpicker").selectpicker("render");
}
');
	echo '<div class="page-wrapper-modal">';
	echo $this->getTemplate()->Form()->startForm('form_delete', 'current', $this->getDirectParameters(), ['form_parameter'=>'enctype="multipart/form-data"']);
	foreach ($this->getDeleteElements() as $element=>$options) {
		echo $this->parseFormDeleteElementTPL($element, $options);
	}
	echo $this->getTemplate()->Form()->drawHiddenField('action', 'dodelete');
	echo $this->getTemplate()->Form()->drawHiddenField($this->getGroupOption('index', 'database'), $this->getIndexElementStorage());
	echo $this->getTemplate()->Form()->endForm();
	echo '</div>';
}

# data
if (in_array(\osWFrame\Core\Settings::getAction(), ['', 'log'])) {


	if (\osWFrame\Core\Settings::getAction()!='') {
		$this->name.='_'.\osWFrame\Core\Settings::getAction();
		echo '<div class="page-wrapper-modal">';
	} else {
		echo '<div class="card shadow mb-4"><div class="card-body page-wrapper-form">';
	}

	if ($this->getPreViewElements()!=[]) {
		foreach ($this->getPreViewElements() as $element=>$options) {
			if (isset($options['module'])) {
				$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/view/'.$options['module'].'/tpl/content.tpl.php';
				if (file_exists($file)) {
					include $file;
				}
			}
		}
	}

	if ($this->getViewElements()!=[]) {
		foreach ($this->getViewElements() as $element=>$options) {
			if (isset($options['module'])) {
				$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame/ddm4/view/'.$options['module'].'/tpl/content.tpl.php';
				if (file_exists($file)) {
					include $file;
				}
			}
		}
	}

	if (\osWFrame\Core\Settings::getAction()!='') {
		echo '</div>';
	} else {
		echo '</div></div>';
	}

	?>

	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="ddm4modal_dialog_<?php echo $this->getName() ?>">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="justify-content: none; ">
					<h5 class="modal-title float-left" style="float:left !important;"></h5>
					<span class="float-right">
				<button type="button" class="float-right close" data-bs-dismiss="modal" aria-label="<?php echo $this->getGroupMessage('form_close') ?>"><i class="fa fa-window-close" aria-hidden="true"></i></button>
				<button type="button" class="float-right close resize" onclick="resizeDDM4Dialog()" aria-label="<?php echo $this->getGroupMessage('form_maximize') ?>"><i class="fa fa-window-maximize" aria-hidden="true"></i></button>
					</span>
				</div>
				<div class="modal-body"><p></p></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $this->getGroupMessage('form_close') ?></button>
				</div>
			</div>
		</div>
	</div>



	<div class="modal fade overflow-hidden pe-0 pb-4" id="ddm4_controller_<?php echo $this->getName() ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<div class="justify-content-end">
						<a type="button" class="resize" onclick="resizeDDM4Modal()" aria-label="<?php echo $this->getGroupMessage('form_maximize') ?>"><i class="fa fa-2x fa-fw fa-window-maximize text-muted" aria-hidden="true"></i></a>
						<a type="button" data-bs-dismiss="modal" data-bs-dismiss="modal" aria-label="<?php echo $this->getGroupMessage('form_close') ?>"><i class="fa fa-2x fa-fw fa-window-close text-muted" aria-hidden="true"></i></a>
					</div>
				</div>
				<div class="modal-body" style="padding:0px; margin:0px; overflow:hidden; overflow-y: auto;"></div>
				<div class="modal-footer">
					<button onclick="submitDDM4Modal_<?php echo $this->getName() ?>();" name="ddm4_button_submit" type="button" class="btn btn-primary ddm4_btn_search"><?php echo $this->getGroupMessage('form_search') ?></button>
					<button onclick="submitDDM4Modal_<?php echo $this->getName() ?>();" name="ddm4_button_submit" type="button" class="btn btn-primary ddm4_btn_add"><?php echo $this->getGroupMessage('form_add') ?></button>
					<button onclick="submitDDM4Modal_<?php echo $this->getName() ?>();" name="ddm4_button_submit" type="button" class="btn btn-primary ddm4_btn_edit"><?php echo $this->getGroupMessage('form_edit') ?></button>
					<button onclick="submitDDM4Modal_<?php echo $this->getName() ?>(true);" name="ddm4_button_delete" type="button" class="btn btn-danger ddm4_btn_delete"><?php echo $this->getGroupMessage('form_delete') ?></button>
					<button onclick="resetDDM4Modal_<?php echo $this->getName() ?>();" name="ddm4_button_reset" type="button" class="btn btn-secondary ddm4_btn_reset"><?php echo $this->getGroupMessage('form_reset') ?></button>
					<button name="ddm4_button_close" type="button" class="btn btn-secondary ddm4_btn_close" data-bs-dismiss="modal"><?php echo $this->getGroupMessage('form_close') ?></button>
					<button name="ddm4_button_cancel" type="button" class="btn btn-secondary ddm4_btn_cancel" data-bs-dismiss="modal"><?php echo $this->getGroupMessage('form_cancel') ?></button>
				</div>
			</div>
		</div>
	</div>

	<?php

	$this->getTemplate()->addJSCodeHead('


$( window ).resize(function() {
	sizeDDM4Modal();
});

function sizeDDM4Modal() {
	if ($("#ddm4_controller_'.$this->getName().'").hasClass("modal-fullscreen")) {
		$("#ddm4_controller_'.$this->getName().' .modal-dialog .ddm4_iframe_holder").css("height", ($( window ).height()-139)+"px");
		$("#ddm4_controller_'.$this->getName().' .modal-dialog .ddm4_iframe_content").css("height", ($( window ).height()-139)+"px");
	} else {
		if ($( window ).height()>800) {
			$("#ddm4_controller_'.$this->getName().' .modal-dialog .ddm4_iframe_holder").css("height", "700px");
			$("#ddm4_controller_'.$this->getName().' .modal-dialog .ddm4_iframe_content").css("height", "700px");
		} else {
			$("#ddm4_controller_'.$this->getName().' .modal-dialog .ddm4_iframe_holder").css("height", ($( window ).height()-200)+"px");
			$("#ddm4_controller_'.$this->getName().' .modal-dialog .ddm4_iframe_content").css("height", ($( window ).height()-200)+"px");
		}	
	}
}

function resizeDDM4Modal() {
	$("#ddm4_controller_'.$this->getName().'").toggleClass("modal-fullscreen", 1000, "easeOutSine");
	$("#ddm4_controller_'.$this->getName().' .modal-header .resize .fa").toggleClass("fa-window-maximize");
	$("#ddm4_controller_'.$this->getName().' .modal-header .resize .fa").toggleClass("fa-window-minimize");
	sizeDDM4Modal();
}

function resizeDDM4Dialog() {
	$("#ddm4modal_dialog_'.$this->getName().'").toggleClass("modal-fullscreen", 1000, "easeOutSine");
	$("#ddm4modal_dialog_'.$this->getName().' .modal-header .resize .fa").toggleClass("fa-window-maximize");
	$("#ddm4modal_dialog_'.$this->getName().' .modal-header .resize .fa").toggleClass("fa-window-minimize");
}

function openDDM4Notify_'.$this->getName().'(elem) {
	$.getScript($(elem).attr("pageName"), function() {});
	ddm4datatables.ajax.reload(null, false);
}


function openDDM4Modal_'.$this->getName().'(elem, title, mode, count) {
	if (!mode) {
		mode="";
	}
	if (!count) {
		count=0;
	}
	
	openDDM4CoreModal_'.$this->getName().'($(elem).attr("pageName"), title, mode, count)
}

function openDDM4CoreModal_'.$this->getName().'(url, title, mode, count) {
	if (!mode) {
		mode="";
	}
	if (!count) {
		count=0;
	}

	$("#ddm4_controller_'.$this->getName().'").removeClass("modal-log");
	if (mode=="add") {
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_add").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_reset").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="edit") {
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_edit").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_reset").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="delete") {
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_delete").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_reset").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="search") {
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_search").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_reset").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="search_edit") {
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_search").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_delete").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_reset").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="log") {
		$("#ddm4_controller_'.$this->getName().'").addClass("modal-log");
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_reset").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_close").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_cancel").hide();
	} else if (mode=="modal") {
		$("#ddm4_controller_'.$this->getName().'").addClass("modal-log");
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_reset").hide();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_close").show();
		$("#ddm4_controller_'.$this->getName().' .modal-footer .ddm4_btn_cancel").hide();
	}
	$("#ddm4_controller_'.$this->getName().' .modal-header h5").html(title);
	$("#ddm4_controller_'.$this->getName().'.modal .modal-body").html("<div class=\"ddm4_iframe_holder\"><iframe class=\"ddm4_iframe_content\"></iframe></div>");
	$("#ddm4_controller_'.$this->getName().' .ddm4_iframe_content").attr("src", url);
	/* $("#ddm4_controller_'.$this->getName().'.modal .modal-content").load(); */
	$("#ddm4_controller_'.$this->getName().'.modal").modal("show");
	sizeDDM4Modal();

	$("#ddm4_controller_'.$this->getName().'").on("hidden.bs.modal", function () {
		$(".ddm4_iframe_content")[0].contentWindow.run=false;
	});
}

function submitDDM4Modal_'.$this->getName().'(del) {
	$(".ddm4_iframe_content")[0].contentWindow.submitDDM4(del);
}

function resetDDM4Modal_'.$this->getName().'() {
	$(".ddm4_iframe_content")[0].contentWindow.resetDDM4();
}
');

}

?>