<?php
/**
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="help-block entry-meta">
			<?php binarybootstrap_entry_meta(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'binarybootstrap' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="help-block entry-meta">

		<?php edit_post_link( __( 'Edit', 'binarybootstrap' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
