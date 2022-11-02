		</main>
		<footer class="main-footer" id="footer">
			<div class="container links">
				<nav class="footer-nav desctop">
					<div class="col">
						<div class="title"><?php _e('TOOLAI'); ?></div>

						<?php $toolai = wp_nav_menu(['menu' => 'TOOLAI', 'echo' => false]); ?>
						<?php if ($toolai) echo strip_tags($toolai, '<a>'); ?>

					</div>
					<div class="col">
						<div class="title"><?php _e('Services'); ?></div>
						<?php $services = wp_nav_menu(['menu' => 'Services', 'echo' => false]); ?>
						<?php if ($services) echo strip_tags($services, '<a>'); ?>
					</div>
					<div class="col">
						<div class="title"><?php _e('Legal'); ?></div>
						<?php $legal = wp_nav_menu(['menu' => 'Legal', 'echo' => false]); ?>
						<?php if ($legal) echo strip_tags($legal, '<a>'); ?>
					</div>
				</nav>
				<nav class="footer-nav mobile">
					<div class="loc-lang">
						<div class="my-select country-switcher" data-value="null">
							<div class="placeholder">
								<span class="placeholder-text">
									<img src="./icons/flags/eng.png" alt="">
								</span>
								<span class="placeholder-arrow">
									<i class="fas fa-chevron-down"></i>
								</span>
							</div>
							<div class="options">
								<div class="option" data-value="ru"><img src="./icons/flags/rf.jpg" alt=""></div>
								<div class="option" data-value="kg"><img src="./icons/flags/kg.png" alt=""></div>
								<div class="option" data-value="en"><img src="./icons/flags/eng.png" alt=""></div>
							</div>
						</div>
						<div class="my-select lang-switcher" data-value="null">
							<div class="placeholder">
								<span class="placeholder-text">English</span>
								<span class="placeholder-arrow">
									<i class="fas fa-chevron-down"></i>
								</span>
							</div>
							<div class="options">
								<div class="option" data-value="ru">Русский</div>
								<div class="option" data-value="kg">Кыргызча</div>
								<div class="option" data-value="en">English</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="s-accordion">
						<h5 class="accordion-title">
							<span><?php _e('TOOLAI'); ?></span>
							<i class="fas fa-chevron-down accordion-arrow" aria-hidden="true"></i>
						</h5>
						<div class="accordion-body">
							<?php $toolai = wp_nav_menu(['menu' => 'TOOLAI', 'echo' => false]); ?>
							<?php if ($toolai) echo strip_tags($toolai, '<a>'); ?>
						</div>
					</div>
					<hr>
					<div class="s-accordion">
						<h5 class="accordion-title">
							<span><?php _e('Services'); ?></span>
							<i class="fas fa-chevron-down accordion-arrow" aria-hidden="true"></i>
						</h5>
						<div class="accordion-body">
							<?php $toolai = wp_nav_menu(['menu' => 'Services', 'echo' => false]); ?>
							<?php if ($toolai) echo strip_tags($toolai, '<a>'); ?>
						</div>
					</div>
					<hr>
					<div class="s-accordion">
						<h5 class="accordion-title">
							<span><?php _e('Legal'); ?></span>
							<i class="fas fa-chevron-down accordion-arrow" aria-hidden="true"></i>
						</h5>
						<div class="accordion-body">
							<?php $toolai = wp_nav_menu(['menu' => 'Legal', 'echo' => false]); ?>
							<?php if ($toolai) echo strip_tags($toolai, '<a>'); ?>
						</div>
					</div>
					<hr>
				</nav>
				<div class="col footer-tools">
					<div class="socials">
						<?php
						if ($social = get_field('twitter', 'options')) {
							echo '<a href="' . $social . '"><i class="fab fa-twitter-square"></i></a>';
						}
						if ($social = get_field('pinterest', 'options')) {
							echo '<a href="' . $social . '"><i class="fab fa-pinterest"></i></a>';
						}
						if ($social = get_field('tiktok', 'options')) {
							echo '<a href="' . $social . '"><i class="fab fa-tiktok"></i></a>';
						}
						if ($social = get_field('youtube', 'options')) {
							echo '<a href="' . $social . '"><i class="fab fa-youtube-square"></i></a>';
						}
						if ($social = get_field('facebook', 'options')) {
							echo '<a href="' . $social . '"><i class="fab fa-facebook"></i></a>';
						}
						if ($social = get_field('instagram', 'options')) {
							echo '<a href="' . $social . '"><i class="fab fa-instagram-square"></i></a>';
						}
						?>
					</div>
					<form action="" class="contact-form">
						<label for="message">Email us</label>
						<div class="row">
							<input type="text" id="message">
							<button type="submit">Send</button>
						</div>
					</form>
				</div>
			</div>
			<div class="copyright">
				<div class="container"><span>© <?php _e('Copyright ');
												echo date('Y') . ' ';
												bloginfo('name'); ?> </span></div>
			</div>
		</footer>
		<?php wp_footer(); ?>
		</body>

		</html>