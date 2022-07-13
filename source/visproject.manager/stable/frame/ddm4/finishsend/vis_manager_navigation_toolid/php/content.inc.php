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

\osWFrame\Core\Session::setIntVar($this->getFinishElementOption($element, 'var'), $this->getDoSendElementStorage($this->getFinishElementOption($element, 'element')));

?>