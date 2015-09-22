<?php 
	get_template_part( 'panel/constants' );

	load_theme_textdomain( 'ci_theme', get_template_directory() . '/lang' );

	// This is the main options array. Can be accessed as a global in order to reduce function calls.
	$ci = get_option(THEME_OPTIONS);
	$ci_defaults = array();

	// The $content_width needs to be before the inclusion of the rest of the files, as it is used inside of some of them.
	if ( ! isset( $content_width ) ) $content_width = 750;

	//
	// Let's bootstrap the theme.
	//
	get_template_part('panel/bootstrap');

	get_template_part('functions/woocommerce');
	get_template_part('functions/shortcodes');
	get_template_part('functions/downloads_handler');


	//
	// Define our various image sizes.
	//
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 750, 500, true );
	add_image_size( 'ci_blog_thumb', 750, 370, true );
	add_image_size( 'ci_thumb_sm', 350, 235, true );
	add_image_size( 'ci_blog_full', 1140, 380, true );
	add_image_size( 'ci_site_header', 1920, 160, true );
	add_image_size( 'ci_slider', 1920, 850, true );
	add_image_size( 'ci_square', 750, 750, true );
	add_image_size( 'ci_masonry', 750 );


	// Enable HTML5 support
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Let WooCommerce know that we support it.
	add_theme_support('woocommerce');

	// Set image sizes also for woocommerce.
	// Run only when the theme or WooCommerce is activated.
	add_action('ci_theme_activated', 'ci_woocommerce_image_dimensions');
	register_activation_hook( WP_PLUGIN_DIR.'/woocommerce/woocommerce.php', 'ci_woocommerce_image_dimensions' );
	if( !function_exists('ci_woocommerce_image_dimensions') ):
	function ci_woocommerce_image_dimensions()	{
		// Image sizes
		update_option( 'shop_thumbnail_image_size', array(
			'width'  => '110',
			'height' => '110',
			'crop'   => 1
		) );
		update_option( 'shop_catalog_image_size', array(
			'width'  => '750',
			'height' => '9999',
			'crop'   => 0
		) );
		update_option( 'shop_single_image_size', array(
			'width'  => '560',
			'height' => '9999',
			'crop'   => 0
		) );
	}
	endif;

	// Let the user choose a color scheme on each post individually.
	add_ci_theme_support('post-color-scheme', array('page', 'post', 'product', 'cpt_artist', 'cpt_discography', 'cpt_event', 'cpt_gallery', 'cpt_video'));

	// Enable the automatic video thumbnails.
	add_filter( 'ci_automatic_video_thumbnail_field', 'ci_theme_add_auto_thumb_video_field' );
	if ( !function_exists( 'ci_theme_add_auto_thumb_video_field' ) ):
	function ci_theme_add_auto_thumb_video_field( $field ) {
		return 'ci_cpt_video_url';
	}
	endif;

	add_filter('the_content', 'ci_prettyPhoto_rel', 12);
	add_filter('get_comment_text', 'ci_prettyPhoto_rel');
	add_filter('wp_get_attachment_link', 'ci_prettyPhoto_rel');
	if( !function_exists('ci_prettyPhoto_rel') ):
	function ci_prettyPhoto_rel( $content )	{
		global $post;
		$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
		
		$replacement = '<a$1href=$2$3.$4$5 data-rel="prettyPhoto['.$post->ID.']"$6>$7</a>';
		
		$content = preg_replace($pattern, $replacement, $content);
		return $content;
	}
	endif;

	if( !function_exists( 'ci_get_remaining_time_array' ) ):
	function ci_get_remaining_time_array( $date, $time ) {
		$now  = current_time( 'timestamp' );
		$tmp  = strtotime( trim( $date ) . ' ' . trim( $time ), $now );
		$diff = $tmp - $now;
		if ( $diff <= 1 ) {
			return false;
		}
		$days    = floor( $diff / ( 60 * 60 * 24 ) );
		$diff    = $diff % ( 60 * 60 * 24 );
		$hours   = floor( $diff / ( 60 * 60 ) );
		$diff    = $diff % ( 60 * 60 );
		$minutes = round( $diff / 60 );
		$array   = array(
			'days'    => $days,
			'hours'   => $hours,
			'minutes' => $minutes,
		);

		return $array;
	}
	endif;


	if( !function_exists( 'ci_is_repeating_button' ) ):
	function ci_is_repeating_button($txt) {
		$pattern = '/^<(a|span).*class="btn".*\1>$/';
		preg_match($pattern, $txt, $matches);
		return ! empty( $matches );
	}
	endif;

	if( !function_exists( 'ci_get_layout_classes' ) ):
	function ci_get_layout_classes( $context, $part ) {

		$sidebar_cols   = '';
		$content_cols   = '';
		$meta_placement = '';
		$glob_placement = ci_setting( 'layout_site' );

		if( is_singular() ) {
			$meta_placement = get_post_meta( get_the_ID(), 'meta_placement', true );
		}

		if ( 'cpt' == $context ) {
			if ( 'alt' != $glob_placement ) {
				$sidebar_cols = 'col-md-push-8 col-sm-push-7';
				$content_cols = 'col-md-pull-4 col-sm-pull-5';
			}
			if ( 'right' == $meta_placement ) {
				$sidebar_cols = 'col-md-push-8 col-sm-push-7';
				$content_cols = 'col-md-pull-4 col-sm-pull-5';
			} elseif ( 'left' == $meta_placement ) {
				$sidebar_cols = '';
				$content_cols = '';
			}
		} elseif ( 'blog' == $context ) {
			if ( 'alt' == $glob_placement ) {
				$sidebar_cols = 'col-md-pull-8 col-sm-pull-7';
				$content_cols = 'col-md-push-4 col-sm-push-5';
			}
			if ( 'right' == $meta_placement ) {
				$sidebar_cols = '';
				$content_cols = '';
			} elseif ( 'left' == $meta_placement ) {
				$sidebar_cols = 'col-md-pull-8 col-sm-pull-7';
				$content_cols = 'col-md-push-4 col-sm-push-5';
			}
		}

		if ( 'content' == $part ) {
			return $content_cols;
		} elseif ( 'sidebar' == $part ) {
			return $sidebar_cols;
		}

	}
	endif;

	if ( !function_exists( 'ci_get_read_more_text' ) ):
	function ci_get_read_more_text( $post_type ) {
		$wording = ci_setting( 'read_more_text' );

		if( !in_array( $post_type, array('post', 'page', 'attachment') ) ) {
			$tmp = ci_setting( 'read_more_text_' . $post_type );
			if( !empty( $tmp ) ) {
				$wording = $tmp;
			}
		}

		return $wording;
	}
	endif;

	if ( ! function_exists( 'ci_theme_get_slides' ) ):
	function ci_theme_get_slides( $base_category = false, $post_id = false, $return_ids = false ) {

		if( $base_category === false && $post_id === false && get_option( 'show_on_front' ) == 'page' ) {
			$front = get_option( 'page_on_front' );
			if ( ! empty( $front ) ) {
				$base = get_post_meta( $front, 'base_slider_category', true );
				if ( ! empty( $base ) ) {
					$base_category = $base;
				}
			}
		} elseif( $base_category === false && $post_id !== false ) {
			$base = get_post_meta( $post_id, 'base_slider_category', true );
			if ( ! empty( $base ) ) {
				$base_category = $base;
			}
		}

		$args = array(
			'post_type'      => 'cpt_slider',
			'posts_per_page' => - 1,
		);

		if ( ! empty( $base_category ) && $base_category > 0 ) {
			$args = array_merge( $args, array(
				'tax_query' => array(
					array(
						'taxonomy' => 'slider-category',
						'terms'    => intval( $base_category ),
					)
				)
			) );
		}

		if( $return_ids === true ) {
			$args['fields'] = 'ids';
		}

		return new WP_Query( $args );
	}
	endif;
