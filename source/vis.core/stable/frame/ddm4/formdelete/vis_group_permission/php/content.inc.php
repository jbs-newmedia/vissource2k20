<?php

/**
 * This file is part of the VIS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS
 * @link https://oswframe.com
 * @license MIT License
 */

$this->setDeleteElementStorage($element, \VIS\Core\Manager::loadGroupPermission($this->getIndexElementStorage()));

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>