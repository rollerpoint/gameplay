<?php
/**
 * Template part to display full-view news-smart-box widget.
 *
 * @package Digezine
 * @subpackage widgets
 */
?>
<div class="news-smart-box__item-inner">
	<div class="news-smart-box__item-header">
		<?php echo $cats; ?>
		<?php echo $image; ?>
	</div>
	<div class="news-smart-box__item-content">
		<?php echo $title; ?>
		<div class="entry-meta">
			<?php echo $author; ?>
			<?php echo $date; ?>
			<?php echo $comments; ?>
		</div>
		<?php echo $excerpt; ?>
		<?php echo $more_btn; ?>
	</div>
</div>
