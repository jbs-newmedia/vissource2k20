<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license MIT License
 */

namespace VIS2\Core;

use osWFrame\Core\BaseConnectionTrait;
use osWFrame\Core\BaseStaticTrait;

class Navigation {

	use BaseStaticTrait;
	use BaseConnectionTrait;
	use BaseToolTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=2;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var string
	 */
	protected string $page='';

	/**
	 * @var string
	 */
	protected string $file='';

	/**
	 * @var int
	 */
	protected $current_navigation_id=0;

	/**
	 * @var array|null
	 */
	protected ?array $navigation_tree=null;

	/**
	 * @var array|null
	 */
	protected ?array $navigation_unsorted=null;

	/**
	 * @var array|null
	 */
	protected ?array $navigation_unsorted_name2id=null;

	/**
	 * @var array
	 */
	protected array $current_navigation_path_array=[];

	/**
	 * @var array
	 */
	protected array $navigation_data=[];

	/**
	 * @var Permission|null
	 */
	protected ?Permission $VIS2_Permission=null;

	/**
	 * Navigation constructor.
	 */
	public function __construct(int $tool_id=0) {
		if ($tool_id>0) {
			$this->setToolId($tool_id);
		}
	}

	/**
	 * @param Permission $Permission
	 * @return $this
	 */
	public function setPermission(Permission $Permission):self {
		$this->VIS2_Permission=$Permission;

		return $this;
	}

	/**
	 * @return Permission|null
	 */
	public function getPermission():?Permission {
		return $this->VIS2_Permission;
	}

	/**
	 * @return bool
	 */
	public function isLoaded():bool {
		if (($this->navigation_tree===null)&&($this->navigation_unsorted===null)&&($this->navigation_unsorted_name2id===null)) {

			return false;
		}

		return true;
	}

	/*
	 * Liefert die Default ToolPage
	 *
	 * @return string
	 */
	public function getDefaultPage():string {
		return 'vis_dashboard';
	}

	/*
	 * Setzt die aktuelle Seite.
	 *
	 * @param string $page
	 * @return bool
	 */
	public function setPage(string $page=''):bool {
		if ($page=='') {
			$page=$this->getDefaultPage();
		}
		if ($this->validatePage($page)===true) {
			$this->page=strtolower($page);
			$return_value=true;
		} else {
			$this->page=strtolower(self::getDefaultPage());
			$return_value=false;
		}

		if (isset($this->navigation_unsorted_name2id[$page])) {
			$this->current_navigation_id=$this->navigation_unsorted_name2id[$page];
			$this->current_navigation_path_array=$this->navigation_unsorted[$this->current_navigation_id]['navigation_path_array'];
		}

		return $return_value;
	}

	/*
	 * Liefert die aktuelle Seite.
	 *
	 * @return string
	 */
	public function getPage():string {
		return $this->page;
	}

	/**
	 * @param string $file
	 */
	public function setFile(string $file):void {
		$this->file=$file;
	}

	/**
	 * @return string
	 */
	public function getFile():string {
		return $this->file;
	}

	/*
	 * Liefert den Titel einer Seite.
	 *
	 * @return string
	 */
	public function getNavigationTitle(string $page=''):string {
		if ($this->isLoaded()!==true) {
			$this->loadNavigationTree();
		}

		if ($page=='') {
			$page=$this->getPage();
		}

		if (in_array($page, ['vis_api', 'vis_dashboard', 'vis_profile', 'vis_settings', 'vis_logout'])) {
			switch ($page) {
				case 'vis_api':
					return 'API';
					break;
				case 'vis_dashboard':
					return 'Dashboard';
					break;
				case 'vis_profile':
					return 'Profil';
					break;
				case 'vis_settings':
					return 'Einstellungen';
					break;
				case 'vis_logout':
					return 'Abmelden';
					break;
			}
		}

		if ((isset($this->navigation_unsorted_name2id[$page]))&&(isset($this->navigation_unsorted[$this->navigation_unsorted_name2id[$page]]))) {
			return $this->navigation_unsorted[$this->navigation_unsorted_name2id[$page]]['navigation_title'];
		} else {
			return 'Undefiniert';
		}
	}

	/**
	 *
	 * @param string $page
	 * @return bool
	 */
	public function validatePage(string $page):bool {
		if (in_array($page, ['vis_api', 'vis_dashboard', 'vis_profile', 'vis_settings', 'vis_logout'])) {
			return true;
		}

		if ($this->isLoaded()!==true) {
			$this->loadNavigationTree();
		}

		if (isset($this->navigation_unsorted_name2id[$page])) {
			return true;
		}

		return false;
	}

	/**
	 * @return $this
	 */
	public function loadNavigationTree():self {
		$this->navigation_tree=[];
		$this->navigation_unsorted=[];
		$this->navigation_unsorted_name2id=[];

		$QselectNavigation=self::getConnection();
		$QselectNavigation->prepare('SELECT *, n.tool_id, n.page_id FROM :table_vis2_navigation: AS n LEFT JOIN :table_vis2_page: AS p on (p.tool_id=n.tool_id AND p.page_id=n.page_id) WHERE n.tool_id=:tool_id: ORDER BY n.navigation_parent_id ASC, n.navigation_sortorder ASC, n.navigation_title ASC');
		$QselectNavigation->bindTable(':table_vis2_navigation:', 'vis2_navigation');
		$QselectNavigation->bindTable(':table_vis2_page:', 'vis2_page');
		$QselectNavigation->bindInt(':tool_id:', $this->getToolId());
		foreach ($QselectNavigation->query() as $navigation_element) {
			$navigation_element['navigation_sortorder']=intval($navigation_element['navigation_sortorder']);
			$navigation_element['custom']=false;
			$navigation_element['permission_link']=false;
			$navigation_element['permission_view']=false;
			$this->navigation_tree[$navigation_element['navigation_parent_id']][$navigation_element['navigation_id']]=$navigation_element;
			$this->navigation_unsorted[$navigation_element['navigation_id']]=$navigation_element;
			$this->navigation_unsorted_name2id[$navigation_element['page_name_intern']]=$navigation_element['navigation_id'];
		}

		$this->createNavigationPath();

		$QselectPagePermissions=self::getConnection();
		$QselectPagePermissions->prepare('SELECT * FROM :table_vis2_page_permission: WHERE tool_id=:tool_id:');
		$QselectPagePermissions->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
		$QselectPagePermissions->bindInt(':tool_id:', $this->getToolId());
		$ar_permission=[];
		foreach ($QselectPagePermissions->query() as $pagepermission) {
			if (!isset($ar_permission[$pagepermission['page_id']])) {
				$ar_permission[$pagepermission['page_id']]=[];
			}
			$ar_permission[$pagepermission['page_id']][]=$pagepermission['permission_flag'];
		}

		foreach ($this->navigation_tree as $parent_id=>$members) {
			foreach ($members as $member_id=>$member) {
				if (isset($ar_permission[$member['page_id']])) {
					$this->navigation_tree[$parent_id][$member_id]['permission']=$ar_permission[$member['page_id']];
					$this->navigation_unsorted[$member_id]['permission']=$ar_permission[$member['page_id']];
				} else {
					$this->navigation_tree[$parent_id][$member_id]['permission']=[];
					$this->navigation_unsorted[$member_id]['permission']=[];
				}
			}
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function createNavigationPath():self {
		if ($this->isLoaded()!==true) {
			$this->loadNavigationTree();
		}

		foreach ($this->navigation_tree as $parent_id=>$members) {
			foreach ($members as $member_id=>$member) {
				if ($parent_id==0) {
					$this->navigation_tree[$parent_id][$member_id]['navigation_path']='0_'.$member_id;
				} else {
					$this->navigation_tree[$parent_id][$member_id]['navigation_path']=$this->getNavigationPath($member_id);
				}
				$this->navigation_tree[$parent_id][$member_id]['navigation_path_array']=explode('_', $this->navigation_tree[$parent_id][$member_id]['navigation_path']);

				$this->navigation_unsorted[$member_id]['navigation_path']=$this->navigation_tree[$parent_id][$member_id]['navigation_path'];
				$this->navigation_unsorted[$member_id]['navigation_path_array']=$this->navigation_tree[$parent_id][$member_id]['navigation_path_array'];
			}
		}

		return $this;
	}

	/**
	 *
	 * @param int $member_id
	 * @return string
	 */
	protected function getNavigationPath(int $member_id):string {
		if ($this->isLoaded()!==true) {
			$this->loadNavigationTree();
		}

		$link_path=[];
		$link_path[]=$member_id;
		if ((isset($this->navigation_unsorted[$member_id]))&&(isset($this->navigation_unsorted[$member_id]['navigation_parent_id']))) {
			while (($this->navigation_unsorted[$member_id]['navigation_parent_id']!=0)) {
				$member_id=$this->navigation_unsorted[$member_id]['navigation_parent_id'];
				$link_path[]=$member_id;
			}
		}
		$link_path[]=0;
		$link_path=array_reverse($link_path);
		$link_path=implode('_', $link_path);

		return $link_path;
	}

	/**
	 *
	 * @param int $parent_id
	 * @param int $max_level
	 * @param bool $force
	 * @return array
	 */
	public function getNavigationWithPermission(int $parent_id=0, int $max_level=0):array {
		if ($this->getPermission()->isLoaded()!==true) {
			$this->getPermission()->loadPermission();
		}

		$data=self::getNavigation($parent_id, $max_level);
		foreach ($data as $id=>$_navigation_element) {
			$data[$id]=$this->checkNavigationPermissionRecursive($_navigation_element);
		}

		return $data;

	}

	/**
	 *
	 * @param int $parent_id
	 * @param int $max_level
	 * @return array
	 */
	public function getNavigation(int $parent_id=0, int $max_level=0):array {
		if ($this->isLoaded()!==true) {
			$this->loadNavigationTree();
		}

		return $this->createNavigationRecursive($parent_id, 0, $max_level);
	}

	/**
	 *
	 * @param int|string $parent_id
	 * @param int $level
	 * @param int $max_level
	 * @return array
	 */
	protected function createNavigationRecursive($parent_id, int $level=0, int $max_level=0):array {
		if ($this->isLoaded()!==true) {
			$this->loadNavigationTree();
		}

		if (isset($this->navigation_tree[$parent_id])) {
			$data=[];
			foreach ($this->navigation_tree[$parent_id] as $category_id=>$category) {
				$_data=[];
				$category['navigation_level']=$level;
				$this->navigation_data[$category_id]=$category;

				if (in_array($category_id, $this->current_navigation_path_array)) {
					$category['navigation_active']=true;
				} else {
					$category['navigation_active']=false;
				}
				if (isset($this->navigation_tree[$category_id])&&(($max_level==0)||($max_level>$level+1))) {
					$this->navigation_data[$category_id]['navigation_subcats']=true;
					$category['navigation_subcats']=true;
					$_data['info']=$category;
					$_data['links']=$this->createNavigationRecursive($category_id, $level+1, $max_level);
					foreach ($_data['links'] as $_category) {
						if ($_category['info']['navigation_active']===true) {
							$_data['info']['navigation_active']=true;
						}
					}
					$data[]=$_data;
				} else {
					$this->navigation_data[$category_id]['navigation_subcats']=false;
					$category['navigation_subcats']=false;
					if (!isset($category['navigation_active'])) {
						$category['navigation_active']=false;
					}
					$_data['info']=$category;
					$_data['links']=[];
					$data[]=$_data;
				}
			}

			return $data;
		}
	}

	/**
	 *
	 * @param array $navigation_element
	 * @return array
	 */
	protected function checkNavigationPermissionRecursive(array $navigation_element):array {
		if (($navigation_element['info']['page_name_intern']!='')&&($this->getPermission()->checkPermission($navigation_element['info']['page_name_intern'], 'link')===true)) {
			$navigation_element['info']['permission_link']=true;
		}

		if (($navigation_element['info']['page_name_intern']!='')&&($this->getPermission()->checkPermission($navigation_element['info']['page_name_intern'], 'view')===true)) {
			$navigation_element['info']['permission_view']=true;
		}

		if ((isset($navigation_element['links']))&&(!empty($navigation_element['links']))) {
			foreach ($navigation_element['links'] as $id=>$_navigation_element) {
				$navigation_element['links'][$id]=$this->checkNavigationPermissionRecursive($_navigation_element);
			}
		}

		return $navigation_element;
	}

	/**
	 * @param array $data
	 * @param $create_navigationpath
	 * @return bool
	 */
	public function addNavigationElement(array $data, $create_navigationpath=true):bool {
		$required_fields=['page_name', 'page_description', 'permission', 'navigation_parent_id', 'navigation_id'];

		foreach ($required_fields as $required_field) {
			if (!isset($data[$required_field])) {
				return false;
			}
		}

		if (!isset($data['page_name_intern'])) {
			$data['page_name_intern']=$data['page_name'];
			$data['page_name']=$data['page_description'];
		}

		$data['permission_link']=false;
		$data['permission_view']=false;

		if (in_array('link', $data['permission'])) {
			$data['permission_link']=true;
		}

		if (in_array('view', $data['permission'])) {
			$data['permission_view']=true;
		}

		if ($this->isLoaded()!==true) {
			$this->loadNavigationTree();
		}

		$this->navigation_tree[$data['navigation_parent_id']][$data['navigation_id']]=$data;
		$this->navigation_unsorted[$data['navigation_id']]=$data;
		$this->navigation_unsorted_name2id[$data['page_name_intern']]=$data['navigation_id'];

		foreach ($this->navigation_tree as $parent_id=>$parent_elements) {
			uasort($this->navigation_tree[$parent_id], $this->buildSorter('navigation_sortorder'));
		}

		if ($this->getPermission()->isLoaded()!==true) {
			$this->getPermission()->loadPermission();
		}

		if (strlen($data['page_name_intern'])>0) {
			foreach ($data['permission'] as $flag) {
				$this->getPermission()->addPermission($data['page_name_intern'], $flag);
			}
		}

		if ($create_navigationpath===true) {
			$this->createNavigationPath();
		}

		return true;
	}

	/**
	 * @param $key
	 * @return \Closure
	 */
	protected function buildSorter($key) {
		return function($a, $b) use ($key) {
			return strnatcmp($a[$key], $b[$key]);
		};
	}

	/**
	 * @return bool
	 */
	public static function updateInternSortOrder():bool {
		$i=0;

		$QselectDataL1=self::getConnection();
		$QselectDataL1->prepare('SELECT * FROM :table_vis2_navigation: WHERE navigation_parent_id=:navigation_parent_id: ORDER BY navigation_sortorder ASC');
		$QselectDataL1->bindTable(':table_vis2_navigation:', 'vis2_navigation');
		$QselectDataL1->bindInt(':navigation_parent_id:', 0);
		foreach ($QselectDataL1->query() as $resultL1) {
			$QupdateDataL1=self::getConnection();
			$QupdateDataL1->prepare('UPDATE :table_vis2_navigation: SET navigation_intern_sortorder=:navigation_intern_sortorder: WHERE navigation_id=:navigation_id:');
			$QupdateDataL1->bindTable(':table_vis2_navigation:', 'vis2_navigation');
			$QupdateDataL1->bindInt(':navigation_intern_sortorder:', $i);
			$QupdateDataL1->bindInt(':navigation_id:', $resultL1['navigation_id']);
			$QupdateDataL1->execute();
			$i++;

			$QselectDataL2=self::getConnection();
			$QselectDataL2->prepare('SELECT * FROM :table_vis2_navigation: WHERE navigation_parent_id=:navigation_parent_id: ORDER BY navigation_sortorder ASC');
			$QselectDataL2->bindTable(':table_vis2_navigation:', 'vis2_navigation');
			$QselectDataL2->bindInt(':navigation_parent_id:', $resultL1['navigation_id']);
			foreach ($QselectDataL2->query() as $resultL2) {
				$QupdateDataL2=self::getConnection();
				$QupdateDataL2->prepare('UPDATE :table_vis2_navigation: SET navigation_intern_sortorder=:navigation_intern_sortorder: WHERE navigation_id=:navigation_id:');
				$QupdateDataL2->bindTable(':table_vis2_navigation:', 'vis2_navigation');
				$QupdateDataL2->bindInt(':navigation_intern_sortorder:', $i);
				$QupdateDataL2->bindInt(':navigation_id:', $resultL2['navigation_id']);
				$QupdateDataL2->execute();
				$i++;

				$QselectDataL3=self::getConnection();
				$QselectDataL3->prepare('SELECT * FROM :table_vis2_navigation: WHERE navigation_parent_id=:navigation_parent_id: ORDER BY navigation_sortorder ASC');
				$QselectDataL3->bindTable(':table_vis2_navigation:', 'vis2_navigation');
				$QselectDataL3->bindInt(':navigation_parent_id:', $resultL2['navigation_id']);
				foreach ($QselectDataL3->query() as $resultL3) {
					$QupdateDataL3=self::getConnection();
					$QupdateDataL3->prepare('UPDATE :table_vis2_navigation: SET navigation_intern_sortorder=:navigation_intern_sortorder: WHERE navigation_id=:navigation_id:');
					$QupdateDataL3->bindTable(':table_vis2_navigation:', 'vis2_navigation');
					$QupdateDataL3->bindInt(':navigation_intern_sortorder:', $i);
					$QupdateDataL3->bindInt(':navigation_id:', $resultL3['navigation_id']);
					$QupdateDataL3->execute();
					$i++;
				}
			}
		}

		return true;
	}

}

?>