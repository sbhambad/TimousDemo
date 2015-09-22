<?php global $ci, $ci_defaults, $load_defaults, $content_width; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_ecommerce_options', 70);
	if( !function_exists('ci_add_tab_ecommerce_options') ):
		function ci_add_tab_ecommerce_options($tabs) 
		{ 
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('e-Commerce Options', 'ci_theme'); 
			return $tabs; 
		}
	endif;

	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );
	$ci_defaults['eshop_single_show_related_products'] = 'enabled';
	$ci_defaults['eshop_single_show_up_sells']         = 'enabled';
	$ci_defaults['eshop_posts_per_page']               = 15;
	$ci_defaults['eshop_products_view_first']          = 25;
	$ci_defaults['eshop_products_view_second']         = 50;
	$ci_defaults['eshop_products_view_all']            = 'enabled';
	$ci_defaults['product_columns']                    = '2';

	// Set the number of products per page
	add_filter('loop_shop_per_page', 'ci_woocommerce_loop_shop_per_page');
	if( !function_exists('ci_woocommerce_loop_shop_per_page') ):
	function ci_woocommerce_loop_shop_per_page($cols)
	{
		global $ci_defaults;
		$p = ci_setting( 'eshop_posts_per_page' );
		if ( ( !empty( $p ) and intval( $p ) > 0 ) or intval( $p ) == -1 ) {
			return intval( $p );
		} else {
			return $ci_defaults['eshop_posts_per_page'];
		}
	}
	endif;

	/*
	 * Allow users to view more products on shop pages.
	 */
	if ( isset($_GET['view']) ) {
		add_filter( 'loop_shop_per_page', 'ci_theme_wc_loop_shop_per_page_view', 20 );
	}
	if( !function_exists('ci_theme_wc_loop_shop_per_page_view') ):
	function ci_theme_wc_loop_shop_per_page_view( $posts_per_page ) {

		if( empty( $_GET['view'] ) ) {
			return $posts_per_page;
		}

		$values = array( $posts_per_page );

		if( 'all' === $_GET['view'] ) {
			$view = -1;
		} else {
			$view = intval( $_GET['view'] );
		}
		if( ci_setting('eshop_products_view_first') != '' ) {
			$values[] = ci_setting('eshop_products_view_first');
		}
		if( ci_setting('eshop_products_view_second') != '' ) {
			$values[] = ci_setting('eshop_products_view_second');
		}
		if( ci_setting('eshop_products_view_all') == 'enabled' ) {
			$values[] = -1;
		}

		if( in_array($view, $values) ) {
			return $view;
		}

		return $posts_per_page;
	}
	endif;


	if ( ci_setting('eshop_single_show_related_products') != 'enabled' ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}
	
	// Upsells
	if ( ci_setting('eshop_single_show_up_sells') != 'enabled' ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	}

?>
<?php else: ?>

	<fieldset class="set">
		<p class="guide"><?php _e('Control what you want displayed on your single product pages.', 'ci_theme'); ?></p>
		<?php ci_panel_checkbox('eshop_single_show_related_products', 'enabled', __('Show related products', 'ci_theme')); ?>
		<?php ci_panel_checkbox('eshop_single_show_up_sells', 'enabled', __('Show Up-Sells', 'ci_theme')); ?>
	</fieldset>

	<fieldset class="set">
		<p class="guide"><?php _e('Select the default number of columns your products will appear in (affects all shop pages).', 'ci_theme'); ?></p>
		<?php ci_panel_radio('product_columns', '2', '2', __('2 Columns', 'ci_theme')); ?>
		<?php ci_panel_radio('product_columns', '4', '3', __('3 Columns', 'ci_theme')); ?>
	</fieldset>

	<fieldset class="set">
		<p class="guide"><?php _e('Set how many products per page should be displayed on product listing pages (e.g. shop page, category pages, etc). Use <strong>-1</strong> for no limit.', 'ci_theme'); ?></p>
		<?php ci_panel_input('eshop_posts_per_page', __('Products per page', 'ci_theme')); ?>

		<p class="guide"><?php _e('Set additional numbers for viewing more products in product listing pages (e.g. shop page).', 'ci_theme'); ?></p>

		<?php ci_panel_input('eshop_products_view_first', __('First selection (single number, e.g. 20):', 'ci_theme')); ?>
		<?php ci_panel_input('eshop_products_view_second', __('Second selection (single number, e.g. 50):', 'ci_theme')); ?>
		<p>
			<?php ci_panel_checkbox('eshop_products_view_all', 'enabled', __('Enable View All Products Option?', 'ci_theme')); ?>
		</p>
	</fieldset>

<?php endif; ?>