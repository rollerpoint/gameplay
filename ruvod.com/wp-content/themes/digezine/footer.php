<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Digezine
 */

?>
	
	<?php
		$sidebar_name = is_front_page() ? 'after-loop-promo-sidebar' : null;
		if (!$sidebar_name) {
			$cat_id = get_query_var('cat');
			if (!$cat_id && $post) {
				$cat_id = wp_get_post_categories($post->ID, array(
					'parent' => 0,
					'fields' => 'ids',
				))[0];
			}
			if ($cat_id) {
				$cat = get_category($cat_id);
			}
			if ($cat) {
				$sidebar_name = $cat->slug.'-after-loop-promo-sidebar';
			}
		}
		
		if (is_active_sidebar($sidebar_name)){
			echo "<div class ='site-content_wrap container promo'><div class='after-loop-promo-block'>";
			ob_start();
			dynamic_sidebar($sidebar_name);
			$sidebar = ob_get_contents();
			ob_end_clean();
			echo $sidebar;
			echo "</div></div>";
		};
		?>
	</div><!-- #content -->

	<footer id="colophon" <?php digezine_footer_class() ?> role="contentinfo">
		<?php get_template_part( 'template-parts/footer/footer-area' ); ?>
		<?php get_template_part( apply_filters( 'digezine_footer_layout_template_slug', 'template-parts/footer/layout' ), get_theme_mod( 'footer_layout_type' ) ); ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
