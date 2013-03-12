<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */
?>

	</div><!-- #main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php get_sidebar( 'footer' ); ?>
		<div class="site-info">
			<?php do_action( 'binarybootstrap_credits' ); ?>
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'binarybootstrap' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'binarybootstrap' ), 'WordPress' ); ?></a>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'binarybootstrap' ), 'binarybootstrap', '<a href="https://github.com/binhdo/binarybootstrap" rel="designer">binaryhideout</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>