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
 * @var string $vis2content
 * @var string $avalynx_sidebar
 * @var \VIS2\Core\Main $VIS2_Main
 * @var \VIS2\Core\Mandant $VIS2_Mandant
 * @var \VIS2\Core\Navigation $VIS2_Navigation
 * @var \VIS2\Core\User $VIS2_User
 * @var \osWFrame\Core\Template $this
 *
 */

use osWFrame\Core\HTML;
use osWFrame\Core\Settings;
use VIS2\Core\Badge;

?>

<?php if (Settings::catchIntValue('modal', 0, 'pg') === 1): ?>

    <?php echo $vis2content ?>

<?php else: ?>

    <header id="avalynx-app-header" class="sticky-top d-flex border-bottom">
        <div class="avalynx-brand d-flex align-items-center justify-content-start" style="padding-left:16px;">

            <a href="<?php echo $this->buildHrefLink(
                'current',
                'vistool='.$VIS2_Main->getTool().'&vispage=vis_dashboard'
            ) ?>">
                <?php if (pathinfo(Settings::getStringVar('vis2_logo_navi_name'), \PATHINFO_EXTENSION) === 'svg'): ?>

                    <img style="height:36px"
                         src="<?php echo $VIS2_Main->getResourceLink(
                             'img'.\DIRECTORY_SEPARATOR.Settings::getStringVar('vis2_logo_navi_name')
                         ) ?>"
                         title="<?php echo Settings::getStringVar('vis2_logo_navi_title') ?>"
                         alt="<?php echo Settings::getStringVar('vis2_logo_navi_title') ?>"
                         class="me-2"/>

                <?php else: ?>

                    <?php echo $this->getOptimizedImage(Settings::getStringVar('vis2_logo_navi_name'), [
                        'module' => Settings::getStringVar('vis2_logo_navi_module'),
                        'path' => Settings::getStringVar('vis2_logo_path'),
                        'title' => Settings::getStringVar('vis2_logo_navi_title'),
                        'height' => Settings::getIntVar('vis2_logo_navi_height'),
                        'parameter' => 'class="me-2" style="height:36px;"',
                    ]) ?>

                <?php endif ?>

            </a>

            <span><?php if (Settings::getStringVar(
                        'vis2_tool_'.$VIS2_Main->getTool().'_title'
                    ) !== null
                ): ?><?php echo Settings::getStringVar(
                    'vis2_tool_'.$VIS2_Main->getTool().'_title'
                ) ?><?php else: ?><?php echo $VIS2_Main->getToolName(); ?><?php endif ?></span>

        </div>

        <!-- Sidebar toggle Start (only large) -->
        <button
            class="btn btn-link justify-content-center align-items-center avalynx-toggler-sidenav d-none d-md-flex avalynx-header-button"
            onclick="avalynx_toggleSidenav()" title="Toggle sidebar">
            <i class="fa-solid fa-align-left fa-fw"></i>
        </button>
        <!-- Sidebar toggle End (only large) -->

        <!-- Spacer Start -->
        <div class="flex-grow-1"></div>
        <!-- Spacer End -->

        <!-- Darkmode Start -->
        <button
            class="btn btn-link justify-content-center align-items-center avalynx-toggler-darkmode avalynx-header-button"
            onclick="avalynx_toggleDarkmode()" title="Toggle darkmode">
            <i class="bi bi-circle-half"></i>
        </button>
        <!-- Darkmode End -->


        <?php if ((Settings::getBoolVar('vis2_toolswitch') === true) && (count(
                    $VIS2_User->getToolsSelectArray()
                ) > 1)
        ): ?>

            <?php $c = count($VIS2_User->getToolsSelectArray());
            if ($c > 1): ?>

                <!-- Select tool Start -->
                <button class="btn btn-link justify-content-center align-items-center avalynx-header-button"
                        id="dropdownMenuTool" data-bs-toggle="dropdown" aria-expanded="false" title="Tool wechseln">
                    <i class="fa-solid fa-building fa-fw"></i>
                </button>


                <ul class="dropdown-menu dropdown-menu-end rounded-0 me-1 avalynx-livesearch<?php if ($c > 10): ?> avalynx-livesearch-show<?php endif ?>"
                    aria-labelledby="dropdownMenuTool">

                        <li>
			            <span class="dropdown-item-text"><input type="text"
                                                                class="form-control avalynx-livesearch-input"
                                                                placeholder="Suchen...">
                        </li>

                    <?php foreach ($VIS2_User->getToolsSelectArray() as $vis_tool => $vis_tool_name): ?>
                        <li class="d-none"><a
                                class="dropdown-item<?php if ($VIS2_Main->getTool() === $vis_tool
                                ): ?> active<?php endif ?>" href="<?php echo $this->buildHrefLink(
                                'current',
                                'vistool='.$vis_tool.'&vispage='.$VIS2_Navigation->getPage()
                            ) ?>"><?php echo HTML::outputString($vis_tool_name) ?></a></li>
                    <?php endforeach ?>
                </ul>

                <!-- Select tool End -->


            <?php endif ?>

        <?php endif ?>


        <?php if (($VIS2_Main->getBoolVar('tool_use_mandant') === true) && ($VIS2_Main->getBoolVar(
                    'tool_use_mandantswitch'
                ) === true)
        ): ?>

            <?php $c = count($VIS2_User->getMandantenSelectArray());
            if ($c > 1): ?>

                <!-- Select client Start -->
                <button class="btn btn-link justify-content-center align-items-center avalynx-header-button"
                        id="dropdownMenuClient" data-bs-toggle="dropdown" aria-expanded="false" title="Mandant wählen">
                    <i class="fa-solid fa-database fa-fw"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end rounded-0 me-1 avalynx-livesearch<?php if ($c > 10): ?> avalynx-livesearch-show<?php endif ?>"
                    aria-labelledby="dropdownMenuClient">

                    <li>
			            <span class="dropdown-item-text"><input type="text"
                                                                class="form-control avalynx-livesearch-input"
                                                                placeholder="Suchen...">
                    </li>

                    <?php foreach ($VIS2_User->getMandantenSelectArray(
                    ) as $mandant_id => $mandant_name): ?><?php if ($mandant_id > 0): ?>
                    <li class="d-none"><a
                            class="dropdown-item<?php if ($VIS2_Mandant->getId() === $mandant_id
                            ): ?> active<?php endif ?>"
                            href="<?php echo $this->buildhrefLink(
                                'current',
                                'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(
                                ).'&vis2_mandant_id='.$mandant_id
                            ) ?>"><?php echo HTML::outputString($mandant_name) ?></a>
                        <?php endif ?><?php endforeach ?>

                </ul>
                <!-- Select client End -->
            <?php endif ?>

        <?php endif ?>

        <!-- Profile Start -->
        <button class="btn btn-link justify-content-center align-items-center" id="dropdownMenuProfile"
                data-bs-toggle="dropdown" aria-expanded="false" title="Profile">
            <img src="data/resources/bootstrap/avalynx/<?php echo \osWFrame\Core\Settings::getStringVar(
                'vendor_lib_bootstrap_avalynx_version'
            ) ?>/img/profile.jpg" class="h-100 rounded-circle" alt="profile-image">
        </button>
        <div class="dropdown-menu rounded-0 me-1" aria-labelledby="dropdownMenuProfile">
            <a class="dropdown-item<?php if ($VIS2_Navigation->getPage() === 'vis_profile'): ?> active<?php endif ?>"
               href="<?php echo $this->buildHrefLink(
                   'current',
                   'vistool='.$VIS2_Main->getTool().'&vispage=vis_profile'
               ) ?>"><i class="fa-solid fa-user fa-fw"></i> Profil</a>
            <a class="dropdown-item<?php if ($VIS2_Navigation->getPage() === 'vis_settings'): ?> active<?php endif ?>"
               href="<?php echo $this->buildHrefLink(
                   'current',
                   'vistool='.$VIS2_Main->getTool().'&vispage=vis_settings'
               ) ?>"><i class="fa-solid fa-gear fa-fw"></i> Einstellungen</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?php echo $this->buildHrefLink(
                'current',
                'vistool='.$VIS2_Main->getTool().'&vispage=vis_logout'
            ) ?>"><i class="fas fa-sign-out-alt fa-fw"></i> Abmelden</a>
        </div>
        <!-- Profile End -->

        <!-- Sidebar toggle Start (only small) -->
        <button
            class="btn btn-link justify-content-center align-items-center avalynx-toggler-sidenav d-flex d-md-none avalynx-header-button"
            onclick="avalynx_toggleSidenav()" title="Toggle sidebar">
            <i class="fa-solid fa-align-left fa-fw"></i>
        </button>
        <!-- Sidebar toggle End (only small) -->

    </header>


    <div id="avalynx-app-main" class="d-flex">

        <div id="avalynx-app-loader" class="position-absolute w-100 border-top">
            <div class="position-absolute top-50 start-50 translate-middle">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

        <div id="avalynx-app-sidenav">

            <div id="avalynx-app-sidenav-header"
                 class="offcanvas-header avalynx-brand d-flex align-items-center justify-content-start border-bottom p-0 m-0"
                 style="padding-left:16px !important;">

                <a href="<?php echo $this->buildHrefLink(
                    'current',
                    'vistool='.$VIS2_Main->getTool().'&vispage=vis_dashboard'
                ) ?>">
                    <?php if (pathinfo(
                            Settings::getStringVar('vis2_logo_navi_name'),
                            \PATHINFO_EXTENSION
                        ) === 'svg'): ?>

                        <img style="height:36px"
                             src="<?php echo $VIS2_Main->getResourceLink(
                                 'img'.\DIRECTORY_SEPARATOR.Settings::getStringVar('vis2_logo_navi_name')
                             ) ?>"
                             title="<?php echo Settings::getStringVar('vis2_logo_navi_title') ?>"
                             alt="<?php echo Settings::getStringVar('vis2_logo_navi_title') ?>"
                             class="me-2"/>

                    <?php else: ?>

                        <?php echo $this->getOptimizedImage(Settings::getStringVar('vis2_logo_navi_name'), [
                            'module' => Settings::getStringVar('vis2_logo_navi_module'),
                            'path' => Settings::getStringVar('vis2_logo_path'),
                            'title' => Settings::getStringVar('vis2_logo_navi_title'),
                            'height' => Settings::getIntVar('vis2_logo_navi_height'),
                            'parameter' => 'class="me-2" style="height:36px;"',
                        ]) ?>

                    <?php endif ?>
                </a>


                <span><?php if (Settings::getStringVar(
                            'vis2_tool_'.$VIS2_Main->getTool().'_title'
                        ) !== null
                    ): ?><?php echo Settings::getStringVar(
                        'vis2_tool_'.$VIS2_Main->getTool().'_title'
                    ) ?><?php else: ?><?php echo $VIS2_Main->getToolName(); ?><?php endif ?></span>

            </div>

            <div id="avalynx-app-sidenav-body" class="p-0">

                <div class="p-2 pe-1">

                    <ul class="avalynx-sidenav" id="avalynx-sidenav-content">
                        <li class="avalynx-sidenav-item">
                            <h2 class="avalynx-sidenav-header" id="avalynx-sidenav-content-main_item-heading">
                                <a class="avalynx-sidenav-link<?php if ($VIS2_Navigation->getPage(
                                    ) === 'vis_dashboard'): ?> active<?php endif ?>" type="button"
                                   href="<?php echo $this->buildHrefLink(
                                       'current',
                                       'vistool='.$VIS2_Main->getTool().'&vispage=vis_dashboard'
                                   ) ?>">
                                    <span class="title">Dashboard</span>
                                </a>
                            </h2>
                        </li>


                        <?php if (Settings::getBoolVar('vis2_navigation_enabled') !== false): ?>

                            <?php foreach ($VIS2_Navigation->getNavigationWithPermission(
                                0,
                                2
                            ) as $navigation_element): ?>

                                <?php if ($navigation_element['info']['permission_link'] === true): ?>

                                    <?php if (count($navigation_element['links']) > 0): ?>

                                        <li class="avalynx-sidenav-line"></li>

                                        <li class="avalynx-sidenav-item<?php if ($navigation_element['info']['navigation_active'] === true): ?> active<?php endif ?>">

                                        <h2 class="avalynx-sidenav-header"
                                            id="avalynx-sidenav-content-pill_and_badge_item_<?php echo $navigation_element['info']['navigation_id'] ?>-heading">
                                            <button class="avalynx-sidenav-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#avalynx-sidenav-content-pill_and_badge_item_<?php echo $navigation_element['info']['navigation_id'] ?>-collapse"
                                                    aria-expanded="false"
                                                    aria-controls="avalynx-sidenav-content-pill_and_badge_item_<?php echo $navigation_element['info']['navigation_id'] ?>-collapse">
                                            <span
                                                class="title"><?php echo HTML::outputString(
                                                    $navigation_element['info']['navigation_title']
                                                ) ?></span></button>
                                        </h2>


                                        <div
                                            id="avalynx-sidenav-content-pill_and_badge_item_<?php echo $navigation_element['info']['navigation_id'] ?>-collapse"
                                            class="avalynx-sidenav-collapse <?php if ($navigation_element['info']['navigation_active'] !== true): ?> collapse<?php endif ?>"
                                            aria-labelledby="avalynx-sidenav-content-pill_and_badge_item_<?php echo $navigation_element['info']['navigation_id'] ?>-heading"
                                            data-bs-parent="#avalynx-sidenav-content" style="">
                                            <div class="avalynx-sidenav-body">

                                                <ul class="avalynx-sidenav-sub1">

                                                    <?php foreach ($navigation_element['links'] as $navigation_element): ?>

                                                        <?php if ($navigation_element['info']['permission_link'] === true): ?>

                                                            <li class="avalynx-sidenav-sub1-item">

                                                                <h2 class="avalynx-sidenav-sub1-header"
                                                                    id="avalynx-sidenav-content-pill_and_badge_item_1-heading">

                                                                    <?php if ($navigation_element['info']['permission_view'] === true): ?>
                                                                    <a class="avalynx-sidenav-sub1-link <?php if ($navigation_element['info']['navigation_active'] === true): ?> active<?php endif ?>"
                                                                       type="button"
                                                                       href="<?php echo $this->buildHrefLink(
                                                                           'current',
                                                                           'vistool='.$VIS2_Main->getTool(
                                                                           ).'&vispage='.$navigation_element['info']['page_name_intern']
                                                                       ) ?>">
                                                                        <?php endif ?>

                                                                        <span class="title">
                                                    <?php echo HTML::outputString(
                                                        $navigation_element['info']['navigation_title']
                                                    ) ?>
                                                        </span>

                                                                        <?php if (($navigation_element['info']['page_name_intern'] !== null) && (Badge::get(
                                                                                    $navigation_element['info']['page_name_intern']
                                                                                ) !== null)
                                                                        ): ?>
                                                                            <span
                                                                                class="badge rounded-pill text-bg-secondary"
                                                                                title="<?php echo VIS2\Core\Badge::get(
                                                                                    $navigation_element['info']['page_name_intern'],
                                                                                    null
                                                                                ) ?>"><?php echo VIS2\Core\Badge::get(
                                                                                    $navigation_element['info']['page_name_intern']
                                                                                ) ?></span>
                                                                        <?php endif ?>

                                                                        <?php if ($navigation_element['info']['permission_view'] === true): ?>
                                                                    </a>
                                                                <?php endif ?>

                                                                </h2>

                                                            </li>

                                                        <?php endif ?>

                                                    <?php endforeach ?>

                                                </ul>

                                            </div>
                                        </div>
                                    <?php endif ?>
                                    </li>
                                <?php endif ?>

                            <?php endforeach ?>

                        <?php endif ?>

                    </ul>

                </div>

            </div>

        </div>

        <div id="avalynx-app-container" class="flex-grow-1">

            <!-- Content Start -->
            <?php echo $vis2content ?>
            <!-- Content End -->

        </div>

    </div>

    <?php /*


               <?php if (($VIS2_Main->getBoolVar('tool_use_mandant') === true) && ($VIS2_Main->getBoolVar(
                                    'tool_use_mandantswitch'
                                ) === true)
                        ): ?>

                            <?php if (count($VIS2_User->getMandantenSelectArray()) > 1): ?>
                                <li class="nav-item nav-divider w-100"></li>

                                <li class="nav-item w-100">
                                    <a href="#vis2_mandant" class="nav-link" data-bs-toggle="collapse"> <span>Mandant wechseln</a>
                                    <div class="collapse nav flex-column" id="vis2_mandant"
                                         data-bs-parent="#jbsadmin-sidebar-navigation">
                                        <div class="nav-sub p-2 mb-2">

                                            <?php foreach ($VIS2_User->getMandantenSelectArray(
                                            ) as $mandant_id => $mandant_name): ?><?php if ($mandant_id > 0): ?>
                                                <a class="collapse-item<?php if ($VIS2_Mandant->getId() === $mandant_id
                                                ): ?> active<?php endif ?>"
                                                   href="<?php echo $this->buildhrefLink(
                                                       'current',
                                                       'vistool='.$VIS2_Main->getTool(
                                                       ).'&vispage='.$VIS2_Navigation->getPage(
                                                       ).'&vis2_mandant_id='.$mandant_id
                                                   ) ?>"><?php echo HTML::outputString($mandant_name) ?></a>
                                            <?php endif ?><?php endforeach ?>

                                        </div>
                                    </div>
                                </li>
                            <?php endif ?>

                        <?php endif ?>

                        <?php if ((Settings::getBoolVar('vis2_toolswitch') === true) && (count(
                                    $VIS2_User->getToolsSelectArray()
                                ) > 1)
                        ): ?>

                            <?php if (count($VIS2_User->getToolsSelectArray()) > 1): ?>
                                <li class="nav-item nav-divider w-100"></li>

                                <li class="nav-item w-100">
                                    <a href="#vis2_tool" class="nav-link" data-bs-toggle="collapse"> <span>Tool wechseln</a>
                                    <div class="collapse nav flex-column" id="vis2_tool"
                                         data-bs-parent="#jbsadmin-sidebar-navigation">
                                        <div class="nav-sub p-2 mb-2">

                                            <?php foreach ($VIS2_User->getToolsSelectArray(
                                            ) as $vis_tool => $vis_tool_name): ?>
                                                <a class="collapse-item<?php if ($VIS2_Main->getTool() === $vis_tool
                                                ): ?> active<?php endif ?>"
                                                   href="<?php echo $this->buildHrefLink(
                                                       'current',
                                                       'vistool='.$vis_tool.'&vispage='.$VIS2_Navigation->getPage()
                                                   ) ?>"><?php echo HTML::outputString($vis_tool_name) ?></a>
                                            <?php endforeach ?>

                                        </div>
                                    </div>
                                </li>
                            <?php endif ?>

                        <?php endif ?>
*/ ?>
    <a class="scroll-to-top" id="scrollToTop"> <i class="fas fa-angle-up"></i> </a>
<?php endif ?>
