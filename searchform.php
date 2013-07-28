<?php
/**
 * The template for displaying search forms
 *
 * @package WordPress
 * @subpackage Binary_Bootstrap
 * @since Binary Bootstrap 1.0
 */
?>
<form role="search" method="get" class="search-form" action="<?php esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
		<label>
			<span class="screen-reader-text"><?php echo _x( 'Search for:', 'binarybootstrap' ); ?></span>
		</label>
		<input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'binarybootstrap' ); ?>" value="<?php get_search_query(); ?>" name="s" title="<?php echo _x( 'Search for:', 'binarybootstrap' ); ?>">
		<span class="input-group-btn">
		<input type="submit" class="search-submit btn btn-default" value="<?php echo esc_attr_x( 'Search', 'binarybootstrap' ); ?>">
		</span>
	</div>
</form>