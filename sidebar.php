<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */
?>
<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="secondary" class="<?php echo binarybootstrap_secondary_class(); ?> widget-area" role="complementary">
		<?php do_action( 'before_sidebar' ); ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- #secondary -->
<?php endif; ?>
