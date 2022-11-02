<section class="philosophy">
	<div class="container">
		<?php am_the_field('main_title', '<h2 class="section-title">', '</h2>'); ?>
		<div class="philosophy_tabs tab">
			<div class="tab-panel">
				<?php am_the_sub_field('subtitle1', '<h5 class="tab-title active" data-toggle="tab" data-target="nomad">', '</h5>'); ?>
				<?php am_the_sub_field('subtitle2', '<h5 class="tab-title" data-toggle="tab" data-target="stories">', '</h5>'); ?>
				<?php am_the_sub_field('subtitle3', '<h5 class="tab-title" data-toggle="tab" data-target="new-collection">', '</h5>'); ?>
			</div>
			<div class="tab-content">

				<?php if ($cards = get_sub_field('philosophy_card')) : ?>
					<div class="tab-item active" data-name="nomad">

						<?php foreach ($cards as $key => $card) : ?>
							<div class="philosophy-card card">

								<?php if ($pc_image = $card['pc_image']) : ?>
									<div class="card-image">
										<img src="<?php echo $pc_image; ?>" alt="">
									</div>
								<?php endif; ?>

								<div class="card-content">

									<?php if ($pc_text = $card['pc_text']) : ?>
										<?php echo ($key == 0) ? '<h4 class="card-title">' : '<p class="card-description">'; ?>
										<?php echo $pc_text; ?>
										<?php echo ($key == 0) ? '</h4>' : '</p>'; ?>
									<?php endif; ?>

									<?php if ($pc_url = $card['more_button_link']) : ?>
										<a href="<?php echo $pc_url; ?>" class="card-link">
											<?php _e('MORE'); ?>
										</a>
									<?php endif; ?>

								</div>
							</div>
						<?php endforeach; ?>

					</div>
				<?php endif; ?>

				<?php if ($cards = get_sub_field('philosophy_card_2')) : ?>
					<div class="tab-item" data-name="stories">

						<?php foreach ($cards as $key => $card) : ?>
							<div class="philosophy-card card">

								<?php if ($pc_image = $card['pc_image']) : ?>
									<div class="card-image">
										<img src="<?php echo $pc_image; ?>" alt="">
									</div>
								<?php endif; ?>

								<div class="card-content">

									<?php if ($pc_text = $card['pc_text']) : ?>
										<?php echo ($key == 0) ? '<h4 class="card-title">' : '<p class="card-description">'; ?>
										<?php echo $pc_text; ?>
										<?php echo ($key == 0) ? '</h4>' : '</p>'; ?>
									<?php endif; ?>

									<?php if ($pc_url = $card['more_button_link']) : ?>
										<a href="<?php echo $pc_url; ?>" class="card-link">
											<?php _e('MORE'); ?>
										</a>
									<?php endif; ?>

								</div>
							</div>
						<?php endforeach; ?>

					</div>
				<?php endif; ?>

				<?php if ($cards = get_sub_field('philosophy_card_3')) : ?>
					<div class="tab-item" data-name="stories">

						<?php foreach ($cards as $key => $card) : ?>
							<div class="philosophy-card card">

								<?php if ($pc_image = $card['pc_image']) : ?>
									<div class="card-image">
										<img src="<?php echo $pc_image; ?>" alt="">
									</div>
								<?php endif; ?>

								<div class="card-content">

									<?php if ($pc_text = $card['pc_text']) : ?>
										<?php echo ($key == 0) ? '<h4 class="card-title">' : '<p class="card-description">'; ?>
										<?php echo $pc_text; ?>
										<?php echo ($key == 0) ? '</h4>' : '</p>'; ?>
									<?php endif; ?>

									<?php if ($pc_url = $card['more_button_link']) : ?>
										<a href="<?php echo $pc_url; ?>" class="card-link">
											<?php _e('MORE'); ?>
										</a>
									<?php endif; ?>

								</div>
							</div>
						<?php endforeach; ?>

					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
</section>