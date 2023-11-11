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
 * @var int $ddm_navigation_id
 * @var \osWFrame\Core\DDM4 $osW_DDM4
 *
 */

?><?php use osWFrame\Core\DDM4;

if ($ddm_navigation_id !== 0): ?>

    <?php echo $osW_DDM4->runDDMTPL(); ?>

<?php else: ?>

    <div class="card shadow mb-4">
        <div class="card-body">Keine Einstellungen vorhanden.</div>
    </div>

<?php endif ?>
