<?php
/**
 * Template part for displaying entry-meta-cats.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */
?>
<?php $utility = digezine_utility()->utility; ?>

<?php if ( 'post' === get_post_type() ) : ?>
		<?php $cats_visible = digezine_is_meta_visible( 'blog_post_categories', 'loop' );
		
		$company = get_post_meta(get_the_ID(),'company_blog',true);
		if ($company) {
			$company_id = get_post_meta(get_the_ID(),'company_id',true);
			$base_slug = get_post_meta($company_id,'wpcf-main_tag_slug',true);
			$company_tags = "";
			$tag = get_term_by('slug', 'blogs', 'post_tag');
			$company_tags.='<a href="'.get_tag_link($tag->term_id).'" class="company_tag">'.$tag->name.'</a>';
			$tag = get_term_by('slug', $base_slug, 'post_tag');
			$company_tags.='<a href="'.get_tag_link($tag->term_id).'" class="company_tag">'.$tag->name.'</a>';
		}
		
		$utility->meta_data->get_terms( array(
			'visible'   => $cats_visible,
			'type'      => 'category',
			'before'    => '<span class="post__cats">'.$company_tags,
			'after'     => '</span>',
			'echo'      => true,
		) );
		?>

<?php endif; ?>
