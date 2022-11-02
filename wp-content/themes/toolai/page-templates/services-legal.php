<?php
/*
Template Name: Services&Legal
Template Post Type: page, post
*/

get_header(); ?>

<?php if ($featured_image_url = get_the_post_thumbnail_url()) : ?>
	<section class="promo info-promo">
		<img src="<?php echo $featured_image_url; ?>" alt="baner">
	</section>
<?php endif; ?>
<section class="info">
	<div class="container">
		<?php function get_page_by_template($template = '')
		{
			$args = array(
				'meta_key' => '_wp_page_template',
				'meta_value' => $template,
				'hierarchical' => 0
			);
			return get_pages($args);
		}

		if ($pages = get_page_by_template('page-templates/services-legal.php')) : ?>
			<div class="info-list">
				<?php foreach ($pages as $page) {
					echo '<a href="' . $page->guid . '" class="info-list__link">' . $page->post_title . '</a>';
				} ?>
			</div>
		<?php endif; ?>
		<div class="info-content">
			<div class="info-row">
				<div class="info-col">
					<p class="info-text">
						<?php the_content(); ?>
					</p>
					<?php if ($items = get_field('sl_items')) : ?>
						<?php foreach ($items as $key => $item) : ?>

							<div class="s-accordion info-accordion">
								<h5 class="accordion-title"> <span><?php echo $key + 1 . '. ' . $item['sl_title']; ?></span>
									<i class="fas fa-chevron-down accordion-arrow"></i>
								</h5>
								<div class="accordion-body">
									<p class="info-text">
										<?php echo $item['sl_text']; ?>
									</p>
								</div>
							</div>
							<hr>

						<?php endforeach; ?>
					<?php endif; ?>

				</div>
				<aside class="info-col">
					<?php am_the_field('sidebar_title', '<h4 class="info-title">', '</h4>'); ?>
					<?php am_the_field('sidebar_text'); ?>
				</aside>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>