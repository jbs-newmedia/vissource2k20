<?php declare(strict_types=0);

/**
 * This file is part of the VIS2:Manager package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2:Manager
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace VIS2\Manager;

use osWFrame\Core\BaseConnectionTrait;
use osWFrame\Core\BaseStaticTrait;
use osWFrame\Core\BaseTemplateBridgeTrait;
use osWFrame\Core\BaseVarTrait;
use osWFrame\Core\Template;

class Main
{
    use BaseStaticTrait;
    use BaseConnectionTrait;
    use BaseVarTrait;
    use BaseTemplateBridgeTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 2;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 0;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 0;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    public function __construct(
        Template $osW_Template
    ) {
        $this->setEnvironment($osW_Template);
    }

    public function setEnvironment(Template $osW_Template): void
    {
        $this->setTemplate($osW_Template);
    }
}
