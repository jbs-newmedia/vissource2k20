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

$c=count($dashboard_tpls);
$i=0;
foreach ($dashboard_tpls as $file) {
	$i++;
	include($file);
	if ($i<$c) {
		echo '<hr/>';
	}
}

?>