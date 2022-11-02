<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
	return;
}
?>
<div <?php wc_product_class('card', $product); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action('woocommerce_before_shop_loop_item'); ?>

	<div class="card-body">

		<a href="<?php the_permalink(); ?>">
			<?php $prod_id = get_the_ID(); ?>
			<?php if ($image_url = wp_get_attachment_image_url(get_post_thumbnail_id($prod_id), 'full')) : ?>
				<img src="<?php echo $image_url; ?>" alt="">
			<?php endif; ?>
		</a>
		<div class="add-to-favorite">
			<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
		</div>

	</div>
	<div class="card-footer">
		<span class="tag"><?php the_excerpt(); ?></span>
		<strong class="look-name"><?php the_title(); ?></strong>
		<?php if ($price_html = $product->get_price_html()) : ?>
			<span class="price"><?php echo $price_html; ?></span>
		<?php endif; ?>
		<?php

		/**
		 * Hook: woocommerce_after_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action('woocommerce_after_shop_loop_item');
		?>
	</div>
</div>