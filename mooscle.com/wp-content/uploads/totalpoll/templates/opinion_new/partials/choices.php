<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div data-tp-choices class="totalpoll-choices">
	<?php
	foreach ( $this->poll->choices() as $choice_index => $choice ):
		if ( $choice_index > 1 ):
			continue;
		endif;
		?>
		<div data-tp-choice class="totalpoll-choice totalpoll-choice-<?php echo $choice['content']['type']; ?> <?php echo $choice['checked'] ? 'checked' : ''; ?> totalpoll-choice-<?php echo $choice_index === 0 ? 'first' : 'second'; ?>" itemprop="suggestedAnswer" itemscope itemtype="http://schema.org/Answer">
			<label class="totalpoll-choice-container">
				<div class="totalpoll-choice-content">
					<?php
					if ( $this->current === 'vote' && $choice['content']['type'] !== 'other' ):
						include 'vote/checkbox.php';
					endif;
					?>
					<?php
					if ( $choice['content']['type'] !== 'html' && $choice['content']['type'] !== 'other' ):
						include 'shared/label.php';
					elseif ( $choice['content']['type'] === 'html' ):
						echo do_shortcode( $choice['content']['html'] );
					elseif ( $this->current === 'vote' && $choice['content']['type'] === 'other' ):
						include 'vote/other.php';
					endif;
					?>
				</div>
			</label>
		</div>
		<?php
	endforeach;
	?>
</div>