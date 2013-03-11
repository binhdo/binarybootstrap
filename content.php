<?php
/**
 * @package Binary Bootstrap
 * @since Binary Bootstrap 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="page-header entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'binarybootstrap' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="help-block entry-meta">
			<?php binarybootstrap_entry_meta(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Read more <span class="meta-nav">&rarr;</span>', 'binarybootstrap' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'binarybootstrap' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="help-block entry-meta">
		<?php if ( comments_open() ) : ?>
			<i class="glyphicon glyphicon-comment"></i>
			<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'binarybootstrap' ) . '</span>', __( 'One comment so far', 'binarybootstrap' ), __( 'View all % comments', 'binarybootstrap' ) ); ?>
		<?php endif; // comments_open() ?>
	
		<?php edit_post_link( __( 'Edit', 'binarybootstrap' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
