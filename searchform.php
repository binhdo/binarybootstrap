<?php
/**
 * The template for displaying search forms
 *
 * @package WordPress
 * @subpackage Binary_Bootstrap
 * @since Binary Bootstrap 1.0
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
		<label>
			<span class="screen-reader-text"><?php echo _x( 'Search for:', 'binarybootstrap' ); ?></span>
		</label>
		<input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'binarybootstrap' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo _x( 'Search for:', 'binarybootstrap' ); ?>">
		<span class="input-group-btn">
			<button type="submit" class="search-submit btn btn-default"><?php echo esc_attr_x( 'Search', 'binarybootstrap' ); ?></button>
		</span>
	</div>
</form>