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
 * @var string $element
 * @var \osWFrame\Core\DDM4 $this
 *
 */

$this->readParameters();
$links = $this->getAddElementOption($element, 'data');
$links_anz = 0;
if (is_array($links)) {
    $links_anz = count($links);
}

?>

<?php if ($links_anz > 0): ?>
    <ul class="nav nav-tabs ddm_element_<?php echo $this->getAddElementValue($element, 'id') ?>" role="tablist">
        <?php $i = 0;
    foreach ($links as $link_id => $__link):$i++; ?><?php
        if (isset($__link['navigation_id'])) {
            $link_id = $__link['navigation_id'];
        }
        ?>
            <li class="nav-item">
                <a class="nav-link<?php if ($this->getParameter(
                    'ddm_navigation_id'
                ) === $link_id
                ): ?> active<?php endif ?>" <?php echo ((isset($__link['target'])) ? ' target="' . $__link['target'] . '"' : ''); ?>
                   href="<?php echo $this->getTemplate()->buildhrefLink(
                       (($__link['module']) ? $__link['module'] : $this->getDirectModule()),
                       'ddm_navigation_id=' . $link_id . (($__link['parameter']) ? '&' . $__link['parameter'] : '')
                   ) ?>"><?php echo (($__link['text']) ? osWFrame\Core\HTML::outputString(
                       $__link['text']
                   ) : 'undefined') ?></a>
            </li>
        <?php endforeach ?>
    </ul>
    <div class="clearfix"><br/></div>
<?php endif ?>
