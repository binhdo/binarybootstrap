<?php
/**
 * The sidebar containing the footer widget area.
 *
 * If no active widgets in this sidebar, it will be hidden completely.
 *
 * @package WordPress
 * @subpackage Binary_Bootstrap
 * @since Binary Bootstrap 1.0
 */

if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
<div id="secondary" class="sidebar-container <?php echo binarybootstrap_full_width_class(); ?>" role="complementary">
		<div class="widget-area row">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- .widget-area -->
	</div><!-- #secondary -->
<?php endif; ?>