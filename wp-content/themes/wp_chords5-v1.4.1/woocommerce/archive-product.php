<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>

<?php
	/**
	 * woocommerce_before_main_content hook
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20 // REMOVED by theme
	 */

	do_action('woocommerce_before_main_content');
?>
<div class="col-md-8">
	<div class="shop-actions group">
		<div class="actions">
			<?php wc_get_template( 'loop/result-count.php' ); ?>

			<div class="product-number">
				<span><?php _e('View:', 'ci_theme'); ?></span>
				<a href="<?php echo esc_url( add_query_arg('view', ci_setting('eshop_products_view_first'), get_permalink( ci_translate_post_id( wc_get_page_id( 'shop' ), true, 'page' ) ) ) ); ?>"><?php ci_e_setting('eshop_products_view_first'); ?></a>
				<a href="<?php echo esc_url( add_query_arg('view', ci_setting('eshop_products_view_second'), get_permalink( ci_translate_post_id( wc_get_page_id( 'shop' ), true, 'page' ) ) ) ); ?>"><?php ci_e_setting('eshop_products_view_second'); ?></a>
				<?php if ( ci_setting('eshop_products_view_all') ) : ?>
					<a href="<?php echo esc_url( add_query_arg('view', 'all', get_permalink( ci_translate_post_id( wc_get_page_id( 'shop' ), true, 'page' ) ) ) ); ?>"><?php _e('All', 'ci_theme'); ?></a>
				<?php endif; ?>
			</div>
		</div> <!-- .shop-actions -->

		<?php
			/**
			 * woocommerce_before_shop_loop hook
			 *
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action( 'woocommerce_before_shop_loop' );
		?>
	</div>

	<?php if ( is_tax() ) : ?>
		<h2 class="section-title"><?php single_term_title(); ?></h2>
	<?php endif; ?>

	<?php do_action( 'woocommerce_archive_description' ); ?>

	<div class="product-list item-list row">

		<?php if ( have_posts() ) : ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<div class="col-xs-12">
				<?php wc_get_template( 'loop/no-products-found.php' ); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php
		/**
		 * woocommerce_after_shop_loop hook
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );
	?>
</div>

<div class="col-md-4">
	<?php do_action('woocommerce_sidebar'); ?>
</div>

<?php
	/**
	 * woocommerce_after_main_content hook
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');
?>

<?php get_footer('shop'); ?>
