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

?>
<div class="card shadow mb-4">
	<div class="card-body">
		Willkommen <strong><?php echo $VIS_User->getDisplayName(false) ?></strong>,<br/> <br/> heute ist der
		<strong><?php echo date('d.m.Y') ?></strong> und es ist <strong><?php echo date('H:i') ?> Uhr</strong>.<br/>
	</div>
</div>