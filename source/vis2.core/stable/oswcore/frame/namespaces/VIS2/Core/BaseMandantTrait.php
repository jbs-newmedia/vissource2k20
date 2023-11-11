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

namespace VIS2\Core;

trait BaseMandantTrait
{
    protected int $mandant_id = 0;

    public function setMandantId(int $mandant_id): bool
    {
        $this->mandant_id = $mandant_id;

        return true;
    }

    public function getMandantId(): int
    {
        return $this->mandant_id;
    }
}
