<?php
/**
 * The Template for displaying single persons.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

$titles		= get_post_meta( $post->ID, 'scholar_title', true );
$display	= get_post_meta( $post->ID, 'scholar_person_display', true );
$address	= get_post_meta( $post->ID, 'scholar_address', true );

get_header(); ?>
	<!-- Begin Content -->
	<div id="content" class="row">
		<div id="posts" class="large-9 columns" data-role="content" role="content">
		<?php while ( have_posts() ) : the_post(); 
			if( $display['single_page'] > 0 ) :
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/NewsArticle">
				<header class="article-header">
					<h1 class="entry-title" itemprop="name"><a itemprop="url" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'glc' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
					<?php
					foreach( $titles as $title ) :
						echo '<h3 class="person-title">' . $title . '</h3>';
					endforeach;
					?>
				</header>
				<section class="entry-content" itemprop="description">
					<?php ScholarPerson::the_post_thumbnail_caption(); ?>
					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'glc' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'glc' ), 'after' => '</div>' ) ); ?>
					<?php edit_post_link( 'Edit This Content' ); ?> 
				</section><!-- .entry-content -->
			</article><!-- #post-## -->
		<?php 
			endif;
			endwhile; // End the loop. Whew. ?>
		</div>
		
		<?php get_sidebar(); ?>
	</div><!-- #content -->
<?php get_footer(); ?>
