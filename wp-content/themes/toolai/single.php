<?php get_header(); ?>

	<div class="container">

		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>

				<?php if ( has_post_thumbnail() ) : ?>

					<div class="page-site-header">
						<?php am_the_post_img( 'large' ); ?>
					</div>

				<?php endif; ?>
				
				<?php get_sidebar('post'); ?>

				<div id="content">
					<div class="entry">

						<?php get_template_part( 'template-parts/content', 'post' ); ?>

					</div>

					<?php if ( comments_open() || get_comments_number() ) : ?>
						<div class="block">
							<?php comments_template(); ?>
						</div>
					<?php endif; ?>

				</div>

			<?php endwhile; ?>

		<?php endif; ?>

	</div>

<?php get_footer(); ?>