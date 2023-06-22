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

\osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.\osWFrame\Core\Settings::getStringVar('vis2_logout_module')));

?>