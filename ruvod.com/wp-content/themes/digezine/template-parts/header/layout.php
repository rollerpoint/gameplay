<?php
/**
 * Template part for default header layout.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */
?>
<div class="header-container_wrap container">
	<div class="header-container__flex <?php if (is_active_sidebar('top-promo-sidebar') && is_user_logged_in()) { echo 'with-top-promo-block'; }?>">
		<div class="site-branding">
			<?php digezine_header_logo() ?>
			<?php digezine_site_description(); ?>
		</div>

		<?php digezine_main_menu(); ?>
		
		<?php digezine_header_btn(); ?>
		
	</div>
	<?php
		global $post;
		$sidebar_name = is_front_page() ? 'top-promo-sidebar' : null;
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
				$sidebar_name = $cat->slug.'-promo-sidebar';
			}
		}
		if ($sidebar_name && is_active_sidebar($sidebar_name)){
			echo "<div class='top-promo-block'>";
			ob_start();
			dynamic_sidebar($sidebar_name);
			$sidebar = ob_get_contents();
			ob_end_clean();
			echo $sidebar;
			echo "</div>";
		}
	?>
</div>
