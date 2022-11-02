<section class="new-look">
	<div class="container">

		<?php am_the_sub_field('nl_title', '<h2 class="section-title">', '</h2>'); ?>
		<?php am_the_sub_field('nl_subtitle', '<h3 class="sub-title">', '</h3>'); ?>

		<?php if ($prods = get_sub_field('nl_products')) : ?>
			<div class="list cards-slider">
				<?php /* print_r($prods);*/ ?>
				<?php foreach ($prods as $prod) : $id = $prod->ID; ?>
					<di class="card">
						<div class="card-body">
							<img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id($id), 'full'); ?>" alt="">
							<div class="add-to-favorite">
								<a href="<?php echo get_permalink() . '?add_to_wishlist=' . $id . '&_wpnonce=e86f3761aa'; ?>">
									<i class="fas fa-heart"></i>
								</a>
							</div>
						</div>
						<div class="card-footer">
							<span class="tag"><?php $prod->post_excerpt; ?></span>
							<a href="<?php echo $prod->guid; ?>" class="look-name"><?php echo $prod->post_title; ?></a>
							<span class="price"><?php $product = wc_get_product( $id ); echo $product->get_price_html(); ?></span>
						</div>
					</di>
				<?php endforeach; ?>

			</div>
		<?php endif; ?>
	</div>
</section>