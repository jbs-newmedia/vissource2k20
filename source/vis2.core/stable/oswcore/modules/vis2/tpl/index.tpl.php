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
    <?php echo $this->getHead(); ?>
</head>
<body id="jbsadmin-body"
      class="bg-white <?php if (Settings::catchIntValue(
          'modal',
          0,
          'pg'
      ) === 1
      ): ?>jbsadmin-body-modal<?php else: ?>jbsadmin-body-full<?php endif ?>">
<?php echo $this->getBody(); ?>

<?php echo $content ?>

</body>
</html>
