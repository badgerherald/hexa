<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

// Set up ads for current page.
global $DoubleClick;


?>

<?php /* The loop */ ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part( 'content', 'shoutouts' ); ?>

    <div class="clearfix"></div>

    <div class="clearfix"></div>

<?php endwhile; ?>

<?php get_footer(); ?>