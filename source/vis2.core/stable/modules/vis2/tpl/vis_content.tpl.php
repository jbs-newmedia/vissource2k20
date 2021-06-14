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
	<nav id="jbsadmin-navbar" class="navbar navbar-expand navbar-light bg-white mb-4 fixed-top shadow">

		<a class="navbar-brand d-flex align-items-center" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_dashboard') ?>">
			<div class="navbar-brand-icon">
				<?php if (pathinfo(\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_name'), PATHINFO_EXTENSION)=='svg'): ?>
					<img style="height:<?php echo \osWFrame\Core\Settings::getIntVar('vis2_logo_navi_height') ?>px" src="<?php echo $VIS2_Main->getResourceLink('img'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_name')) ?>" title="<?php echo \osWFrame\Core\Settings::getStringVar('vis2_logo_navi_title') ?>" alt="<?php echo \osWFrame\Core\Settings::getStringVar('vis2_logo_navi_title') ?>"/>
				<?php else: ?>

					<?php echo $this->getOptimizedImage(\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_name'), ['module'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_module'), 'title'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_title'), 'height'=>\osWFrame\Core\Settings::getIntVar('vis2_logo_navi_height')]) ?>

				<?php endif ?>
			</div>
			<div class="navbar-brand-text text-primary ms-2"><?php if (\osWFrame\Core\Settings::getStringVar('vis2_tool_'.$VIS2_Main->getTool().'_title')!=null): ?><?php echo \osWFrame\Core\Settings::getStringVar('vis2_tool_'.$VIS2_Main->getTool().'_title') ?><?php else: ?><?php echo $VIS2_Main->getToolName(); ?><?php endif ?></div>
		</a>

		<button id="sidebarToggleTopLeft" class="btn btn-link d-none d-md-block rounded-circle me-2">
			<i class="fa fa-bars"></i>
		</button>

		<div class="ms-auto"></div>

		<div class="pe-2">
			<div class="dropdown ms-auto">
				<a href="#" class="d-flex align-items-center link-dark text-decoration-none" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
					<span class="text-gray-600 ms-2 d-none d-md-block"><?php echo \osWFrame\Core\HTML::outputString($VIS2_User->getDisplayName(false)) ?></span>
					<img src="<?php echo $VIS2_User->getProfileImage(); ?>" height="46" class="ms-2"></a>
				<ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser2">
					<li>
						<a class="dropdown-item<?php if ($VIS2_Navigation->getPage()=='vis_profile'): ?> active<?php endif ?>" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_profile') ?>"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profil</a>
					</li>
					<li>
						<a class="dropdown-item<?php if ($VIS2_Navigation->getPage()=='vis_settings'): ?> active<?php endif ?>" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_settings') ?>"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Einstellungen</a>
					</li>
					<li>
						<hr class="dropdown-divider">
					</li>
					<li>
						<a class="dropdown-item" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_logout') ?>"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Abmelden</a>
					</li>
				</ul>
			</div>
		</div>

		<button id="sidebarToggleTopRight" class="btn btn-link d-md-none rounded-circle ms-2">
			<i class="fa fa-bars"></i>
		</button>

	</nav>

	<div class="d-flex w-100" id="jbsadmin-wrapper">

		<div id="jbsadmin-sidebar" class="sidebar collapse show me-2">

			<div class="bg-primary bg-gradient-dark-25-gradient-dark" style="width:14rem; position: fixed; float:left; height: 100%; background-attachment: fixed; z-index: -1;"></div>

			<a class="navbar-brand d-flex align-items-center bg-white position-fixed" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_dashboard') ?>">
				<div class="navbar-brand-icon">
					<?php if (pathinfo(\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_name'), PATHINFO_EXTENSION)=='svg'): ?>
						<img style="height:<?php echo \osWFrame\Core\Settings::getIntVar('vis2_logo_navi_height') ?>px" src="<?php echo $VIS2_Main->getResourceLink('img'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_name')) ?>" title="<?php echo \osWFrame\Core\Settings::getStringVar('vis2_logo_navi_title') ?>" alt="<?php echo \osWFrame\Core\Settings::getStringVar('vis2_logo_navi_title') ?>"/>
					<?php else: ?>

						<?php echo $this->getOptimizedImage(\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_name'), ['module'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_module'), 'title'=>\osWFrame\Core\Settings::getStringVar('vis2_logo_navi_title'), 'height'=>\osWFrame\Core\Settings::getIntVar('vis2_logo_navi_height')]) ?>

					<?php endif ?>
				</div>
				<div class="navbar-brand-text text-primary ms-2"><?php if (\osWFrame\Core\Settings::getStringVar('vis2_tool_'.$VIS2_Main->getTool().'_title')!=null): ?><?php echo \osWFrame\Core\Settings::getStringVar('vis2_tool_'.$VIS2_Main->getTool().'_title') ?><?php else: ?><?php echo $VIS2_Main->getToolName(); ?><?php endif ?></div>
			</a>

			<div id="jbsadmin-sidebar-nav" class="pt-3">
				<ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="jbsadmin-sidebar-navigation">

					<li class="nav-item w-100<?php if ($VIS2_Navigation->getPage()=='vis_dashboard'): ?> active<?php endif ?>">
						<a href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage=vis_dashboard') ?>" class="nav-link"><span>Dashboard</span></a>
					</li>

					<?php if ((\osWFrame\Core\Settings::getBoolVar('vis2_navigation_enabled')!==false)): ?>

						<?php foreach ($VIS2_Navigation->getNavigationWithPermission(0, 2) as $navigation_element): ?>

							<?php if ($navigation_element['info']['permission_link']==true): ?>

								<?php if (count($navigation_element['links'])>0): ?>

								<li class="nav-item nav-divider w-100"></li>

								<li class="nav-item w-100<?php if ($navigation_element['info']['navigation_active']==true): ?> active<?php endif ?>">

										<a href="#navi_vis2_<?php echo $navigation_element['info']['navigation_id'] ?>" class="nav-link" data-bs-toggle="collapse">
											<span><?php echo \osWFrame\Core\HTML::outputString($navigation_element['info']['navigation_title']) ?></span></a>
										<div class="collapse nav flex-column<?php if ($navigation_element['info']['navigation_active']==true): ?> show<?php endif ?>" id="navi_vis2_<?php echo $navigation_element['info']['navigation_id'] ?>" data-bs-parent="#jbsadmin-sidebar-navigation">
											<div class="nav-sub p-2 mb-2">

												<?php foreach ($navigation_element['links'] as $navigation_element): ?>

													<?php if ($navigation_element['info']['permission_view']==true): ?>
														<a class="collapse-item<?php if ($navigation_element['info']['navigation_active']===true): ?> active<?php endif ?>" href="<?php echo $this->buildHrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$navigation_element['info']['page_name_intern']) ?>">
													<?php endif ?>

													<?php echo \osWFrame\Core\HTML::outputString($navigation_element['info']['navigation_title']) ?>

													<?php if (($navigation_element['info']['page_name_intern']!==null)&&(\VIS2\Core\Badge::get($navigation_element['info']['page_name_intern'])!==null)): ?>
														<span class="vis2_navigation_badge" title="<?php echo VIS2\Core\Badge::get($navigation_element['info']['page_name_intern'], null) ?>"><?php echo VIS2\Core\Badge::get($navigation_element['info']['page_name_intern']) ?></span>
													<?php endif ?>

													<?php if ($navigation_element['info']['permission_view']==true): ?>
														</a>
													<?php endif ?>

												<?php endforeach ?>

											</div>
										</div>
									<?php endif ?>
								</li>
							<?php endif ?>

						<?php endforeach ?>

					<?php endif ?>


					<?php if (($VIS2_Main->getBoolVar('tool_use_mandant')===true)&&($VIS2_Main->getBoolVar('tool_use_mandantswitch')===true)): ?>

						<?php if (count($VIS2_User->getMandantenSelectArray())>1): ?>
							<li class="nav-item nav-divider w-100"></li>

							<li class="nav-item w-100">
								<a href="#vis2_mandant" class="nav-link" data-bs-toggle="collapse"> <span>Mandant wechseln</a>
								<div class="collapse nav flex-column" id="vis2_mandant" data-bs-parent="#jbsadmin-sidebar-navigation">
									<div class="nav-sub p-2 mb-2">

										<?php foreach ($VIS2_User->getMandantenSelectArray() as $mandant_id=>$mandant_name): ?><?php if ($mandant_id>0): ?>
											<a class="collapse-item<?php if ($VIS2_Mandant->getId()==$mandant_id): ?> active<?php endif ?>" href="<?php echo $this->buildhrefLink('current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&vis2_mandant_id='.$mandant_id) ?>"><?php echo \osWFrame\Core\HTML::outputString($mandant_name) ?></a>
										<?php endif ?><?php endforeach ?>

									</div>
								</div>
							</li>
						<?php endif ?>

					<?php endif ?>

					<?php if ((\osWFrame\Core\Settings::getBoolVar('vis2_toolswitch')===true)&&(count($VIS2_User->getToolsSelectArray())>1)): ?>

						<?php if (count($VIS2_User->getToolsSelectArray())>1): ?>
							<li class="nav-item nav-divider w-100"></li>

							<li class="nav-item w-100">
								<a href="#vis2_tool" class="nav-link" data-bs-toggle="collapse"> <span>Tool wechseln</a>
								<div class="collapse nav flex-column" id="vis2_tool" data-bs-parent="#jbsadmin-sidebar-navigation">
									<div class="nav-sub p-2 mb-2">

										<?php foreach ($VIS2_User->getToolsSelectArray() as $vis_tool=>$vis_tool_name): ?>
											<a class="collapse-item<?php if ($VIS2_Main->getTool()==$vis_tool): ?> active<?php endif ?>" href="<?php echo $this->buildHrefLink(('current'), 'vistool='.$vis_tool.'&vispage='.$VIS2_Navigation->getPage()) ?>"><?php echo \osWFrame\Core\HTML::outputString($vis_tool_name) ?></a>
										<?php endforeach ?>

									</div>
								</div>
							</li>
						<?php endif ?>

					<?php endif ?>

					<li class="border-top border-white mb-1 w-100"></li>

					<li class="nav-item w-100 mt-3 mb-3 pb-4 text-center">
						<button id="sidebarToggle" class="btn btn-link rounded-circle text-center text-light border bg-light" style="height:42px; width:42px;">
							<i class="fas fa-1x fa-angle-left  text-primary"></i>
						</button>
					</li>
				</ul>
			</div>
		</div>

		<div id="jbsadmin-content-wrapper" class="w-100">

			<div id="jbsadmin-content">

				<div class="container-fluid pb-2">

					<?php echo $vis2content ?>

				</div>

			</div>

		</div>

	</div>

	<a class="scroll-to-top rounded" href="#jbsadmin-body"> <i class="fas fa-angle-up"></i> </a>
<?php endif ?>