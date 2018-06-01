<?php
if (defined('ABSPATH') === false) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-choice-label" itemprop="text">
	<?php if ($this->current == 'results'): ?>
		<?php $choice_percentage = number_format($choice['votes%']); ?>
		<?php $choice_percentage_decimal = $choice['votes%'] - floor($choice['votes%']); ?>
		<div class="totalpoll-choice-percentage" data-tp-percentage="<?php echo $choice['votes%']; ?>">
			<span class="totalpoll-choice-percentage-number"><?php echo $choice_percentage; ?></span><span
				class="totalpoll-choice-percentage-decimal">.<?php echo $choice_percentage_decimal; ?></span>
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