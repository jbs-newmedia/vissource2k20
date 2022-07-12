<?php

/**
 * This file is part of the VIS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS
 * @link https://oswframe.com
 * @license MIT License
 */

?><!DOCTYPE html>
<html lang="<?php echo \osWFrame\Core\Language::getCurrentLanguage('short') ?>">
<head>
	<?php echo $this->getHead(); ?>
</head>
<body id="jbsadmin-body" class="bg-white <?php if (\osWFrame\Core\Settings::catchValue('modal', '', 'pg')=='1'): ?>jbsadmin-body-modal<?php else:?>jbsadmin-body-full<?php endif?>">
<?php echo $this->getBody(); ?>

<?php echo $content ?>

</body>
</html>