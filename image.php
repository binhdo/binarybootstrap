<?php
/**
 * The template for displaying image attachments.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Binary_Bootstrap
 * @since Binary Bootstrap 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area <?php echo binarybootstrap_primary_class( false ); ?>">
		<div id="content" class="site-content" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-meta">
						<?php
							$published_text = __( '<span class="glyphicon glyphicon-calendar"></span><span class="attachment-meta"><time class="entry-date" datetime="%1$s">%2$s</time> <span class="glyphicon glyphicon-link"></span> <a href="%3$s" title="Return to %4$s" rel="gallery">%5$s</a></span>', 'binarybootstrap' );
							$post_title = get_the_title( $post->post_parent );
							if ( empty( $post_title ) || 0 == $post->post_parent )
								$published_text = '<span class="glyphicon glyphicon-calendar"><span class="attachment-meta"><time class="entry-date" datetime="%1$s">%2$s</time></span>';

							printf( $published_text,
								esc_attr( get_the_date( 'c' ) ),
								esc_html( get_the_date() ),
								esc_url( get_permalink( $post->post_parent ) ),
								esc_attr( strip_tags( $post_title ) ),
								$post_title
							);

							$metadata = wp_get_attachment_metadata();
							printf( '<span class="glyphicon glyphicon-picture"></span><span class="attachment-meta full-size-link"><a href="%1$s" title="%2$s">%3$s (%4$s &times; %5$s)</a></span>',
								esc_url( wp_get_attachment_url() ),
								esc_attr__( 'Link to full-size image', 'binarybootstrap' ),
								__( 'Full resolution', 'binarybootstrap' ),
								$metadata['width'],
								$metadata['height']
							);

							edit_post_link( __( 'Edit', 'binarybootstrap' ), '<span class="edit-link">', '</span>' );
						?>
					</div><!-- .entry-meta -->
				</header><!-- .entry-header -->

				<div class="entry-content">
					<ul id="image-navigation" class="navigation image-navigation pager" role="navigation">
						<li class="nav-previous previous"><?php previous_image_link( false, __( '<span class="meta-nav">&larr;</span> Previous', 'binarybootstrap' ) ); ?></li>
						<li class="nav-next next"><?php next_image_link( false, __( 'Next <span class="meta-nav">&rarr;</span>', 'binarybootstrap' ) ); ?></li>
					</ul><!-- #image-navigation -->

					<div class="entry-attachment">
						<div class="attachment">
							<?php binarybootstrap_the_attached_image(); ?>

							<?php if ( has_excerpt() ) : ?>
							<div class="entry-caption">
								<?php the_excerpt(); ?>
							</div>
							<?php endif; ?>
						</div><!-- .attachment -->
					</div><!-- .entry-attachment -->

					<?php if ( ! empty( $post->post_content ) ) : ?>
					<div class="entry-description">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'binarybootstrap' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-description -->
					<?php endif; ?>

				</div><!-- .entry-content -->
			</article><!-- #post -->

			<?php comments_template(); ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>