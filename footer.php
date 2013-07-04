<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Binary_Bootstrap
 * @since Binary Bootstrap 1.0
 */
?>

		</div><!-- #main -->
		<footer id="colophon" class="site-footer row" role="contentinfo">
			<?php get_sidebar( 'main' ); ?>

			<div class="site-info <?php echo binarybootstrap_full_width_class(); ?>">
				<?php do_action( 'binarybootstrap_credits' ); ?>
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'binarybootstrap' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'binarybootstrap' ); ?>"><?php printf( __( 'Proudly powered by %s', 'binarybootstrap' ), 'WordPress' ); ?></a>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>