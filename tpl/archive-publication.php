<?php
/**
 * The Template for displaying archive lists of publications.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
global $page;
get_header(); ?>
    <!-- Begin Content -->
    <style type="text/css" media="screen">
        p.citation {
            margin-left: 30px;
            text-indent: -30px;
        }
    </style>
    <div id="content" class="row">
        <div id="posts" class="large-9 columns" data-role="content" role="content">
            <?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    $author         = get_post_meta($post->ID, 'scholar_author', true);
                    $article    = get_post_meta($post->ID, 'scholar_article', true);
                    $title      = get_post_meta($post->ID, 'scholar_title', true);
                    $translator     = get_post_meta($post->ID, 'scholar_translator', true);
                    $edition    = get_post_meta($post->ID, 'scholar_edition', true);
                    $editor         = get_post_meta($post->ID, 'scholar_editor', true);
                    $pub_city   = get_post_meta($post->ID, 'scholar_pub_city', true);
                    $publisher  = get_post_meta($post->ID, 'scholar_publisher', true);
                    $pub_year   = get_post_meta($post->ID, 'scholar_pub_year', true);
                    $medium         = get_post_meta($post->ID, 'scholar_medium', true);
                    $url        = get_post_meta($post->ID, 'scholar_url', true);
                
                    $publication    = sprintf(
                        '%s, (%s). %s. <em>%s</em>. %s: %s. %s. &lt;<a href="%s">%s</a>&gt;',
                        $author,
                        $pub_year,
                        $article,
                        $title,
                        $pub_city,
                        $publisher,
                        $medium,
                        $url,
                        $url
                    );
                
                                    echo '<p class="citation">' . $publication . '</p>';
                endwhile;
            endif;
            ?>
            <div class="nav-previous alignleft"><?php next_posts_link('Older posts'); ?></div>
            <div class="nav-next alignright"><?php previous_posts_link('Newer posts'); ?></div>
        </div>
        
        <?php get_sidebar(); ?>
    </div><!-- #content -->
<?php get_footer(); ?>
