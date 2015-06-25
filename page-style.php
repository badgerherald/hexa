<?php


get_header('minimal');
?>

<div class="block style-block" style="margin-top:200px;margin-bottom:200px">
	<div class="wrapper">
		<h1>Block Headlines</h1>
		<p class="description">Block Headlines are often used on top of featured images.</p>

		<code>h1.block-headline:</code>
		<div style="max-width:440px;">
			<h1 class="block-headline"><a href="#">The 12 stages of getting Badgers football season tickets</a></h1>
		</div>

		<code>h1.block-headline:</code>
		<div style="max-width:300px;">
			<h2 class="block-headline"><a href="#">The 12 stages of getting Badgers football season tickets</a></h2>
		</div>

		<h4>Notes:</h4>

		<p>The way that these are styled can make using them a little tricky to use.<p>

		<p>Because a box-shadow is used to synthesize the left and right padding,
		any h1 or h2 with this style has to be enclosed in a .block-headline-container to apply
		margins.</p>

		<h4>Example:</h4>
		<xmp>
			<div class="block-headline-container">
				<h1 class="block-headline"><a href="#">This is a headline</a></h1>
			</div>
		</xmp>

	</div>
</div>

<?php get_footer(); ?>
