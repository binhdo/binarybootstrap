<?php
/**
 * The template for displaying image attachments.
 *
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */

get_header();
?>

	<div id="primary" class="<?php echo binarybootstrap_primary_class( false ); ?> content-area image-attachment">
		<div id="content" class="site-content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="help-block entry-meta">
						<?php
							$published_text  = __( '<i class="glyphicon glyphicon-time"></i> <span class="attachment-meta"><time class="entry-date" datetime="%1$s">%2$s</time> in <a href="%3$s" title="Return to %4$s" rel="gallery">%5$s</a></span> ', 'binarybootstrap' );
							$post_title = get_the_title( $post->post_parent );
							if ( empty( $post_title ) || 0 == $post->post_parent )
								$published_text  = '<span class="attachment-meta"><time class="entry-date" datetime="%1$s">%2$s</time></span> ';

							printf( $published_text,
								esc_attr( get_the_date( 'c' ) ),
								esc_html( get_the_date() ),
								esc_url( get_permalink( $post->post_parent ) ),
								esc_attr( strip_tags( $post_title ) ),
								$post_title
							);

							$metadata = wp_get_attachment_metadata();
							printf( '<i class="glyphicon glyphicon-picture"></i> <span class="attachment-meta full-size-link"><a href="%1$s" title="Link to full-size image">%2$s &times; %3$s</a></span>',
								esc_url( wp_get_attachment_url() ),
								$metadata['width'],
								$metadata['height']
							);
						?>
					</div><!-- .entry-meta -->
				</header><!-- .entry-header -->

				<div class="clearfix entry-content">

					<div class="text-center entry-attachment">
						<figure class="attachment">
							<?php binarybootstrap_the_attached_image(); ?>
							
						<?php if ( ! empty( $post->post_excerpt ) ) : ?>
						<figcaption class="entry-caption">
							<?php the_excerpt(); ?>
						</figcaption><!-- .entry-caption -->
						<?php endif; ?>
						</figure><!-- .attachment -->
					</div><!-- .entry-attachment -->

					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'binarybootstrap' ), 'after' => '</div>' ) ); ?>

				</div><!-- .entry-content -->

				<footer class="help-block entry-meta">
					<ul id="image-navigation" class="pager navigation-image">
						<li class="previous"><?php previous_image_link( false, __( '&larr; Previous', 'binarybootstrap' ) ); ?></li>
						<li class="next"><?php next_image_link( false, __( 'Next &rarr;', 'binarybootstrap' ) ); ?></li>
					</ul><!-- #image-navigation -->
				
					<?php edit_post_link( __( 'Edit', 'binarybootstrap' ), ' <span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-meta -->
			</article><!-- #post-<?php the_ID(); ?> -->

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

		<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>