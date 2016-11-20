<?php

$container = $GLOBALS['container'] ?: new container('advertise-hero');
$container->default_args(
	array('navonly'=>false)
);

if(!$container->args['navonly']):
?>

<div class="<?php echo $container->classes(); ?>">
	<div class="wrapper">
		<h1>Advertise with <span class="the">the</span> Badger Herald</h1>
		<p><span>Founded in 1969, The Badger Herald is a college media platform built by students</span></p>
		<p><span>as an alternative voice for students at the University of Wisconsin-Madison.</span></p>
		<p class="seller"><span>We can connect your brand to the campus market.</span></p>
	</div>
</div>

<?php

else:

?>
<div class="<?php echo $container->classes(); ?> navonly">
	<div class="wrapper">
		<h1>Advertise with <span class="the">the</span> Badger Herald</h1>
	</div>
</div>

<?
endif;

$container = new container('advertise-nav');

?>

<div class="<?php echo $container->classes(); ?>">
	<div class="wrapper">

	<?php 

	$args = array( 'theme_location' => 'ad-nav', );
	wp_nav_menu($args); 

	?>

	<div class="clearfix"></div>
	</div>

</div>

