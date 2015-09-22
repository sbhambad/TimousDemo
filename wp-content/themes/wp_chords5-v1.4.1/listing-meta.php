<?php
	$cpt_taxonomy = get_object_taxonomies( get_post_type() );
	if ( is_array( $cpt_taxonomy ) && count( $cpt_taxonomy ) > 0 ) {
		$cpt_taxonomy = $cpt_taxonomy[0];
	} else {
		$cpt_taxonomy = 'category';
	}

	switch( get_post_type() ) {
		case 'post':
			?>
			<div class="item-info">
				<p class="item-title-intro"><?php echo get_the_date(); ?></p>
				<p class="item-title-main"><?php the_title(); ?></p>
			</div>
			<?php
			break;
		case 'page':
			?>
			<div class="item-info">
				<p class="item-title-main"><?php the_title(); ?></p>
			</div>
			<?php
			break;
		case 'cpt_artist':
			?>
			<div class="item-info">
				<p class="item-title-main"><?php the_title(); ?></p>
				<p class="item-byline"><?php echo strip_tags( get_the_term_list( get_the_ID(), $cpt_taxonomy, '', ', ' ) ); ?></p>
			</div>
			<?php
			break;
		case 'cpt_discography':
			?>
			<div class="item-info">
				<p class="item-title-main"><?php the_title(); ?></p>
				<p class="item-byline"><?php echo get_post_meta( get_the_ID(), 'ci_cpt_discography_label', true ); ?></p>
			</div>
			<?php
			break;
		case 'cpt_gallery':
			?>
			<div class="item-info">
				<p class="item-title-main"><?php the_title(); ?></p>
				<p class="item-byline"><?php echo get_post_meta( get_the_ID(), 'ci_cpt_gallery_location', true ); ?></p>
			</div>
			<?php
			break;
		case 'cpt_video':
			?>
			<div class="item-info">
				<p class="item-title-main"><?php the_title(); ?></p>
				<p class="item-byline"><?php echo get_post_meta( get_the_ID(), 'ci_cpt_video_location', true ); ?></p>
			</div>
			<?php
			break;
		case 'product':
			?>
			<div class="item-info">
				<p class="item-title-main"><?php the_title(); ?></p>
				<p class="item-byline">
					<?php woocommerce_template_loop_price(); ?>
					<?php
						/**
						 * woocommerce_before_shop_loop_item_title hook
						 *
						 * @hooked woocommerce_show_product_loop_sale_flash - 10
						 */
						do_action( 'woocommerce_before_shop_loop_item_title' );
					?>
				</p>
			</div>
			<?php
			break;
		case 'cpt_event':
			?>
			<div class="item-info">
				<?php
					$event_date      = get_post_meta( get_the_ID(), 'ci_cpt_event_date', true );
					$event_time      = get_post_meta( get_the_ID(), 'ci_cpt_event_time', true );
					$event_timestamp = strtotime( $event_date . ' ' . $event_time, current_time( 'timestamp' ) );
					$event_location  = get_post_meta( get_the_ID(), 'ci_cpt_event_location', true );
					$recurrent       = get_post_meta( $post->ID, 'ci_cpt_event_recurrent', true ) == 'enabled' ? true : false;
					$recurrence      = get_post_meta( $post->ID, 'ci_cpt_event_recurrence', true );
				?>
				<?php if( $recurrent ): ?>
					<p class="item-title-intro"><?php echo $recurrence; ?></p>
				<?php else: ?>
					<p class="item-title-intro"><?php echo date_i18n( get_option('date_format'), $event_timestamp ); ?></p>
				<?php endif; ?>
				<p class="item-title-main"><?php the_title(); ?></p>
				<p class="item-byline"><?php echo $event_location; ?></p>
			</div>
			<?php
			break;
	}
?>

