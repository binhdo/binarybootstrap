<?php
/**
 * The Sidebar containing the footer widget area.
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */
?>
<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
	<div id="tertiary" class="row widget-area">
		<?php dynamic_sidebar( 'sidebar-2' ); ?>
	</div>
<?php endif; ?>
