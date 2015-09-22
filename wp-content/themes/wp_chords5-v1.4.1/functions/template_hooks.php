<?php
add_filter('ci_panel_option_show_site_slogan', '__return_false');

add_filter( 'template_include', 'ci_theme_blog_template' );
if ( !function_exists( 'ci_theme_blog_template' ) ):
function ci_theme_blog_template( $template ) {
	$located = get_child_or_parent_file_path( 'template-blog-fullwidth.php' );

	if ( is_home() && ci_setting('layout_blog') == 'fullwidth' && !empty( $located ) ) {
		return $located;
	}

	return $template;
}
endif;

add_action( 'wp_enqueue_scripts', 'ci_theme_enqueue_header_css' );
if ( ! function_exists( 'ci_theme_enqueue_header_css' ) ) :
function ci_theme_enqueue_header_css() {
	$css = '';

	$slides_count = 0;
	if ( is_page_template( 'template-frontpage.php' ) ) {
		$slides       = ci_theme_get_slides( false, false, true );
		$slides_count = $slides->post_count;
	}

	if ( is_page_template( 'template-frontpage.php' ) && $slides_count > 0 ) {
		$css = '#header { background: none; border: none; }';
	} else {
		$img_url = ci_setting( 'default_header_bg' );
		$img_id  = ci_setting( 'default_header_bg_hidden' );

		if ( !empty( $img_url ) and !empty( $img_id ) ) {
			$img_url = ci_get_image_src( $img_id, 'ci_page_header' );
		}

		if ( !empty( $img_url ) ) {
			$css = '#header { background-image: url("' . esc_url( $img_url ) . '"); }';
		}
	}

	wp_add_inline_style('ci-color-scheme', $css);
}
endif;

add_filter( 'template_include', 'ci_theme_woo_pages_fullwidth' );
if ( !function_exists('ci_theme_woo_pages_fullwidth') ):
function ci_theme_woo_pages_fullwidth($template) {
	$located = get_child_or_parent_file_path( 'template-page-fixed.php' );

	if ( woocommerce_enabled() && !empty( $located ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		return $located;
	}
	return $template;
}
endif;

add_filter( 'get_post_metadata', 'ci_theme_woo_pages_fullwidth_option', 10, 4 );
if ( !function_exists('ci_theme_woo_pages_fullwidth_option') ):
function ci_theme_woo_pages_fullwidth_option( $value, $object_id, $meta_key, $single ) {
	if ( 'page_layout' == $meta_key ) {
		if ( woocommerce_enabled() && ( is_cart() || is_checkout() || is_account_page() ) ) {
			return 'full';
		}
	}

	return $value;
}
endif;

add_filter( 'body_class', 'ci_theme_body_classes', 20 );
if ( ! function_exists( 'ci_theme_body_classes' ) ):
function ci_theme_body_classes( $classes ) {
	// Add a ci-no-slider class to body if there are no slide items.
	$slides = ci_theme_get_slides( false, false, true );
	if ( $slides->post_count == 0 && is_page_template( 'template-frontpage.php' ) ) {
		$classes[] = 'ci-no-slider';
	}

	// Add specific classes depending on color scheme
	$scheme = $classes['theme_color_scheme'];
	if ( substr_left( $scheme, 10 ) == 'ci-scheme-' ) {
		$scheme = str_replace( 'ci-scheme-', '', $scheme );

		if ( substr_left( $scheme, 6 ) == 'white_' ) {
			$classes['theme_color_scheme_group'] = 'ci-light-scheme';
		} else {
			$classes['theme_color_scheme_group'] = 'ci-dark-scheme';
		}
	}

	return $classes;
}
endif;

add_filter( 'ci_footer_credits', 'ci_theme_footer_credits' );
if ( ! function_exists( 'ci_theme_footer_credits' ) ):
function ci_theme_footer_credits( $string ) {

	if ( ! CI_WHITELABEL ) {
		return sprintf( __( '<a href="%s">Powered by WordPress</a> - A theme by CSSIgniter.com', 'ci_theme' ),
			esc_url( 'http://www.wordpress.org' )
		);
	} else {
		/* translators: %2$s is replaced by the website's name. */
		return sprintf( __( '<a href="%1$s">%2$s</a> - <a href="%3$s">Powered by WordPress</a>', 'ci_theme' ),
			esc_url( home_url() ),
			get_bloginfo( 'name' ),
			esc_url( 'http://www.wordpress.org' )
		);
	}
}
endif;
