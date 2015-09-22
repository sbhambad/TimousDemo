<?php
if( !function_exists('ci_tracklisting_shortcode') ):
function ci_tracklisting_shortcode($params, $content = null) {

	extract( shortcode_atts( array(
		'id'           => '',
		'slug'         => '',
		'limit'        => -1,
		'tracks'       => '',
		'hide_buttons' => '',
		'hide_players' => '',
	), $params ) );

	$tracks = empty( $tracks ) ? '' : explode( ',', $tracks );

	$post = get_post();

	// By default, when the shortcode tries to get the tracklisting of any discography item, should be
	// restricted to only published discographies.
	// However, when the discography itself shows its own tracklisting, it should be allowed to do so,
	// no matter what its post status may be.
	$args = array(
		'post_type'       => 'cpt_discography',
		'post_status'     => 'publish',
		'posts_per_page'  => '1'
	);

	if ( empty( $id ) and empty( $slug ) ) {
		$args['p'] = $post->ID;

		// We are showing the current post's tracklisting (since we didn't get any parameters),
		// so we need to make sure we can retrieve it whatever its post status might be.
		$args['post_status'] = 'any';
	} elseif ( !empty( $id ) and $id > 0 ) {
		$args['p'] = $id;

		// Check if the current post's ID matches what was passed.
		// If so, we need to make sure we can retrieve it whatever its post status might be.
		if ( $post->ID == $args['p'] ) {
			$args['post_status'] = 'any';
		}
	} elseif ( !empty( $slug ) ) {
		$args['name'] = sanitize_title_with_dashes( $slug, '', 'save' );

		// Check if the current post's slug matches what was passed.
		// If so, we need to make sure we can retrieve it whatever its post status might be.
		if ( $post->post_name == $args['name'] ) {
			$args['post_status'] = 'any';
		}
	}

	$q = new WP_Query( $args );

	if( $q->have_posts() ) {
		while($q->have_posts()) {
			$q->the_post();

			$post_id = get_the_ID();
			$fields  = get_post_meta( $post_id, 'ci_cpt_discography_tracks', true );

			ob_start();

			if ( !empty( $fields ) ) {
				$track_num = 0; // Helps count songs even if skipped.
				$outputted = 0; // Helps count actually outputted songs. Used with 'limit' parameter.
				?>
				<ul class="tracklisting">
					<?php foreach( $fields as $field ): ?>
						<?php
							$track_num++;
							$track_id = $post_id . '_' . $track_num;

							if ( is_array( $tracks ) and !in_array( $track_num, $tracks ) ) {
								continue;
							}

							preg_match('#^http[s|]://soundcloud\.com.*#', $field['play_url'], $soundcloud_url);
						?>
						<li class="group track" id="player-<?php echo $track_id; ?>">
							<?php if ( empty( $hide_players ) ): ?>
								<?php if( !empty( $soundcloud_url ) ): ?>
									<a href="<?php echo esc_url( $field['play_url'] ); ?>" class="sc-play sm2_link"><i class="fa fa-play"></i></a>
								<?php else: ?>
									<a href="<?php echo esc_url( $field['play_url'] ); ?>" class="sm2_link"><i class="fa fa-play"></i></a>
								<?php endif; ?>
							<?php endif; ?>

							<div class="track-info">
								<p class="item-title-main"><?php echo $field['title']; ?></p>
								<p class="item-byline"><?php echo $field['artist']; ?> &nbsp;</p>

								<?php if ( empty( $hide_buttons ) ): ?>
									<ul class="track-buttons">
										<?php if ( !empty( $field['download_url'] ) ): ?>
											<li>
												<a href="<?php echo esc_url( add_query_arg( 'force_download', $field['download_url'] ) ); ?>" class="btn btn-xs inline-exclude"><i class="fa fa-download"></i>
													<span><?php _e( 'Download track', 'ci_theme' ); ?></span>
												</a>
											</li>
										<?php endif; ?>

										<?php if ( !empty( $field['buy_url'] ) ): ?>
											<li>
												<a href="<?php echo esc_url( $field['buy_url'] ); ?>" class="btn btn-xs"><i class="fa fa-shopping-cart"></i>
													<span><?php _e( 'Buy track', 'ci_theme' ); ?></span>
												</a>
											</li>
										<?php endif; ?>

										<?php if ( !empty( $field['lyrics'] ) ): ?>
											<?php $lyrics_id = sanitize_html_class( $field['title'] . '-lyrics-' . $track_num ); ?>
											<li>
												<a data-rel="prettyPhoto" href="#<?php echo $lyrics_id; ?>" title="<?php echo sprintf( _x( '%s Lyrics', 'song name lyrics', 'ci_theme' ), $field['title'] ); ?>" class="btn btn-xs"><i class="fa fa-book"></i>
													<span><?php _e( 'Lyrics', 'ci_theme' ); ?></span>
												</a>
												<div id="<?php echo $lyrics_id; ?>" class="track-lyrics-hold" style="display:none;"><?php echo wpautop( $field['lyrics'] ); ?></div>
											</li>
										<?php endif; ?>
									</ul>
								<?php endif; ?>

								<span class="track-no"><?php echo $track_num; ?></span>
							</div>

							<?php if( !empty( $soundcloud_url ) ): ?>
								<div id="track<?php echo $track_id; ?>" class="soundcloud-wrap">
									<?php echo wp_oembed_get( esc_url($field['play_url']) ); ?>
								</div>
							<?php endif; ?>

						</li>
						<?php
							if($limit > 0)
							{
								$outputted++;
								if($outputted >= $limit)
									break;
							}
						?>
					<?php endforeach; ?>
				</ul>
				<?php
			}

			$output = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		$output = apply_filters('ci_tracklisting_shortcode_error_msg', __('Cannot show track listings from non-public, non-published posts.', 'ci_theme'));
	}

	return $output;
}
endif;
add_shortcode('tracklisting', 'ci_tracklisting_shortcode');

?>