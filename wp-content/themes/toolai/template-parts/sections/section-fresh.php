<section class="fresh">
	<div class="container">
		<h2 class="section-title hidden">fresh looks</h2>
		<div class="list">

			<?php if ($looks = get_sub_field('looks')) : ?>

				<?php foreach ($looks as $key => $look) : ?>
					<a href="<?php echo $look['more_button_link']; ?>" class="fresh-item fresh-item-<?php echo $key + 1; ?>">
						<img src="<?php echo $look['l_image']; ?>" alt="">
						<div class="content">
							<div class="mask"></div>
							<h3 class="title"><?php echo $look['l_title']; ?></h3>
							<span class="more"><?php _e('more'); ?></span>
						</div>
					</a>
				<?php endforeach; ?>

			<?php endif; ?>

		</div>
	</div>
</section>

<section class="editorial">
	<div class="container">
		<h2 class="section-title hidden">editorial</h2>
		<div class="list">
			<div class="card">
				<div class="card-body">
					<img src="https://atlasdemo.space/toolai-wp/wp-content/uploads/2022/06/Toolai-BrandPresent-2.jpg" alt="">
					<div class="content">
						<h3 class="title">STORIES</h3>
						<a href="https://atlasdemo.space/toolai-wp/our-stories/">Read more</a>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<img src="https://atlasdemo.space/toolai-wp/wp-content/uploads/2022/06/001.1.jpg" alt="">
					<div class="content">
						<h3 class="title">NEW <br> COLLECTION</h3>
						<a href="https://atlasdemo.space/toolai-wp/new-collection/">Read more</a>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<img src="https://atlasdemo.space/toolai-wp/wp-content/uploads/2022/06/BRAND-PHILOSOPHY-4.jpg" alt="">
					<div class="content">
						<h3 class="title">PHYLOSOPHY</h3>
						<a href="https://atlasdemo.space/toolai-wp/philosophy/">Read more</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>