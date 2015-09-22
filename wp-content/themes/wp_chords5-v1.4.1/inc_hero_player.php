<?php
	$autoplay = '';
	$current_track = '';

	$pid    = intval( ci_setting( 'music_player_post_id' ) );
	$s_url  = esc_url( ci_setting( 'music_player_stream_url' ) );
	$s_name = ci_setting( 'music_player_stream_name' );

	$tracklisting = get_post_meta( $pid, 'ci_cpt_discography_tracks', true );
	if ( empty( $tracklisting ) || !is_array( $tracklisting ) || count( $tracklisting ) < 1 ) {
		$tracklisting = false;
	}

	if ( ci_setting('music_player_autoplay') == 'on' ) {
		$autoplay = " autoplay";
	}

	if ( ci_setting('music_player_display_current_track') == 'on' ) {
		$current_track = " ci-current-track";
	}
?>
<?php if ( ! empty( $tracklisting ) || ! empty( $s_url ) ) : ?>
	<div class="hero-player">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="ci-soundplayer <?php echo !empty( $s_url ) ? 'ci-streaming ' : ''; echo $autoplay . $current_track; ?>">
						<div class="ci-soundplayer-controls">
							<a class="ci-soundplayer-prev" href="#"><i class="fa fa-step-backward"></i></a>
							<a class="ci-soundplayer-play" href="#"><i class="fa fa-play"></i></a>
							<a class="ci-soundplayer-next" href="#"><i class="fa fa-step-forward"></i></a>
						</div>

						<div class="ci-soundplayer-meta">
							<span class="track-title"></span>
							<div class="track-bar">
								<div class="progress-bar"></div>
								<div class="load-bar"></div>
							</div>
							<span class="track-position">00:00</span>
						</div>

						<ol class="ci-soundplayer-tracklist">
							<?php if( !empty( $s_url ) ): ?>
								<li><a class="inline-exclude" href="<?php echo $s_url; ?>"><?php echo $s_name; ?></a></li>
							<?php else: ?>
								<?php
									foreach( $tracklisting as $track ) {
										preg_match('#^http[s|]://soundcloud\.com.*#', $track['play_url'], $soundcloud_url);
										if( !empty($soundcloud_url) ) {
											continue;
										}
										?><li><a class="inline-exclude" href="<?php echo esc_url($track['play_url']); ?>"><?php echo $track['title'] . ' - ' . $track['artist']; ?></a></li><?php
									}
								?>
							<?php endif; ?>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
