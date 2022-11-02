<?php if ($p_cards = get_sub_field('p_cards')) : ?>
	<?php $class = (count($p_cards) % 2 == 0) ? '' : 'b2b-partners'; ?>
	<section class="b2b <?php echo $class; ?>">
		<div class="container">

			<?php am_the_sub_field('p_main_title', '<h2 class="section-title">', '</h2>'); ?>

			<div class="<?php if ($class == 'b2b-partners') echo 'full-'; ?>cards">

				<?php foreach ($p_cards as $card) : ?>
					<div class="card">
						<div class="card-body">

							<?php if ($p_image_url = $card['p_image']) : ?>
								<img src="<?php echo $p_image_url; ?>" alt="">
							<?php endif; ?>

							<?php if ($p_title = $card['p_title']) : ?>
								<div class="card-content">
									<h4 class="card-title"><?php echo $p_title; ?></h4>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>

			</div>
		</div>
	</section>
<?php endif; ?>