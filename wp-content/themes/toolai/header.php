<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<?php if (is_singular() && pings_open(get_queried_object())) : ?>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php endif; ?>
	<?php /*<link rel="shortcut icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/img/favicon.png">*/ ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div class="body-mask"></div>
	<div class="modals">
		<div class="modal auth-modal" data-name="auth">
			<div class="modal-content">
				<div class="modal-header">
					<h4>Authorization</h4>
					<button class="modal-close"><i class="fas fa-times"></i></button>
				</div>
				<div class="modal-body">
					<form action="" class="auth-form">
						<input type="email" placeholder="email" required>
						<input type="password" placeholder="password" required>
						<button  type="submit">Sign in</button>
						<button type="button" class="modal-close" data-toggle="modal" data-modal="register">
							Don't have an account?
						</button>
					</form>
				</div>
			</div>
		</div>
		<div class="modal auth-modal" data-name="register">
			<div class="modal-content">
				<div class="modal-header">
					<h4>Registration</h4>
					<button class="modal-close"><i class="fas fa-times"></i></button>
				</div>
				<div class="modal-body">
					<form action="" class="auth-form">
						<input type="email" placeholder="email" required>
						<input type="password" placeholder="password" required>
						<input type="password" placeholder="confirm the password" required>
						<button type="submit">Sign in</button>
						<button type="button" class="modal-close" >
							Already have an account
						</button>
					</form>
				</div>
			</div>
		</div>
		<div class="modal search-modal" data-name="search">
			<div class="modal-content">
				<div class="modal-body">
					<?php echo get_search_form(); ?>
				</div>
			</div>
		</div>
		<div class="modal cart-modal" data-name="cart">
			<div class="modal-content">
				<div class="modal-header">
					<h4>Shopping cart</h4>
					<button class="modal-close">
						<i class="fas fa-times"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="cart-list">
						<?php
						global $woocommerce;
						$items = $woocommerce->cart->get_cart();

						foreach ($items as $item => $values) :
							$_product =  wc_get_product($values['data']->get_id());
							$price = get_post_meta($values['product_id'], '_price', true);

						?>
							<div class="cart__item">
								<div class="item__col">
									<?php if ($image_url = wp_get_attachment_image_url(get_post_thumbnail_id($_product->get_id()), 'full')) : ?>
										<img src="<?php echo $image_url; ?>" class="item__thumb" alt="">
									<?php endif; ?>
									<span class="item__name"><?php echo $_product->get_title(); ?></span>
								</div>
								<div class="item__col">
									<span class="item__color">red</span>
									<span class="item__size"> S </span>
									<span class="item__price"><?php echo $price; ?></span>
									<a href="<?php echo wc_get_cart_remove_url($item); ?>"><span class="item__price">×</span></a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="modal-footer">
					<strong class="cart-total"><?php echo $woocommerce->cart->get_cart_total(); ?></strong>
					<a href="<?php echo wc_get_checkout_url(); ?>" class="ptocheckout"><?php _e('Proceed to Checkout'); ?></a>
				</div>
			</div>
		</div>
	</div>
	<div class="wrapper">
		<header class="main-header" id="header">
			<div class="container">
				<div class="tools desctop">
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
					<div class="user-panel desctop">
						<div class="user-panel__item search-button" data-toggle="modal" data-modal="search">
							<i class="fas fa-search"></i>
						</div>
						<div class="user-panel__item favorite">
							<a href="<?php the_permalink(159); ?>"><i class="far fa-heart"></i></a>
						</div>
						<div class="user-panel__item bag" data-toggle="modal" data-modal="cart">
							<i class="fas fa-shopping-bag"></i>
						</div>
						<a href="<?php the_permalink(9); ?>" class="user-panel__item  lrm-login ">
							<i class="far fa-user"></i>
						</a>
					</div>
				</div>
				<div class="logo desctop">
					<?php if ($header_logo = get_field('header_logo', 'options')) : ?>
						<?php am_the_retina_icon($header_logo, 300); ?>
					<?php endif; ?>
				</div>
				<nav class="main-nav desctop">
					<?php
					$menus = wp_get_nav_menu_items('Main Menu');
					foreach ($menus as $k => $menu) {
						if ($menu->menu_item_parent == 0) {
							$menu_title = $menu->title;
							$hyper_menu[$menu_title]['title'] = $menu_title;
							$hyper_menu[$menu_title]['url'] = $menu->url;
							$hyper_menu[$menu_title]['id'] = $menu->object_id;
						} else {
							$hyper_menu[$menu_title]['children'][] = [$menu->title, $menu->url];
						}
					}

					?>
					<?php foreach ($hyper_menu as $mega_menu) : ?>
						<?php $is_has_child = (array_key_exists('children', $mega_menu)) ? true : false; ?>
						<div class="nav-menu" <?php echo ($is_has_child) ? 'data-toggle="drop-menu"' : ''; ?>>
							<a href="<?php echo $mega_menu['url']; ?>" class="title">
								<?php echo $mega_menu['title']; ?>
							</a>
							<?php if ($is_has_child) : ?>
								<div class="drop-menu">
									<div class="list">
										<div class="col">
											<?php if ($mega_menu['title'] == 'STORE') echo '<a href="' . $mega_menu['url'] . '" class="nav-link">' . __('All clothing') . '</a>'; ?>
											<?php for ($c = 0; $c <= count($mega_menu['children']); $c++) : ?>
												<?php if (array_key_exists($c, $mega_menu['children'])) : ?>
													<a href="<?php echo $mega_menu['children'][$c][1]; ?>" class="nav-link"><?php echo $mega_menu['children'][$c][0]; ?></a>
												<?php endif; ?>
												<?php $k = ($mega_menu['title'] == 'STORE') ? 1 : 0; ?>
												<?php if ($c == 3 - $k || $c == 7 - $k || $c == 11 - $k) echo '</div><div class="col">'; ?>
											<?php endfor; ?>


										</div>
									</div>
									<?php if ($featured_img_url = get_the_post_thumbnail_url($mega_menu['id'])) : ?>
										<div class="image">
											<img src="<?php echo $featured_img_url; ?>" alt="">
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>

					<?php endforeach; ?>
				</nav>
			</div>
			<nav class="mobile-nav">
				<!-- Mobile Nav -->
				<div class="mobile-nav-header">
					<button class="mobile-nav-button">
						<span class="top"></span>
						<span class="mid"></span>
						<span class="sub"></span>
					</button>
					<div class="logo">
						<img src="./icons/logo.png" alt="">
					</div>
					<div class="user-panel">
						<div class="user-panel__item --white search-button" data-toggle="modal" data-modal="search">
							<i class="fas fa-search" aria-hidden="true"></i>
						</div>
						<div class="user-panel__item --white bag" data-toggle="modal" data-modal="cart">
							<i class="fas fa-shopping-bag"></i>
						</div>
					</div>
				</div>
				<div class="menu">
					<div class="menu-header">
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
								<div class="placeholder"><span class="placeholder-text">English</span><span class="placeholder-arrow"><i class="fas fa-chevron-down"></i></span></div>
								<div class="options">
									<div class="option" data-value="ru">Русский</div>
									<div class="option" data-value="kg">Кыргызча</div>
									<div class="option" data-value="en">English</div>
								</div>
							</div>
						</div>
						<div class="user-panel">
							<div class="user-panel__item favorite">
								<i class="far fa-heart" aria-hidden="true"></i>
							</div>
							<a class="user-panel__item profile lrm-login" >
								<i class="far fa-user" aria-hidden="true"></i>
							</a>
						</div>
					</div>
					<?php foreach ($hyper_menu as $mega_menu) : ?>

						<?php $is_has_child = (array_key_exists('children', $mega_menu)) ? true : false; ?>
						<div class="nav-item" <?php echo ($is_has_child) ? 'data-toggle="sub-menu"' : ''; ?>>
							<a href="<?php echo $mega_menu['url']; ?>" class="title">
								<?php echo $mega_menu['title']; ?>
							</a>
							<?php if ($is_has_child) : ?>

								<div class="sub-menu">
									<div class="sub-menu-header">
										<div class="sub-menu-close">
											<i class="fas fa-long-arrow-alt-right"></i>
										</div>
									</div>
									<div class="list">
										<?php if ($mega_menu['title'] == 'STORE') echo '<a href="' . $mega_menu['url'] . '" class="nav-link">' . __('All clothing') . '</a>'; ?>
										<?php foreach ($mega_menu['children'] as $child) : ?>
											<a href="<?php echo $child[1]; ?>" class="nav-link"><?php echo $child[0]; ?></a>
										<?php endforeach; ?>
									</div>
								</div>

							<?php endif; ?>
						</div>

					<?php endforeach; ?>
				</div>
			</nav> <!-- Mobile Nav End -->
		</header>
		<main id="main">