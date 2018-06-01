<?php
/**
 * Template part to display a single post while in a layout posts loop
 *
 * @package Digezine
 * @subpackage widgets
 */

?>
<div class="widget-fpblock__item invert widget-fpblock__item-<?php echo $key; ?> widget-fpblock__item-<?php echo esc_attr( $special_class ); ?> post-<?php the_ID(); ?>">
	<div class="post-thumbnail">
		<?php echo $image; ?>
	</div>
	<div class="widget-fpblock__item-inner">
		<?php echo $cats; ?>
		<?php echo $title; ?>
		<div class="entry-meta">
			<?php echo $author; ?>
			<?php echo $date; ?>
			<?php echo $tags; ?>
		</div>
		<?php echo $content; ?>
	</div>
</div>
