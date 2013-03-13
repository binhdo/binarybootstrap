<?php
/**
 * The template for displaying search forms in Binary Bootstrap
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */
?>
	<form method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="assistive-text"><?php _ex( 'Search', 'assistive text', 'binarybootstrap' ); ?></label>
		<input type="search" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'binarybootstrap' ); ?>">
		<input type="submit" class="btn submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'binarybootstrap' ); ?>" />
	</form>
