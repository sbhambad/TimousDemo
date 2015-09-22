<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array( 'item' );
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}

if ( $woocommerce_loop['columns'] == 3 ) {
	$col_class = 'col-md-4 col-sm-6';
} elseif ( $woocommerce_loop['columns'] == 4 ) {
	$col_class = 'col-md-3 col-sm-6';
} elseif ( $woocommerce_loop['columns'] == 1 ) {
	$col_class = 'col-md-12 col-sm-6';
} elseif ( $woocommerce_loop['columns'] == 2 ) {
	$col_class = 'col-md-6 col-sm-6';
} else {
	$col_class = 'col-md-4 col-sm-6';
}

?>
<div class="<?php echo esc_attr( $col_class ); ?>">

	<div <?php post_class( $classes ); ?>>

		<?php
			/**
			 * woocommerce_before_shop_loop_item hook
			 *
			 * @hooked woocommerce_template_loop_product_thumbnail - 10 // Added by theme.
			 */
			do_action( 'woocommerce_before_shop_loop_item' );
		?>

		<div class="item-info">

			<?php
				/**
				 * woocommerce_before_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_show_product_loop_sale_flash - 10 // Removed by theme.
				 * @hooked woocommerce_template_loop_product_thumbnail - 10 // Removed by theme.
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' );

				/**
				 * woocommerce_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_template_loop_product_title - 10
				 */
				do_action( 'woocommerce_shop_loop_item_title' );

				/**
				 * woocommerce_after_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_template_loop_rating - 5 // Removed by theme.
				 * @hooked woocommerce_template_loop_price - 10 // Removed by theme.
				 */
				do_action( 'woocommerce_after_shop_loop_item_title' );
			?>

			<p class="item-byline">
				<?php woocommerce_template_loop_price(); ?>
				<?php woocommerce_show_product_loop_sale_flash(); ?>
			</p>

		</div>

		<a class="btn item-btn" href="<?php the_permalink(); ?>"><?php _e('View More', 'ci_theme'); ?></a>

		<?php

			/**
			 * woocommerce_after_shop_loop_item hook
			 *
			 * @hooked woocommerce_template_loop_add_to_cart - 10 // Removed by theme.
			 */
			do_action( 'woocommerce_after_shop_loop_item' );

		?>

	</div>

</div>
