<?php declare(strict_types=0);

/**
 * This file is part of the VIS2 package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var string $element
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use osWFrame\Core\MessageStack;

$_messages = [];
if (MessageStack::getMessageCount('ddm4_' . $this->getName()) > 0) {
    foreach (MessageStack::getMessages('ddm4_' . $this->getName()) as $type => $messages) {
        foreach ($messages as $message) {
            $_messages[] = 'window.parent.vis2_notify("' . $message['msg'] . '", "' . $type . '");';
        }
    }
    osWFrame\Core\MessageStack::clearMessages('ddm4_' . $this->getName());
}

$this->getTemplate()->addJSCodeHead(
    '
$(function() {
' . implode("\n", $_messages) . '
});
'
);
