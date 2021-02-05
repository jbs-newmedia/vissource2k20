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

$this->setDeleteElementStorage($element, \VIS2\Core\Manager::loadPagePermission($this->getIndexElementStorage(), $this->getDeleteElementOption($element, 'tool_id')));

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>