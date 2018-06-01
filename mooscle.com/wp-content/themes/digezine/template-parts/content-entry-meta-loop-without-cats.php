<?php
/**
 * Template part for displaying entry-meta.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package digezine
 */
?>
<?php $utility = digezine_utility()->utility; ?>

<?php if ( 'post' === get_post_type() ) : ?>
	<?php $sluhi = has_category( 'sluhi', get_the_ID() ) ?>
	<div class="entry-meta">

		<?php $date_visible = digezine_is_meta_visible( 'blog_post_publish_date', 'loop' );

		$utility->meta_data->get_date( array(
			'visible' => $date_visible,
			'html'    => '<span class="post__date">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s">%6$s%7$s</time></a></span>',
			'class'   => 'post__date-link',
			'echo'    => true,
		) );
		?>

		<?php $author_visible = digezine_is_meta_visible( 'blog_post_author', 'loop' );
		
		$author_link = $utility->meta_data->get_author( array(
			'visible' => $author_visible,
			'class'   => 'posted-by__author',
			'prefix'  => esc_html__( 'by ', 'digezine' ),
			'html'    => '<span class="posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
			'echo'    => false,
		) );
		$company_id = get_post_meta(get_the_ID(),'company_id',true);
		if ($company_id && !get_query_var('is_my_company_page')) {
			$author = get_the_title($company_id);
			$link = get_permalink($company_id);
			ob_start();
			?>
			<span class="posted-by <?php echo $author_visible ? '' : 'hidden'  ?>">
				<?php echo esc_html__( 'by ', 'digezine' ) ?> 
				<a href="<?php echo $link; ?>" class="posted-by__author" rel="author">
					<?php echo $author; ?>
				</a>
			</span>
			<?php
			$author_link = ob_get_contents();
			ob_end_clean();
		}
		echo $author_link; ?>

		<?php $comment_visible = digezine_is_meta_visible( 'blog_post_comments', 'loop' );

		$utility->meta_data->get_comment_count( array(
			'visible' => $comment_visible && !$sluhi,
			'html'    => '<span class="post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
			'sufix'   => get_comments_number_text( esc_html__( 'No comment(s)', 'digezine' ), esc_html__( '1 comment', 'digezine' ), esc_html__( '% comments', 'digezine' ) ),
			'class'   => 'post__comments-link',
			'echo'    => true,
		) );
		?>

		<?php $tags_visible = digezine_is_meta_visible( 'blog_post_tags', 'loop' );

		$utility->meta_data->get_terms( array(
			'visible'   => $tags_visible && !$sluhi,
			'type'      => 'post_tag',
			'delimiter' => ', ',
			'before'    => '<span class="post__tags">',
			'after'     => '</span>',
			'echo'      => true,
		) );

		$expert_comments = get_post_meta(get_the_ID(), 'expert_comments_count', true);
		if ($expert_comments && $expert_comments > 0) {
			echo '<span class="post__comments post_expert_comments" title="Комментарии экспертов">';
			echo '<a href="'.get_permalink(get_the_ID()).'#expert-comments">';
			echo '<i class="flaticon-interface"></i>';
			echo $expert_comments;
			echo '</a>';
			echo '</span>';
		}
		?>
		<?php $comment_visible = wp_count_comments(get_the_ID())->approved > 0;
		$utility->meta_data->get_comment_count( array(
			'visible' => $comment_visible,
			'html'    => '<span class="post__comments" title="Комментарии пользователей">%1$s<a href="%2$s" %3$s %4$s><i class="flaticon-interface"></i>%5$s%6$s</a></span>',
			'class'   => 'post__comments-link',
			'echo'    => true,
		) );
		?>
	</div><!-- .entry-meta -->

<?php endif; ?>
