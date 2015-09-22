<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Increase loop count
$woocommerce_loop['loop'] ++;


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

	<div <?php wc_product_cat_class( 'item' ); ?>>
		<?php do_action( 'woocommerce_before_subcategory', $category ); ?>

		<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">

			<figure class="item-thumb">
				<?php
					/**
					 * woocommerce_before_subcategory_title hook
					 *
					 * @hooked woocommerce_subcategory_thumbnail - 10
					 */
					do_action( 'woocommerce_before_subcategory_title', $category );
				?>
			</figure>

			<div class="item-info">

				<p class="item-title-intro"><?php echo $category->name; ?></p>

				<p class="item-byline">
					<?php
						if ( $category->count > 0 )
							echo apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">(' . $category->count . ')</span>', $category );
					?>
				</p>

			</div>

			<?php
				/**
				 * woocommerce_after_subcategory_title hook
				 */
				do_action( 'woocommerce_after_subcategory_title', $category );
			?>

		</a>

		<?php do_action( 'woocommerce_after_subcategory', $category ); ?>
	</div>

</div>
