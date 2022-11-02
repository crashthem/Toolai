<div id="yith-wcwl-popup-message" style="margin-left: -119.953px; display: none;">
    <div id="yith-wcwl-message">Product added!</div>
</div>
<?php if ($hs_images = get_sub_field('hs_images')) : ?>
    <section class="promo">
        <div class="slider">
            <?php foreach ($hs_images as $key => $img_url) : ?>
                <div class="slide <?php if ($key == 1) echo 'active'; ?>"><img src="<?php echo $img_url; ?>" alt=""></div>
            <?php endforeach; ?>
        </div>
        <div class="promo-content">
            <?php am_the_sub_field('hs_title', '<h2 class="promo-title">', '</h2>'); ?>
        </div>
    </section>
<?php endif; ?>

<?php if (is_page('Special Offer')) : ?>
    <?php
    $prod_cat = isset($_GET['prod_cat']) ? $_GET['prod_cat'] : 'special_offer';
    $prods = wc_get_products([
        'category'       => $prod_cat,
    ]);
    // print_r($prods);
    if ($prods) :
    ?>
        <section class="offers-list">
            <div class="container">
                <?php the_title('<h2 class="section-title">', 's</h2>'); ?>
                <div class="products-tools">
                    <strong class="category">Dresses</strong>
                    <?php if ($term_children = get_terms([
                        'child_of'   => 36,
                        'hide_empty' => true,
                        'taxonomy'   => 'product_cat',
                        'orderby'    => 'none'
                    ])) : ?>
                        <div class="subs-list">

                            <?php foreach ($term_children as $child) {
                                echo '<a href="' . get_permalink() . '?prod_cat=' . $child->slug . '" class="subs-list__item">' . $child->name . '</a>';
                            } ?>

                        </div>
                    <?php endif; ?>
                    <!-- <div class="tools-item filter">
                        <h5 class="title" data-toggle="modal" data-modal="filter-modal">Filters</h5>
                        <div class="modal filter-modal" data-role="modal" data-name="filter-modal">
                            <div class="container close">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <span>Filters</span>
                                        <button class="modal-close">
                                            <span class="top"></span>
                                            <span class="sub"></span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="s-accordion">
                                            <h5 class="accordion-title"> <span>Size</span> <i class="fas fa-chevron-down accordion-arrow" aria-hidden="true"></i>
                                            </h5>
                                            <div class="accordion-body">
                                                <div class="size-picker">
                                                    <input type="checkbox" name="" id="onesize">
                                                    <label for="onesize" class="onesize">One size</label>
                                                    <input type="checkbox" name="" id="xxxs">
                                                    <label for="xxxs">XXXS</label>
                                                    <input type="checkbox" name="" id="xxs">
                                                    <label for="xxs">XXS</label>
                                                    <input type="checkbox" name="" id="xs">
                                                    <label for="xs">XS</label>
                                                    <input type="checkbox" name="" id="s">
                                                    <label for="s">S</label>
                                                    <input type="checkbox" name="" id="m">
                                                    <label for="m">M</label>
                                                    <input type="checkbox" name="" id="l">
                                                    <label for="l">L</label>
                                                    <input type="checkbox" name="" id="xl">
                                                    <label for="xl">XL</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="s-accordion">
                                            <h5 class="accordion-title"> <span>Color </span><i class="fas fa-chevron-down accordion-arrow" aria-hidden="true"></i></h5>
                                            <div class="accordion-body">
                                                <div class="color-picker">
                                                    <input type="checkbox" name="black" id="black">
                                                    <label for="black">
                                                        <span class="thumb"><img src="./images/colors/thumb/black.png"
                                                                alt=""></span>
                                                        Black
                                                    </label>
                                                    <input type="checkbox" name="" id="blue">
                                                    <label for="blue">
                                                        <span class="thumb"><img src="./images/colors/thumb/blue.png"
                                                                alt=""></span>
                                                        Blue
                                                    </label>
                                                    <input type="checkbox" name="" id="neutrals">
                                                    <label for="neutrals">
                                                        <span class="thumb"><img
                                                                src="./images/colors/thumb/neutrals.png" alt=""></span>
                                                        Neutrals
                                                    </label>
                                                    <input type="checkbox" name="" id="scin">
                                                    <label for="scin">
                                                        <span class="thumb"><img
                                                                src="./images/colors/thumb/skin-tone.png" alt=""></span>
                                                        Scin tones
                                                    </label>
                                                    <input type="checkbox" name="" id="white">
                                                    <label for="white">
                                                        <span class="thumb"><img src="./images/colors/thumb/white.png"
                                                                alt=""></span>
                                                        White
                                                    </label>
                                                    <input type="checkbox" name="" id="yellow">
                                                    <label for="yellow">
                                                        <span class="thumb"><img src="./images/colors/thumb/yellow.png"
                                                                alt=""></span>
                                                        Yellow
                                                    </label>
                                                    <button class="see-more">See more</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="s-accordion">
                                            <h5 class="accordion-title"> <span>Price</span><i class="fas fa-chevron-down accordion-arrow" aria-hidden="true"></i></h5>
                                            <div class="accordion-body">
                                                <div class="price-filter">
                                                    <div class="row">
                                                        <input type="number" id="number-from" value="0" min="0"
                                                            max="50000">
                                                        <input type="number" id="number-to" value="50000" min="0"
                                                            max="50000">
                                                    </div>
                                                    <div class="price-range">
                                                        <input type="range" name="" id="range-from" value="0" min="0"
                                                            max="50000" step="50">
                                                        <span class="range-gap"></span>
                                                        <input type="range" name="" id="range-to" value="50000" min="0"
                                                            max="50000" step="50">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="s-accordion">
                                            <h5 class="accordion-title"> <span>Sort by</span><i class="fas fa-chevron-down accordion-arrow" aria-hidden="true"></i></h5>
                                            <div class="accordion-body">
                                                <div class="sort-options">
                                                    <input type="radio" name="sort" id="newest">
                                                    <label for="newest">Newest first</label>
                                                    <input type="radio" name="sort" id="price-htl">
                                                    <label for="price-htl">Price high to low</label>
                                                    <input type="radio" name="sort" id="price-lth">
                                                    <label for="price-lth">Price low to high</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="modal-footer">
                                        <button>132 results</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>

                <div class="products-body">

                    <?php foreach ($prods as $prod) : $id = $prod->get_id(); ?>

                        <div class="card">
                            <div class="card-body">
                                <a href="<?php echo get_permalink($id); ?>"><img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id($id), 'full'); ?>" alt=""></a>
                                <div class="add-to-favorite">
                                    <a href="<?php echo get_permalink() . '?add_to_wishlist=' . $id . '&_wpnonce=e86f3761aa'; ?>">
                                        <i class="yith-wcwl-icon fa fa-heart-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="card-sizes">
                                    <h4 class="card-sizes__title">
                                        add size
                                    </h4>
                                    <div class="card-sizes__list">
                                        <span class="card-sizes__item" data-value="s">s</span>
                                        <span class="card-sizes__item" data-value="m">m</span>
                                        <span class="card-sizes__item" data-value="l">l</span>
                                        <span class="card-sizes__item" data-value="xl">xl</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <span class="tag"><?php $prod->get_short_description(); ?></span>
                                <strong class="look-name"><?php echo $prod->get_name(); ?></strong>
                                <div class="card-prices">
                                    <?php
                                    $regular = $prod->get_regular_price();
                                    $sale    = $prod->get_sale_price();
                                    ?>
                                    <span class="card__common-price">$ <?php echo $regular; ?></span>
                                    <span class="card__discount">-<?php echo round(100 - $sale * 100 / $regular); ?>%</span>
                                    <span class="card__discount-price">$ <?php echo $sale; ?></span>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>

            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>