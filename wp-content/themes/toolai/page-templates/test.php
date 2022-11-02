<?php
/*
Template Name: Test page
Template Post Type: page
*/

get_header(); ?>
	
	<style>
		.dev-links li{
			margin-bottom: 1em;
		}
		.dev-links li:nth-child(even) {
		    background: #efefef;
		}
	</style>

	<section class="about-section">
		<div class="container">
			<div class="content-holder">
				
				<?php if ($test_links = get_field('test_links')) : ?>

					<h2>Dev links</h2>
					<ul class="dev-links">
						
						<?php foreach ($test_links as $test_link) : ?>

							<li>
								HTML - <a href="<?php echo $test_link['html_url']; ?>" target="_blank"><?php echo $test_link['html_url']; ?></a><br>
								WP - <a href="<?php echo $test_link['wp_page']; ?>" target="_blank"><?php echo $test_link['wp_page']; ?></a>
							</li>

						<?php endforeach; ?>
							
					</ul>

				<?php endif; ?>

				<hr>
				<h2>Default test pages</h2>

				<ul>
					<li><a href="<?php echo home_url('/'); ?>" target="_blank">Home page</a></li>
					<li><a href="<?php echo get_dashboard_url(); ?>" target="_blank">Admin dashboard</a></li>
					<li><a href="<?php echo home_url('/asdasdadasdasd'); ?>" target="_blank">404 page</a></li>
					<li><a href="<?php echo home_url('/?s=s'); ?>" target="_blank">Search page</a></li>
					<li><a href="<?php echo home_url('/?s='); ?>" target="_blank">Empty search</a></li>
					<li><a href="<?php echo home_url('/?s=asdasdasdasdasd'); ?>" target="_blank">Not found search</a></li>
				</ul>

				<hr>
				<h2>Post types</h2>
				<?php
					global $post;
					$excludePostTypes = array(
						'attachment',
						'revision',
						'nav_menu_item',
						'acf-field-group',
						'acf-field',
						'wpcf7_contact_form',
					);
					foreach ( get_post_types( '', 'names' ) as $post_type ) {
						if (!in_array($post_type, $excludePostTypes)){
							
								
							$args = array(
								'posts_per_page'     => 3,
								'post_type'       => $post_type,
								'post_status'     => 'any',
								'orderby'		  => 'rand',
							);
							if ($posts = get_posts( $args )){
								$counter = count($posts);

								echo '<h4 style="text-transform: capitalize;">'.$post_type.'</h4>';

								echo '<ul>';
									foreach ($posts as $post_item) {
										echo '<li><a href="'.get_permalink($post_item->ID).'" target="_blank">'.get_the_title($post_item->ID).'</a></li>';
									}
								echo '</ul>';
							}
						}
					}
				?>
				<hr>
				<h2><?php _e('Taxonomies', 'am'); ?></h2>
				
				<?php
					$excludeTaxonomies = array(
						'nav_menu',
						'link_category',
						'post_format',
					);
					$taxonomies = get_taxonomies(); 
					foreach ( $taxonomies as $taxonomy ) {
						if (!in_array($taxonomy, $excludeTaxonomies)){

							if ($terms = get_terms($taxonomy)){
								echo '<h4 style="text-transform: capitalize;">'.$taxonomy.'</h4>';

								echo '<ul>';
								$counter = count($terms);

								if ($counter > 3) {

									$random_term_keys = array_rand($terms, 3);
									foreach($random_term_keys as $random_term_key){
										$current_term = $terms[$random_term_key];
										echo '<li><a href="'.get_term_link( $current_term, $taxonomy).'" target="_blank">'.$current_term->name.'</a></li>';
									}

								}else{

									foreach($terms as $term_item){
										echo '<li><a href="'.get_term_link( $term_item, $taxonomy).'" target="_blank">'.$term_item->name.'</a></li>';
									}

								}
							}
							echo '</ul>';
						}
					}
					
				?>
				
				<hr>
				<h2><?php _e('Errors', 'am'); ?></h2>
				<?php
					if (WP_DEBUG){
						$files_array = am_get_error_log_rows();
						if (isset($files_array->errors_array) && !empty($files_array->errors_array) && is_array($files_array->errors_array)) {
							echo '<h4 style="text-transform: capitalize;">'.__('Error log file entry', 'am').'</h4>';
							echo '<ol>';
								foreach ($files_array->errors_array as $file) {
									echo '<li><em>'.$file['date']['date'].' - '.$file['date']['time'].'</em> <strong style="color: red;">'.$file['severity'].'</strong><p>'.$file['message'].'</p></li>';
								}
							echo '</ol>';
						} elseif (empty($files_array->errors_array)) {
							echo '<h4 style="color: green">'.__('Error log file is empty or not found', 'am').'</h4>';
						}
					}else{
						echo '<h4 style="color: red">'.__('WP_DEBUG is disabled', 'am').'</h4>';
					}
				?>

			</div>
		</div>
	</section>

<?php get_footer(); ?>