<?php global $product; ?>
<li class="media">
	<div class="media-thumb">
		<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
			<?php echo $product->get_image(); ?>
		</a>
	</div>

	<div class="media-content">
		<a class="media-title" href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>"><?php echo $product->get_title(); ?></a>
		<p class="price"><?php echo $product->get_price_html(); ?></p>
		<?php if ( ! empty( $show_rating ) ) echo $product->get_rating_html(); ?>
	</div>
</li>