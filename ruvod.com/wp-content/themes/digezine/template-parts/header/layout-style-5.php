<?php
/**
 * Template part for style-5 header layout.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */
?>
<div class="header-container_wrap container">
	<div class="header-container__flex">
		<div class="site-branding">
			<?php digezine_header_logo() ?>
			<?php digezine_site_description(); ?>
		</div>
		<?php digezine_main_menu(); ?>
		<?php digezine_header_btn(); ?>
	</div>
</div>
