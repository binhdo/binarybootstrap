<?php
/**
 * The template for displaying full-width pages.
 * 
 * Template Name: Full-width page template, no sidebar
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */

get_header(); ?>

	<div id="primary" class="<?php echo binarybootstrap_primary_class( false ); ?> content-area">
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();
				?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
