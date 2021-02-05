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

$QdeleteData=self::getConnection();
$QdeleteData->prepare('DELETE FROM :table: WHERE :name_index:=:value_index:');
$QdeleteData->bindTable(':table:', $this->getGroupOption('table', 'database'));
$QdeleteData->bindRaw(':name_index:', $this->getGroupOption('index', 'database'));
if ($this->getGroupOption('db_index_type', 'database')=='string') {
	$QdeleteData->bindString(':value_index:', $this->getIndexElementStorage());
} else {
	$QdeleteData->bindInt(':value_index:', intval($this->getIndexElementStorage()));
}
$QdeleteData->execute();

\osWFrame\Core\MessageStack::addMessage('ddm4_'.$this->getName(), 'success', ['msg'=>$this->getGroupMessage('delete_success_title')]);

?>