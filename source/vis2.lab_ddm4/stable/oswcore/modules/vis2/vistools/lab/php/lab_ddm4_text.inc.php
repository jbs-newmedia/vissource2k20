<?php declare(strict_types=0);

/**
 * This file is part of the VIS2:Lab package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2:Lab
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var \osWFrame\Core\Template $osW_Template
 * @var \VIS2\Core\Main $VIS2_Main
 * @var \VIS2\Core\Navigation $VIS2_Navigation
 * @var \VIS2\Core\User $VIS2_User
 *
 */

/*
 * DDM4 initialisieren
 */

use osWFrame\Core\Settings;
use osWFrame\Core\Template;
use VIS2\Core\Main;
use VIS2\Core\Manager;
use VIS2\Core\Navigation;
use VIS2\Core\User;

$ddm4_object = [];
$ddm4_object['general'] = [];
$ddm4_object['general']['engine'] = 'vis2_datatables';
$ddm4_object['general']['cache'] = Settings::catchValue('ddm_cache', '', 'pg');
$ddm4_object['general']['elements_per_page'] = 50;
$ddm4_object['general']['enable_log'] = true;
$ddm4_object['data'] = [];
$ddm4_object['data']['user_id'] = $VIS2_User->getId();
$ddm4_object['data']['tool'] = $VIS2_Main->getTool();
$ddm4_object['data']['page'] = $VIS2_Navigation->getPage();
$ddm4_object['direct'] = [];
$ddm4_object['direct']['module'] = Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters'] = [];
$ddm4_object['direct']['parameters']['vistool'] = $VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage'] = $VIS2_Navigation->getPage();
$ddm4_object['database'] = [];
$ddm4_object['database']['table'] = 'vis2_lab_ddm4_text';
$ddm4_object['database']['alias'] = 'tbl1';
$ddm4_object['database']['index'] = 'text_id';
$ddm4_object['database']['index_type'] = 'integer';
$ddm4_object['database']['order'] = [];
$ddm4_object['database']['order']['text_id'] = 'desc';
$ddm4_object['database']['order_case'] = [];
$ddm4_object['database']['order_case']['user_update_user_id'] = Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4 = new osWFrame\Core\DDM4($osW_Template, 'vis2_lab_ddm4_text', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements = $osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links = [];
$navigation_links[1] = [
    'navigation_id' => 1,
    'module' => $osW_DDM4->getDirectModule(),
    'parameter' => 'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage(),
    'text' => 'Default',
];
$navigation_links[2] = [
    'navigation_id' => 2,
    'module' => $osW_DDM4->getDirectModule(),
    'parameter' => 'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage(),
    'text' => 'String',
];
$navigation_links[3] = [
    'navigation_id' => 3,
    'module' => $osW_DDM4->getDirectModule(),
    'parameter' => 'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage(),
    'text' => 'Integer',
];
$navigation_links[4] = [
    'navigation_id' => 4,
    'module' => $osW_DDM4->getDirectModule(),
    'parameter' => 'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage(),
    'text' => 'Float',
];
$navigation_links[11] = [
    'navigation_id' => 11,
    'module' => $osW_DDM4->getDirectModule(),
    'parameter' => 'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage(),
    'text' => 'E-Mail',
];
$navigation_links[12] = [
    'navigation_id' => 12,
    'module' => $osW_DDM4->getDirectModule(),
    'parameter' => 'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage(),
    'text' => 'Url',
];
$navigation_links[21] = [
    'navigation_id' => 21,
    'module' => $osW_DDM4->getDirectModule(),
    'parameter' => 'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage(),
    'text' => 'RegEx',
];
$navigation_links[99] = [
    'navigation_id' => 99,
    'module' => $osW_DDM4->getDirectModule(),
    'parameter' => 'vistool=' . $VIS2_Main->getTool() . '&vispage=' . $VIS2_Navigation->getPage(),
    'text' => 'ReadOnly',
];

$osW_DDM4->readParameters();

$ddm_navigation_id = (int)(Settings::catchIntValue(
    'ddm_navigation_id',
    (int)($osW_DDM4->getParameter('ddm_navigation_id')),
    'pg'
));
if (!isset($navigation_links[$ddm_navigation_id])) {
    $ddm_navigation_id = 1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if ($ddm_navigation_id < 99) {
    $osW_DDM4->setGroupOption('filter', [
        [
            'and' => [
                [
                    'key' => 'navigation_id',
                    'operator' => '=',
                    'value' => $ddm_navigation_id,
                ],
            ],
        ],
    ], 'database');
}

/*
 * PreView: VIS2_Navigation
 */
$ddm4_elements['preview']['vis2_navigation'] = [];
$ddm4_elements['preview']['vis2_navigation']['module'] = 'vis2_navigation';
$ddm4_elements['preview']['vis2_navigation']['options'] = [];
$ddm4_elements['preview']['vis2_navigation']['options']['data'] = $navigation_links;

/*
 * View: VIS2_Datatables
 */
$ddm4_elements['view']['vis2_datatables'] = [];
$ddm4_elements['view']['vis2_datatables']['module'] = 'vis2_datatables';

/*
 * Elemente nach Navigation
 */
switch ($ddm_navigation_id) {
    case 1:
        /*
         * Data: Text
         */
        $ddm4_elements['data']['text_example'] = [];
        $ddm4_elements['data']['text_example']['module'] = 'text';
        $ddm4_elements['data']['text_example']['title'] = 'Text';
        $ddm4_elements['data']['text_example']['name'] = 'text_example';
        $ddm4_elements['data']['text_example']['options'] = [];
        $ddm4_elements['data']['text_example']['options']['search'] = true;

        break;
    case 2:
        /*
         * Data: Text
         */
        $ddm4_elements['data']['text_example'] = [];
        $ddm4_elements['data']['text_example']['module'] = 'text';
        $ddm4_elements['data']['text_example']['title'] = 'Text';
        $ddm4_elements['data']['text_example']['name'] = 'text_example';
        $ddm4_elements['data']['text_example']['options'] = [];
        $ddm4_elements['data']['text_example']['options']['search'] = true;
        $ddm4_elements['data']['text_example']['options']['notice'] = 'String zwischen 2 und 12 Zeichen';
        $ddm4_elements['data']['text_example']['validation'] = [];
        $ddm4_elements['data']['text_example']['validation']['module'] = 'string';
        $ddm4_elements['data']['text_example']['validation']['length_min'] = 2;
        $ddm4_elements['data']['text_example']['validation']['length_max'] = 12;

        break;
    case 3:
        /*
         * Data: Text
         */
        $ddm4_elements['data']['text_example'] = [];
        $ddm4_elements['data']['text_example']['module'] = 'text';
        $ddm4_elements['data']['text_example']['title'] = 'Text';
        $ddm4_elements['data']['text_example']['name'] = 'text_example';
        $ddm4_elements['data']['text_example']['options'] = [];
        $ddm4_elements['data']['text_example']['options']['search'] = true;
        $ddm4_elements['data']['text_example']['options']['notice'] = 'Integer zwischen 25 und 75';
        $ddm4_elements['data']['text_example']['validation'] = [];
        $ddm4_elements['data']['text_example']['validation']['module'] = 'integer';
        $ddm4_elements['data']['text_example']['validation']['value_min'] = 25;
        $ddm4_elements['data']['text_example']['validation']['value_max'] = 75;

        break;
    case 4:
        /*
         * Data: Text
         */
        $ddm4_elements['data']['text_example'] = [];
        $ddm4_elements['data']['text_example']['module'] = 'text';
        $ddm4_elements['data']['text_example']['title'] = 'Text';
        $ddm4_elements['data']['text_example']['name'] = 'text_example';
        $ddm4_elements['data']['text_example']['options'] = [];
        $ddm4_elements['data']['text_example']['options']['search'] = true;
        $ddm4_elements['data']['text_example']['options']['notice'] = 'Float zwischen 2.50 und 7.25';
        $ddm4_elements['data']['text_example']['validation'] = [];
        $ddm4_elements['data']['text_example']['validation']['module'] = 'float';
        $ddm4_elements['data']['text_example']['validation']['value_min'] = 2.5;
        $ddm4_elements['data']['text_example']['validation']['value_max'] = 7.25;

        break;
    case 11:
        /*
         * Data: Text
         */
        $ddm4_elements['data']['text_example'] = [];
        $ddm4_elements['data']['text_example']['module'] = 'text';
        $ddm4_elements['data']['text_example']['title'] = 'Text';
        $ddm4_elements['data']['text_example']['name'] = 'text_example';
        $ddm4_elements['data']['text_example']['options'] = [];
        $ddm4_elements['data']['text_example']['options']['search'] = true;
        $ddm4_elements['data']['text_example']['options']['notice'] = 'E-Mail zwischen 5 und 128 Zeichen';
        $ddm4_elements['data']['text_example']['validation'] = [];
        $ddm4_elements['data']['text_example']['validation']['module'] = 'string';
        $ddm4_elements['data']['text_example']['validation']['length_min'] = 5;
        $ddm4_elements['data']['text_example']['validation']['length_max'] = 128;
        $ddm4_elements['data']['text_example']['validation']['filter'] = [];
        $ddm4_elements['data']['text_example']['validation']['filter']['email'] = [];

        break;
    case 12:
        /*
         * Data: Text
         */
        $ddm4_elements['data']['text_example'] = [];
        $ddm4_elements['data']['text_example']['module'] = 'text';
        $ddm4_elements['data']['text_example']['title'] = 'Text';
        $ddm4_elements['data']['text_example']['name'] = 'text_example';
        $ddm4_elements['data']['text_example']['options'] = [];
        $ddm4_elements['data']['text_example']['options']['search'] = true;
        $ddm4_elements['data']['text_example']['options']['notice'] = 'URL zwischen 5 und 128 Zeichen';
        $ddm4_elements['data']['text_example']['validation'] = [];
        $ddm4_elements['data']['text_example']['validation']['module'] = 'string';
        $ddm4_elements['data']['text_example']['validation']['length_min'] = 5;
        $ddm4_elements['data']['text_example']['validation']['length_max'] = 128;
        $ddm4_elements['data']['text_example']['validation']['filter'] = [];
        $ddm4_elements['data']['text_example']['validation']['filter']['url'] = [];

        break;
    case 21:
        /*
         * Data: Text
         */
        $ddm4_elements['data']['text_example'] = [];
        $ddm4_elements['data']['text_example']['module'] = 'text';
        $ddm4_elements['data']['text_example']['title'] = 'Text';
        $ddm4_elements['data']['text_example']['name'] = 'text_example';
        $ddm4_elements['data']['text_example']['options'] = [];
        $ddm4_elements['data']['text_example']['options']['search'] = true;
        $ddm4_elements['data']['text_example']['options']['notice'] = 'Telefonnummer nach dem Muster +XX XXX - XXX-XXX';
        $ddm4_elements['data']['text_example']['validation'] = [];
        $ddm4_elements['data']['text_example']['validation']['module'] = 'string';
        $ddm4_elements['data']['text_example']['validation']['length_min'] = 5;
        $ddm4_elements['data']['text_example']['validation']['length_max'] = 128;
        $ddm4_elements['data']['text_example']['validation']['preg'] = '/(^(\+)([0-9]{2,2}) ([0-9]{3,5}) - ([0-9]{3,6})(-([0-9]{1,3}))?$)|(^$)/Uis';

        break;
    case 99:
        /*
         * Data: Text
         */
        $ddm4_elements['data']['text_example'] = [];
        $ddm4_elements['data']['text_example']['module'] = 'text';
        $ddm4_elements['data']['text_example']['title'] = 'Text';
        $ddm4_elements['data']['text_example']['name'] = 'text_example';
        $ddm4_elements['data']['text_example']['options'] = [];
        $ddm4_elements['data']['text_example']['options']['search'] = true;
        $ddm4_elements['data']['text_example']['options']['read_only'] = true;
        $ddm4_elements['data']['text_example']['options']['default_value'] = date('d.m.Y H:i:s');
        $ddm4_elements['data']['text_example']['options']['notice'] = 'Nur lesend zugreifen';

        break;
}

/*
 * Data: Required
 */
$ddm4_elements['data']['text_check'] = [];
$ddm4_elements['data']['text_check']['module'] = 'text';
$ddm4_elements['data']['text_check']['title'] = 'Required';
$ddm4_elements['data']['text_check']['name'] = 'text_check';
$ddm4_elements['data']['text_check']['options'] = [];
$ddm4_elements['data']['text_check']['options']['required'] = true;
$ddm4_elements['data']['text_check']['validation'] = [];
$ddm4_elements['data']['text_check']['validation']['module'] = 'string';
$ddm4_elements['data']['text_check']['validation']['length_min'] = 1;
$ddm4_elements['data']['text_check']['validation']['length_max'] = 16;

/*
 * Data: NavigationId
 */
$ddm4_elements['data']['navigation_id'] = [];
$ddm4_elements['data']['navigation_id']['module'] = 'hidden';
$ddm4_elements['data']['navigation_id']['name'] = 'navigation_id';
$ddm4_elements['data']['navigation_id']['options'] = [];
$ddm4_elements['data']['navigation_id']['options']['default_value'] = $ddm_navigation_id;
$ddm4_elements['data']['navigation_id']['validation'] = [];
$ddm4_elements['data']['navigation_id']['validation']['module'] = 'integer';
$ddm4_elements['data']['navigation_id']['validation']['length_min'] = 1;
$ddm4_elements['data']['navigation_id']['validation']['length_max'] = 11;
$ddm4_elements['data']['navigation_id']['_view'] = [];
$ddm4_elements['data']['navigation_id']['_view']['enabled'] = false;
$ddm4_elements['data']['navigation_id']['_search'] = [];
$ddm4_elements['data']['navigation_id']['_search']['enabled'] = false;
$ddm4_elements['data']['navigation_id']['_edit'] = [];
$ddm4_elements['data']['navigation_id']['_edit']['enabled'] = false;
$ddm4_elements['data']['navigation_id']['_delete'] = [];
$ddm4_elements['data']['navigation_id']['_delete']['enabled'] = false;

/*
 * Data: VIS2_CreateUpdate
 */
$ddm4_elements['data']['vis2_createupdatestatus'] = [];
$ddm4_elements['data']['vis2_createupdatestatus']['module'] = 'vis2_createupdatestatus';
$ddm4_elements['data']['vis2_createupdatestatus']['title'] = $osW_DDM4->getGroupOption(
    'createupdate_title',
    'messages'
);
$ddm4_elements['data']['vis2_createupdatestatus']['options'] = [];
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix'] = 'element_';
$ddm4_elements['data']['vis2_createupdatestatus']['options']['time'] = time();
$ddm4_elements['data']['vis2_createupdatestatus']['options']['user_id'] = $VIS2_User->getId();
$ddm4_elements['data']['vis2_createupdatestatus']['_list'] = [];
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options'] = [];
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']['display_create_time'] = false;
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']['display_create_user'] = false;

/*
 * Data: Optionen
 */
$ddm4_elements['data']['options'] = [];
$ddm4_elements['data']['options']['module'] = 'options';
$ddm4_elements['data']['options']['title'] = 'Optionen';

/*
 * Finish: VIS2_Store_Form_Data
 */
$ddm4_elements['finish']['vis2_store_form_data'] = [];
$ddm4_elements['finish']['vis2_store_form_data']['module'] = 'vis2_store_form_data';
$ddm4_elements['finish']['vis2_store_form_data']['options'] = [];
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix'] = 'element_';

/*
 * AfterFinish: VIS2_Direct
 */
$ddm4_elements['afterfinish']['vis2_direct'] = [];
$ddm4_elements['afterfinish']['vis2_direct']['module'] = 'vis2_direct';

/*
 * Datenelemente hinzufügen
 */
foreach ($ddm4_elements as $key => $ddm4_key_elements) {
    if ($ddm4_key_elements !== []) {
        foreach ($ddm4_key_elements as $element_name => $element_options) {
            $osW_DDM4->addElement($key, $element_name, $element_options);
        }
    }
}

/*
 * DDM4-Objekt Runtime
 */
$osW_DDM4->runDDMPHP();

/*
 * DDM4-Objekt an Template übergeben
 */
$osW_Template->setVar('osW_DDM4', $osW_DDM4);
