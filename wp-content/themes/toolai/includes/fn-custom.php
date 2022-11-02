<?php
add_image_size('mainslider', 1130, 729, false);

add_action('woocommerce_before_main_content', function () {
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
});

add_action('woocommerce_before_shop_loop', function () {
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
});

add_action('woocommerce_after_shop_loop_item', function () {
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
});
