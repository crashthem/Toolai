<?php if ($slider_images = get_sub_field('slider_images')) : ?>
	<section class="promo accordion">
		<?php foreach ($slider_images as $key => $slider_image) : ?>
			<div class="slide <?php if ($key == 1) echo 'active'; ?>">
			<img src="<?php echo $slider_image; ?>" alt="">
			</div>
		<?php endforeach; ?>
	</section>
<?php endif; ?>