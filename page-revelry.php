<?php
get_template_part('revelry/header', 'revelry');
?>

<?php /* The loop  ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php get_template_part( 'content', get_post_format() ); ?>

    <div class="clearfix"></div>

    <?php // get_template_part('inc/block','cover'); ?>

    <div class="clearfix"></div>

<?php endwhile; 

*/ ?>
<div id="revelry">
    <div id="revelry-landing" class="block full-viewport-block">
        <div class="landing-block">
            <div class="landing-content wrapper">
                <a href="<?php echo home_url();?>"><img class="herald-logo" src="<?php echo get_template_directory_uri(); ?>/img/logo/header-7-white.png"></a>
                <img class="revelry-logo" src="<?php echo get_stylesheet_directory_uri(); ?>/img/revelry-logo.png">
            </div>
        </div>
        <a href="#nav" class="down-caret"></a>
    </div>
    <div class="header-nav">
        <nav id="nav">
            <ul class="wrapper">
                <li data-section="about"><a href="#about">About<span class="down-caret"></span></a></li>
                <li data-section="news"><a href="#news">News<span class="down-caret"></span></a></li>
                <li data-section="sitemap"><a href="#sitemap">Site Map<span class="down-caret"></span></a></li>
                <li data-section="photos"><a href="#photos">Photos<span class="down-caret"></span></a></li>
                <li data-section="tickets"><a href="#tickets">Tickets<span class="down-caret"></span></a></li>
                <li class="back-to-top"><a href="#about">Top<span class="up-caret"></span></a></li>
            </ul>
        </nav>
    </div>
    <div id="about" class="content-block block">
        <div class="wrapper">
        <h1>About</h1>
            <p>
                Curabitur ullamcorper ultricies nisi. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Donec posuere vulputate arcu. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus.
            </p>
            <p>
                Phasellus blandit leo ut odio. Nunc egestas, augue at pellentesque laoreet, felis eros vehicula leo, at malesuada velit leo quis pede. Donec mollis hendrerit risus. Sed a libero.
            </p>
            <p>
                Praesent ac massa at ligula laoreet iaculis. In consectetuer turpis ut velit. Donec vitae sapien ut libero venenatis faucibus. Pellentesque commodo eros a enim.
            </p>
            <p>
                Nunc egestas, augue at pellentesque laoreet, felis eros vehicula leo, at malesuada velit leo quis pede. Ut varius tincidunt libero. Mauris sollicitudin fermentum libero. Nulla facilisi.
            </p>
        </div>
    </div>
    <div id="news" class="content-block block">
        <div class="wrapper">
            <h1>News</h1>
        <?php
            $args = array (
                'post_type' => 'post',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'topic',
                        'field' => 'slug',
                        'terms' => 'revelry-2015'
                    )
                )
            );

            $query = new WP_Query($args);
            while ($query->have_posts()) : $query->the_post();
                ?>

                <article class="article-card">

                    <?php 
                    $cats = get_the_category();
                    $sponsored = false;
                    $topics = wp_get_post_terms(get_the_ID(),"topic");
                    foreach ($topics as $topic)
                    {
                        if ($topic->slug == "sponsored")
                        {
                            $sponsored = true;
                        }
                    }
                    ?>
                    <?php if( has_post_thumbnail( $post_id ) ): ?>
                    <a href="<?php the_permalink(); ?>">
                    <div class="thumbnail">
                        <?php the_post_thumbnail(); ?>
                    </div>
                    </a>
                    <?php endif; ?>
                    

                    <a class="topic-label" href="<?php echo get_category_link( $cats[0]->cat_ID  ) ?>"><?php echo exa_topic() ?></a>

                    <?php
                    if ($sponsored)
                    {
                        ?>
                        <span class="sponsored">Sponsored</span>
                        <?php
                    }
                    ?>
                    <a href="<?php the_permalink(); ?>">
                    <h2><?php the_title(); ?></h2>
                    <p class="excerpt"><?php echo get_the_excerpt(); ?></p>
                    </a>

                </article>

                <?php
            endwhile;
            wp_reset_postdata();
        ?>
        </div>
    </div>
    <div id="sitemap" class="content-block block">
        <div class="wrapper">
            <h1>Site Map</h1>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/SponsorMap-General.jpg">
        </div>
    </div>
    <div id="photos" class="content-block block">
        <div class="wrapper">
        <h1>Photos</h1>
        </div>
    </div>
    <div id="tickets" class="content-block block">
        <div class="wrapper">
            <h1>Tickets</h1>
            <ul>
                <li><span class="date">March 16 - 22</span> <span>$5 for students, $25 for non-students</span></li>
                <li><span class="date">March 22 - May 1</span> <span>$10 for students, $30 for non-students</span></li>
                <li><span class="date">May 2</span> <span>$15 for students, $35 for non-students</span></li>
            </ul>
        </div>
    </div>
</div>


<?php get_footer(); ?>