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

use osWFrame\Core\BaseStaticTrait;

class Badge
{
    use BaseStaticTrait;

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
     * Array zum Speichern der Badges.
     *
     */
    protected static array $badge = [];

    /**
     * Setzt ein Badge.
     *
     */
    public static function set(string $page, int $count): bool
    {
        self::$badge[$page] = $count;

        return true;
    }

    /**
     * Gibt die Anzahl des Badge zurück. Bei NULL ist die Badge nicht angelegt.
     *
     */
    public static function get(string $page, ?int $max = 999): ?int
    {
        $name = 'badge';
        if (isset(self::$badge[$name][$page])) {
            if (($max !== null) && (self::$badge[$name][$page] > $max)) {
                return $max;
            }

            return self::$badge[$name][$page];
        }

        return null;
    }
}
