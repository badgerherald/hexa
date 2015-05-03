<?php


get_header('minimal');
?>
<div class="block full-width-cover-image full-width-aspect-3by1 pos-bottom" style="background-image:url(<?php echo get_template_directory_uri(); ?>/img/about/protest.jpg)">
	<div class="about-home-header">	
		<div class="header-box">
			<h1>The Badger Herald</h1>
			<h2>A thorn in their side since 1969</h2>
		</div>
	</div> <!-- #wrapper -->
</div>

	<div class="about-nav wrapper">
		<ul class="fixed-sidebar-container">
			<li><a href="<?php bloginfo("url") ?>/about/">About</a></li>
			<li class="active"><a href="<?php bloginfo("url") ?>/about/staff/">Staff</a></li>
			<li><a href="<?php bloginfo("url") ?>/about/get-involved/">Get Involved</a></li>
			<li><a href="<?php bloginfo("url") ?>/about/history/">History</a></li>
			<li style="display: none;"><a href="<?php bloginfo("url") ?>/about/colophon/">Colophon</a></li>
			<li><a href="<?php bloginfo("url") ?>/about/copyright/">Copyright</a></li>
		</ul>
	</div>

	<div id="content" class="site-content article-content wrapper" role="main">

		<?php /* The loop */ ?>


		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
				<div class="entry-content">

				<?php the_content(); ?>

				</div><!-- .entry-content -->

			</article><!-- #post -->
		<?php endwhile; ?>

	</div><!-- #content -->

<?php get_footer(); ?>
