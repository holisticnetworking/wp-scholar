<?php
/**
 * The Template for displaying archive lists of publications.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
get_header(); ?>
	<!-- Begin Content -->
	<div id="content" class="row">
		<div id="posts" class="large-9 columns" data-role="content" role="content">
			<?php
			if( have_posts() ) : while( have_posts() ) : the_post();
				echo '<h1>Shit yeah, dawg.</h1>';
			endwhile; endif;
			?>
		</div>
		
		<?php get_sidebar(); ?>
	</div><!-- #content -->
<?php get_footer(); ?>
