<?php declare(strict_types=0);

/**
 * This file is part of the VIS2 package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2
 * @link      https://oswframe.com
 * @license   MIT License
 */

use osWFrame\Core\Navigation;
use osWFrame\Core\Network;
use osWFrame\Core\Settings;

Network::directHeader(Navigation::buildUrl('current', 'vistool=' . Settings::getStringVar('vis2_logout_module')));
