<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
global $reactive;

get_header(); ?>
	<!-- Begin Content -->
	<div id="content" class="row">
		<div id="posts" class="large-9 columns" data-role="content" role="content">
			<?php while ( have_posts() ) : the_post(); ?>
				<hr />
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="row">
						<div class="columns large-3 small-3">
							<?php the_post_thumbnail(); ?>
						</div>
						<div class="columns large-9 small-9">
							<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'glc' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3><div class="academic">
							<?php
								$titles	= get_post_meta( get_the_ID(), 'scholar_title', true );
								foreach( $titles as $title ) :
									echo '<h5>' . $title . '<h5>';
								endforeach;
							?>
							<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'glc' ) ); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'glc' ), 'after' => '</div>' ) ); ?>
						</div>
					</div>
				</article><!-- #post-## -->
			<?php endwhile; // End the loop. Whew. ?>
		</div>
		
		<?php get_sidebar( 'person' ); ?>
	</div><!-- #content -->
<?php get_footer(); ?>
