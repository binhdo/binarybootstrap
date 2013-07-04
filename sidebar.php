<?php
/**
 * The sidebar containing the secondary widget area, displays on posts and pages.
 *
 * If no active widgets in this sidebar, it will be hidden completely.
 *
 * @package WordPress
 * @subpackage Binary_Bootstrap
 * @since Binary Bootstrap 1.0
 */

if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
<div id="tertiary" class="sidebar-container <?php echo binarybootstrap_tertiary_class(); ?>" role="complementary">
			<div class="widget-area row">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div><!-- .widget-area -->
	</div><!-- #tertiary -->
<?php endif; ?>