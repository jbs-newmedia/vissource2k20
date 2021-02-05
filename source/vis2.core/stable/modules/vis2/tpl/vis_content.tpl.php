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

?><?php if (\osWFrame\Core\Settings::catchValue('modal', '', 'pg')=='1'): ?><?php echo $vis2content ?><?php else: ?>
	<div id="wrapper">
		<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_dashboard') ?>">
				<div class="sidebar-brand-icon">
					<?php echo $this->getOptimizedImage(\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_name'), ['module'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_module'), 'title'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_title'), 'height'=>36]) ?>
				</div>
				<div class="sidebar-brand-text mx-3"><?php if (\osWFrame\Core\Settings::getStringVar('vis2_tool_'.$VIS2_Main->getTool().'_title')!=null): ?><?php echo \osWFrame\Core\Settings::getStringVar('vis2_tool_'.$VIS2_Main->getTool().'_title') ?><?php else: ?><?php echo $VIS2_Main->getToolName(); ?><?php endif ?></div>
			</a>

			<hr class="sidebar-divider my-0">

			<li class="nav-item<?php if ($VIS2_Navigation->getPage()=='vis_dashboard'): ?> active<?php endif ?>">
				<a class="nav-link" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_dashboard') ?>">
					<span>Dashboard</span> </a>
			</li>

			<?php if ((\osWFrame\Core\Settings::getBoolVar('vis2_navigation_enabled')!==false)): ?><?php foreach ($VIS2_Navigation->getNavigationWithPermission(0, 2) as $navigation_element): ?><?php if ($navigation_element['info']['permission_link']==true): ?>
				<hr class="sidebar-divider my-0">
				<li class="nav-item<?php if ($navigation_element['info']['navigation_active']==true): ?> active<?php endif ?>">
					<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#navi_vis2_<?php echo $navigation_element['info']['navigation_id'] ?>" aria-expanded="true" aria-controls="navi_vis2_<?php echo $navigation_element['info']['navigation_id'] ?>">
						<span><?php echo \osWFrame\Core\HTML::outputString($navigation_element['info']['navigation_title']) ?></span>
					</a>
					<?php if (count($navigation_element['links'])>0): ?>

						<div id="navi_vis2_<?php echo $navigation_element['info']['navigation_id'] ?>" class="collapse<?php if ($navigation_element['info']['navigation_active']===true): ?> show<?php endif ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
							<div class="bg-white py-2 collapse-inner rounded">
								<?php foreach ($navigation_element['links'] as $navigation_element): ?><?php if ($navigation_element['info']['permission_view']==true): ?>
									<a class="collapse-item<?php if ($navigation_element['info']['navigation_active']===true): ?> active<?php endif ?>" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$navigation_element['info']['page_name_intern']) ?>"><?php endif ?><?php echo \osWFrame\Core\HTML::outputString($navigation_element['info']['navigation_title']) ?><?php if (\VIS2\Core\Badge::get($navigation_element['info']['page_name_intern'])!==null): ?>
									<span class="vis2_navigation_badge" title="<?php echo VIS2\Core\Badge::get($navigation_element['info']['page_name_intern'], null) ?>"><?php echo VIS2\Core\Badge::get($navigation_element['info']['page_name_intern']) ?></span><?php endif ?><?php if ($navigation_element['info']['permission_view']==true): ?></a><?php endif ?><?php endforeach ?>
							</div>
						</div>
					<?php endif ?>
				</li>
			<?php endif ?>

			<?php endforeach ?>

			<?php endif ?>


			<?php if (($VIS2_Main->getBoolVar('tool_use_mandant')===true)&&($VIS2_Main->getBoolVar('tool_use_mandantswitch')===true)): ?>

				<?php if (count($VIS2_Mandant->getMandanten())>1): ?>
					<hr class="sidebar-divider my-0">

					<li class="nav-item">
						<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#vis2_mandant" aria-expanded="true" aria-controls="vis2_mandant">
							<span>Mandant wechseln</span> </a>

						<div id="vis2_mandant" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
							<div class="bg-white py-2 collapse-inner rounded">
								<?php foreach ($VIS2_Mandant->getMandanten() as $mandant): ?><?php if ($mandant['mandant_id']>0): ?>
									<a class="collapse-item<?php if ($VIS2_Mandant->getId()==$mandant['mandant_id']): ?> active<?php endif ?>" href="<?php echo $this->buildhrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&vis2_mandant_id='.$mandant['mandant_id']) ?>"><?php echo \osWFrame\Core\HTML::outputString($mandant['mandant_name']) ?></a>
								<?php endif ?><?php endforeach ?>
							</div>
						</div>
					</li>
				<?php endif ?>

			<?php endif ?>

			<?php if ((\osWFrame\Core\Settings::getBoolVar('vis2_toolswitch')===true)&&(count($VIS2_User->getToolsSelectArray())>1)): ?>
				<hr class="sidebar-divider my-0">

				<li class="nav-item">
					<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#vis2_tool" aria-expanded="true" aria-controls="vis2_tool">
						<span>Tool wechseln</span> </a>

					<div id="vis2_tool" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
						<div class="bg-white py-2 collapse-inner rounded">
							<?php foreach ($VIS2_User->getToolsSelectArray() as $vis_tool=>$vis_tool_name): ?>
								<a class="collapse-item<?php if ($VIS2_Main->getTool()==$vis_tool): ?> active<?php endif ?>" href="<?php echo $this->buildHrefLink(('current'), 'vistool='.$vis_tool.'&vispage='.$VIS2_Navigation->getPage()) ?>"><?php echo \osWFrame\Core\HTML::outputString($vis_tool_name) ?></a>
							<?php endforeach ?>
						</div>
					</div>
				</li>
			<?php endif ?>

			<hr class="sidebar-divider d-none d-md-block">

			<div class="text-center d-none d-md-inline">
				<button class="rounded-circle border-0" id="sidebarToggle"></button>
			</div>

		</ul>


		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">

				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

					<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fa fa-bars"></i>
					</button>

					<ul class="navbar-nav ml-auto">


						<div class="topbar-divider d-none d-sm-block"></div>

						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo \osWFrame\Core\HTML::outputString($VIS2_User->getDisplayName(false)) ?></span>
								<img class="img-profile rounded-circle" src="<?php echo $VIS2_User->getProfileImage(); ?>"> </a>
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item<?php if ($VIS2_Navigation->getPage()=='vis_profile'): ?> active<?php endif ?>" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_profile') ?>">
									<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profil </a>
								<a class="dropdown-item<?php if ($VIS2_Navigation->getPage()=='vis_settings'): ?> active<?php endif ?>" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_settings') ?>">
									<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Einstellungen </a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_logout') ?>">
									<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Abmelden </a>
							</div>
						</li>
					</ul>
				</nav>

				<div class="container-fluid">
					<?php echo $vis2content ?>
				</div>
			</div>
		</div>
	</div>

	<a class="scroll-to-top rounded" href="#"> <i class="fas fa-angle-up"></i> </a>
<?php endif ?>