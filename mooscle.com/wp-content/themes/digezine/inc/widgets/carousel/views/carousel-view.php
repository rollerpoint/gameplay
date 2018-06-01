<?php
/**
 * Template part to display Carousel widget.
 *
 * @package Digezine
 * @subpackage widgets
 */
?>

<div class="swiper-slide-inner inner">
	<?php echo $cats; ?>
	<?php echo $image; ?>
	<div class="content-wrapper">
		<header class="entry-header">
			<?php echo $title; ?>
		</header>
		<div class="entry-meta">
			<?php echo $author; ?>
			<?php echo $date; ?>
		</div>
		<div class="entry-content">
			<?php echo $content; ?>
		</div>
		<footer class="entry-footer">
			<?php echo $more_button; ?>
		</footer>
	</div>
</div>
