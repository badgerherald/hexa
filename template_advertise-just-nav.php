<?php
/**
 * Template Name: Advertise (Nav only)
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header();

exa_container('nameplate');
exa_container('advertise-hero',array('navonly'=>true));

?>

<?php /* The loop */ ?>

<?php while ( have_posts() ) : the_post(); ?>

<?php exa_container('content-two-column',array('layout' => exa_layout())); ?>
<?php endwhile; ?>

<?php get_footer(); ?>



