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
<?php get_template_part( 'loop', 'hero' ); ?>
	<!-- Begin Content -->
	<div id="content" class="row">
		<div id="posts" class="large-9 columns" data-role="content" role="content">
			<?php
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'single' );
			?>
		</div>
		
		<?php get_sidebar(); ?>
	</div><!-- #content -->
<?php get_footer(); ?>
