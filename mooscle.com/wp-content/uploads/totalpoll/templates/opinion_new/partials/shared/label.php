<?php
if (defined('ABSPATH') === false) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-choice-label" itemprop="text">
	<?php if ($this->current == 'results'): ?>
		<?php 
			  $total = 100; 
			  $votes = $choice['votes%'];
			  $votes_decimal = $votes - floor($votes);
			  if ($votes_decimal == 0.5) {
				if ($votes > 50) {
					$votes+=0.1;
				} else {
					$votes-=0.1;
				}
			  }
		?>
		<?php $choice_percentage = number_format($votes); ?>
		<?php $choice_percentage_decimal = $votes_decimal; ?>

		<div class="totalpoll-choice-percentage" data-tp-percentage="<?php echo $choice['votes%']; ?>">
			<span class="totalpoll-choice-percentage-number">
				<?php echo $choice_percentage; ?>
			</span>
			<span class="totalpoll-choice-percentage-sign">%</span>
		</div>
	<?php endif; ?>
	<div class="totalpoll-choice-label-text">
		<?php echo $choice['content']['label']; ?>

		<?php if ($this->current == 'results'): ?>
			<div class="totalpoll-choice-votes"><?php echo $this->votes($choice, false); ?></div>
		<?php endif; ?>
	</div>
</div>