<?php

get_header();

exa_container('preflight');
exa_container('menu-search-bar');
exa_container('header');

$container = $GLOBALS['container'] ?: new container('dirty-bird');

while ( have_posts() ) : the_post(); 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="<?php echo $container->classes(); ?>">

	<div class="wrapper">

	<ul class="topics">
		<li>
			<a href="<?php exa_section_permalink() ?>" class="section">
			<?php echo ucfirst(exa_get_section()); ?>
			</a>
		</li>
		<li><span><?php exa_topic(); ?></span></li>
	</ul>
	<div class="clearfix"></div>
	
	
	<div class="headline">
	<img class="dirty-bird" src="<?php bloginfo('stylesheet_directory') ?>/img/so-dirty.gif" />
	<img class="dirty-logo" src="<?php bloginfo('stylesheet_directory') ?>/img/dirty-bird.png" />
		<hr/>
		<div class="clearfix"></div>
			
		<h1 class="title"><?php the_title() ?></h1>
		
		<?php if( exa_has_subhead(get_the_ID()) ) : ?>
			<h2 class="subhead"><?php exa_subhead(); ?></h2>
		<?php endif; ?>
	
	</div>

	</div>

	<div class="wrapper">


		<main class="article-content">

			<?php if( !is_page()): // display byline and date on if it's not a page.?>

					<div class="meta">
						<?php /* Mug: */ ?>
						<div class="mug-box">
							<?php exa_mug(get_the_author_meta('ID'),'small-thumbnail') ?>
						</div>
						
						<?php /* Byline: */ ?>
						<span class="byline">
							by <a class="author-link" href="<?php exa_the_author_link() ?>" title="<?php echo exa_properize(get_the_author()); ?> Profile">
								   <?php the_author() ?>
							   </a>
						</span> &middot; <span class="meta-time"><?php the_time("M j, Y") ?></span>
					

						<a class="facebook-button" target="_blank" href="<?php echo exa_facebook_link(); ?>">Share</a>
						<a class="tweet-button" target="_blank" href="<?php echo exa_tweet_link(); ?>">Tweet</a>
					</div>
			<?php endif; ?>
			

			<?php
			if (exa_hero_style() == "standard" && exa_hero_media() != "none") :		
			?>				
			<div class="hero">
				<?php the_post_thumbnail('image-post-size'); ?>
				<?php exa_hero_caption(); ?>
	
				<div class="clearfix"></div>
				
			</div>
			
			<?php 
			endif; 
			?>

			<section class="article-text">

				<?php the_content(); ?>

			</section>

		</main>

		<aside class="sidebar">

			<div class="ad sidebar-thing">
				<?php 
				$DoubleClick->place_ad(
								'badgerherald.com-upper-sidekick',
								array(
									'phone'=>'',
									'desktop'=>'300x600,300x250'
									)
								);
				?>
			</div>
	
	
			<div class="ad sidebar-thing">
				<?php 
				$DoubleClick->place_ad(
								'badgerherald.com-lower-sidekick',
								array(
									'phone'=>'',
									'desktop'=>'300x600,300x250'
									)
								);
				?>
			</div>
		
		</aside>
	


		<div class="clearfix"></div>

		

	</div><!-- .wrapper -->

</div><!-- .container -->

</article><!-- #post-xx -->


<?php 
endwhile;
get_template_part('footer');