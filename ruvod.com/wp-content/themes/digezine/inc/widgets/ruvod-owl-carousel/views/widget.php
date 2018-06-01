<?php
/**
 * Template part to display a single layout.
 *
 * @package Digezine
 * @subpackage widgets
 */
?>
<?php echo $this->render_layout( array(
	'layout'  => $this->_default_layout,
	'wrapper' => '<div class="widget-owl-carousel not-owl">%2$s</div>',
) );
