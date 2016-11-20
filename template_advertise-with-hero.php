<?php
/**
 * Template Name: Advertise with Hero
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header();

exa_container('menu-search-bar');
exa_container('advertise-hero');

?>

<?php /* The loop */ ?>

<?php while ( have_posts() ) : the_post(); ?>

<?php exa_container('content-two-column',array('layout' => exa_layout())); ?>
<?php endwhile; ?>

<?php get_footer(); ?>