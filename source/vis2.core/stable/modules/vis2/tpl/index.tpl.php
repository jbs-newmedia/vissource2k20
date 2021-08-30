<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
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