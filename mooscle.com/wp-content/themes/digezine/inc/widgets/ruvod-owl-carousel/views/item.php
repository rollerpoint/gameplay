<?php
/**
 * Template part to display a single post while in a layout posts loop
 *
 * @package Digezine
 * @subpackage widgets
 */

?>
<div  class="item ruvod-owl-item">
	
	<div class="ruvod-owl-content">
		<div class="owl-inline-helper">

		</div>
		<div class="ruvod-owl-info">
			<?php echo $cats; ?>
			<div class="title">
				<?php echo $title; ?>
			</div>
			<div class="author">
				<?php echo $author; ?>
			</div>
			<div class="date">
				<?php echo $date; ?>
			</div>
		</div>
		<a href="<?php echo get_permalink($post); ?>" class="ruvod-owl-background"></a>
	</div>
	<div class="ruvod-owl-image">
		<?php echo $image; ?>
	</div>
</div>
