<?php if ($fs_images = get_sub_field('fs_images')) : ?>
    <?php $class = get_sub_field('slider_type') ? 'explore' : 'nomad'; ?>
    <section class="<?php echo $class; ?>">
        <div class="container">
            <?php am_the_sub_field('fs_title', '<h2 class="section-title">', '</h2>'); ?>
            <div class="<?php echo $class; ?>-slider cards-slider-4">
                <?php foreach ($fs_images as $image_url) : ?>
                    <div class="slide">
                        <img src="<?php echo $image_url; ?>" alt="">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>