<!doctype html>
<!--[if IE 8]> <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'after_open_body_tag' ); ?>

<div id="page">
	<div id="mobile-bar">
		<a class="menu-trigger" href="#mobilemenu"><i class="fa fa-bars"></i></a>
		<h1 class="mob-title"><?php bloginfo('name'); ?></h1>
	</div>

	<header id="header">
		<div class="container">
			<div class="row">
				<div class="col-md-2 col-sm-1">
					<?php ci_e_logo('<h1 class="site-logo ' . get_logo_class() . '">', '</h1>'); ?>
				</div>
				<div class="col-md-10 col-sm-11">
					<?php if ( has_nav_menu( 'ci_secondary_menu' ) OR woocommerce_enabled() ) : ?>
						<nav class="nav-secondary-wrap">
							<ul class="nav-secondary">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'ci_secondary_menu',
										'fallback_cb'    => '',
										'container'      => '',
										'menu_id'        => '',
										'menu_class'     => '',
										'items_wrap'     => '%3$s'
									));
								?>

								<?php if ( woocommerce_enabled() ) : ?>
									<?php global $woocommerce; ?>
									<li>
										<div class="cart-head">
											<?php _e('Shopping Bag', 'ci_theme'); ?>:
											<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
												<b><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->get_cart_contents_count(), 'ci_theme'), $woocommerce->cart->get_cart_contents_count()); ?></b>	<span class="cart-price"><?php echo $woocommerce->cart->get_cart_total(); ?></span>
											</a>
										</div>
									</li>
								<?php endif; ?>
							</ul>
						</nav>
					<?php endif; ?>

					<nav id="nav">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'ci_main_menu',
								'fallback_cb'    => '',
								'container'      => '',
								'menu_id'        => 'navigation',
								'menu_class'     => 'group'
							) );
						?>
					</nav>

					<div id="mobilemenu"></div>
				</div>
			</div>
		</div>
	</header>