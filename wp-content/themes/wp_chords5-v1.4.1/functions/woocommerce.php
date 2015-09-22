<?php if(woocommerce_enabled()):

	// Skip the default woocommerce styling and use our boilerplate.
	if ( !function_exists('ci_deregister_woocommerce_styles') ) {
		function ci_deregister_woocommerce_styles()  {
			wp_deregister_style('woocommerce-general');
		}
		add_filter('woocommerce_enqueue_styles', 'ci_deregister_woocommerce_styles');
	}

	// Change number of columns in product loop
	add_filter('loop_shop_columns', 'ci_loop_show_columns');
	if ( !function_exists('ci_loop_show_columns') ):
	function ci_loop_show_columns() {
		return ci_setting('product_columns');
	}
	endif;

	/*
	 * Unhook the following functions as they are either not needed, or needed in a place where a hook is not available
	 * therefore called directly from the template files.
	 */

	// Remove result count, e.g. "Showing 1–10 of 22 results", added manually
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

	// We don't need the Rating and Add to Cart button.
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Remove price and sale flash from the loop. We will call them manually.
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

	// Move thumbnail from woocommerce_before_shop_loop_item_title to woocommerce_before_shop_loop_item hook
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_thumbnail', 10 );

	// We don't need the coupon form in the checkout page. It's included in the cart page.
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

	// Removing stuff from product page (woocommerce/content-single-product.php)
	remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
	add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 3);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

	// Move cross sell display from collaterals to right after the table.
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	add_action( 'woocommerce_after_cart_table', 'woocommerce_cross_sell_display' );

	// Remove breadcrumbs.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

	// Override the product thumbnail hooked function, as we just want change its output.
	if ( !function_exists( 'woocommerce_template_loop_product_thumbnail' ) ):
	function woocommerce_template_loop_product_thumbnail() {
		?>
		<a href="<?php the_permalink(); ?>">
			<figure class="item-thumb">
				<?php echo woocommerce_get_product_thumbnail(); ?>
			</figure>
		</a>
		<?php
	}
	endif;

	// Replace the default placeholder image with ours (it has the right dimentions).
	add_filter('woocommerce_placeholder_img_src', 'ci_change_woocommerce_placeholder_img_src');
	if ( !function_exists( 'ci_change_woocommerce_placeholder_img_src' ) ):
	function ci_change_woocommerce_placeholder_img_src($src)
	{
		return get_child_or_parent_file_uri('/images/placeholder.png');
	}
	endif;
	
	add_filter('woocommerce_placeholder_img', 'ci_woocommerce_placeholder_img');
	if ( !function_exists( 'ci_woocommerce_placeholder_img' ) ):
	function ci_woocommerce_placeholder_img($html)
	{
		$html = preg_replace('/width="[[:alnum:]%]*"/', '', $html);
		$html = preg_replace('/height="[[:alnum:]%]*"/', '', $html);
		return $html;
	}
	endif;


	/*
	 * Allow users to view alternative layout.
	 */
	if ( isset($_GET['list']) ) {
		add_filter( 'shop_list_view', 'ci_theme_get_list_view_param' );
	}

	function ci_theme_get_list_view_param($val)	{
		if(!empty($_GET['list']) and in_array($_GET['list'], array('default', 'alt')))
			return $_GET['list'];
		else
			return false;
	}


	// Make some WooCommerce pages get the fullwidth template
	add_filter( 'template_include', 'ci_theme_wc_cart_fullwidth' );
	if ( !function_exists('ci_theme_wc_cart_fullwidth') ):
		function ci_theme_wc_cart_fullwidth($template)
		{
			$located = get_child_or_parent_file_path( 'template-fullwidth.php' );

			if ( woocommerce_enabled() and ( is_cart() OR is_checkout() OR is_account_page() ) and !empty( $located ) )
				return $located;

			return $template;
		}
	endif;
endif; // woocommerce_enabled() ?>