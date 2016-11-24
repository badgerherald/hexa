<?php

$container = $GLOBALS['container'] ?: new container('advertise-hero');
$container->default_args(
	array('navonly'=>false)
);

$navonly = $container->args['navonly'];
?>

<div class="<?php echo $container->classes($navonly ? 'navonly' : ''); ?>">
	<div class="wrapper">
		<h1>Advertise with <span class="the">the</span> Badger Herald</h1>
		<?php if(!$navonly) : ?>
		<p><span>Founded in 1969, The Badger Herald is a college media platform built by students</span></p>
		<p><span>as an alternative voice for students at the University of Wisconsin-Madison.</span></p>
		<p class="seller"><span>We can connect your brand on campus</span></p>
		<?php endif; ?>
	</div>
</div>

<?php

$container = new container('advertise-nav');

?>

<div class="<?php echo $container->classes(); ?>">
	<div class="wrapper">
	<?php wp_nav_menu(array( 'theme_location' => 'ad-nav', )); ?>
	<div class="clearfix"></div>
	</div>
</div>




