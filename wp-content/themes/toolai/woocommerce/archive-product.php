<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

?>
<?php if ($store_slider = get_field('store_slider', 6)) : ?>
	<section class="promo">

		<div class="slider">
			<?php foreach ($store_slider as $key => $slider_url) : ?>
				<div class="slide <?php if ($key == 1) echo 'active'; ?>"><img src="<?php echo $slider_url; ?>" alt=""></div>
			<?php endforeach; ?>
		</div>

		<div class="promo-content">
			<?php am_the_field('store_title', '<h2 class="promo-title">', '</h2>', 6); ?>
		</div>
	</section>
<?php endif; ?>
<section class="products">
	<div class="container">
		<?php $woo_title = woocommerce_page_title(false); ?>
		<?php $woo_title = (woocommerce_page_title(false) == 'Store') ? __('All Clothing ') : $woo_title; ?>
		<h2 class="section-title"><?php echo $woo_title . ' ' . date('Y'); ?></h2>

		<?php
		$termID = get_queried_object()->term_id;
		$taxonomyName = "product_cat";
		$termchildren = get_term_children($termID, $taxonomyName);
		if ($termchildren) : ?>

			<div class="products-tools">
				<strong class="category"><?php echo $woo_title; ?></strong>
				<div class="subs-list">
					<?php
					foreach ($termchildren as $child) {
						$term = get_term_by('id', $child, $taxonomyName);
						echo '<a href="' . get_term_link($term->term_id, $term->taxonomy) . '" class="subs-list__item">' . $term->name . '</a>';
					}
					?>
				</div>
			</div>

		<?php endif; ?>

		<?php
		if (woocommerce_product_loop()) {

			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked woocommerce_output_all_notices - 10
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action('woocommerce_before_shop_loop');

			woocommerce_product_loop_start();

			if (wc_get_loop_prop('total')) {
				while (have_posts()) {
					the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action('woocommerce_shop_loop');

					wc_get_template_part('content', 'product');
				}
			}

			woocommerce_product_loop_end(); ?>
	</div>
<?php
			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action('woocommerce_after_shop_loop');
		} else {
			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action('woocommerce_no_products_found');
		}

		/**
		 * Hook: woocommerce_after_main_content.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
?>
</section>
<?php
get_footer('shop');
