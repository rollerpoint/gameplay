<?php
/**
 * The template for displaying search form.
 *
 * @package Digezine
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search-form__input-wrap">
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'digezine' ) ?></span>
		<?php echo apply_filters( 'digezine_search_form_input_icon', '<i class="linearicon linearicon-magnifier"></i>' ); ?>
		<input type="search" class="search-form__field" placeholder="<?php echo esc_attr_x( 'Enter keyword', 'placeholder', 'digezine' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'digezine' ) ?>" />
	</div>
	<button type="submit" class="search-form__submit btn btn-primary"><?php esc_html_e( 'Go!', 'digezine' ); ?></button>
</form>
