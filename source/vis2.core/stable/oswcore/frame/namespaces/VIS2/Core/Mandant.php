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

use osWFrame\Core\BaseConnectionTrait;
use osWFrame\Core\BaseStaticTrait;
use osWFrame\Core\BaseVarTrait;
use osWFrame\Core\Network;
use osWFrame\Core\Session;
use osWFrame\Core\SessionMessageStack;

class Mandant
{
    use BaseStaticTrait;
    use BaseConnectionTrait;
    use BaseVarTrait;
    use BaseToolTrait;

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

    /**
     * Speichert alle Mandanten mit Details.
     *
     */
    protected ?array $mandanten = null;

    public function __construct(
        int $tool_id = 0
    ) {
        if ($tool_id > 0) {
            $this->setToolId($tool_id);
        }
    }

    public function isLoaded(): bool
    {
        if ($this->mandanten === null) {
            return false;
        }

        return true;
    }

    public function getMandanten(): array
    {
        if ($this->isLoaded() !== true) {
            $this->loadMandanten();
        }

        return $this->mandanten;
    }

    public function getMandantenSelectArray(): array
    {
        if ($this->isLoaded() !== true) {
            $this->loadMandanten();
        }

        $mandanten = [];
        foreach ($this->mandanten as $mandant_details) {
            $mandanten[$mandant_details['mandant_id']] = $mandant_details['mandant_name'];
        }

        return $mandanten;
    }

    public function setId(int $mandant_id): bool
    {
        return Session::setIntVar('vis2_mandante_id_' . $this->getToolId(), $mandant_id);
    }

    public function getId(): int
    {
        return (int)(Session::getIntVar('vis2_mandante_id_' . $this->getToolId()));
    }

    public function getName(): ?string
    {
        if ($this->isLoaded() !== true) {
            $this->loadMandanten();
        }
        if (isset($this->mandanten[$this->getId()])) {
            return $this->mandanten[$this->getId()]['mandant_name'];
        }

        return null;
    }

    public function directEmptyMandant(string $link, string $message = ''): void
    {
        if ($this->getId() === 0) {
            if ($message === '') {
                $message = 'Bitte einen Mandanten auswählen';
            }
            SessionMessageStack::addMessage('session', 'warning', [
                'msg' => $message,
            ]);
            Network::directHeader($link);
        }
    }

    /**
     * Lädt alle Mandanten.
     *
     * @return $this
     */
    protected function loadMandanten(): self
    {
        $this->mandanten = [];

        $QselectMandanten = self::getConnection();
        $QselectMandanten->prepare(
            'SELECT * FROM :table_vis2_mandant: WHERE tool_id=:tool_id: AND mandant_ispublic=:mandant_ispublic: ORDER BY mandant_name ASC'
        );
        $QselectMandanten->bindTable(':table_vis2_mandant:', 'vis2_mandant');
        $QselectMandanten->bindInt(':mandant_ispublic:', 1);
        $QselectMandanten->bindInt(':tool_id:', $this->getToolId());
        foreach ($QselectMandanten->query() as $mandant) {
            $this->mandanten[$mandant['mandant_id']] = [
                'mandant_id' => $mandant['mandant_id'],
                'mandant_number' => $mandant['mandant_number'],
                'mandant_name_intern' => $mandant['mandant_name_intern'],
                'mandant_name' => $mandant['mandant_name'],
            ];
        }

        return $this;
    }
}
