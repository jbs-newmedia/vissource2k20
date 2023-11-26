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
 * @var string $content
 * @var \osWFrame\Core\Template $this
 *
 */
use osWFrame\Core\Language;
use osWFrame\Core\Settings;
use osWFrame\Core\Template;

?><!DOCTYPE html>
<html lang="<?php echo Language::getCurrentLanguage('short') ?>">
<head>
    <?php
    echo $this->getHead(); ?>

<style>
    :root,
    [data-bs-theme=light] {
        --avalynx-darkmode-0: '<i class="fa-solid fa-sun fa-fw"></i>';
        --avalynx-darkmode-1: '<i class="fa-solid fa-moon fa-fw"></i>';
        --avalynx-darkmode-2: '<i class="bi bi-circle-half"></i>';
    }

    .avalynx-sidenav {
        --avalynx-sidenav-btn-icon: "\f078";
        --avalynx-sidenav-btn-icon-font: "fontawesome";
    }

</style>
</head>
<body>
<?php
echo $this->getBody(); ?>

<?php echo $content ?>

<?php
echo $this->getFooter(); ?>
</body>
</html>

