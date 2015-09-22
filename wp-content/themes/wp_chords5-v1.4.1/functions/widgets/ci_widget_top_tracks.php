<?php
/**
 * Top Tracks Widget.
 */
if( !class_exists('CI_Top_Tracks') ):

class CI_Top_Tracks extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'ci-top-tracks', // Base ID
			'-= CI Top Tracks =-', // Name
			array( 'description' => __( 'Display a list of tracks.', 'ci_theme' ), 'classes' => 'ci_top_tracks_widget'),
			array( /*'width'=> 400 , 'height'=> 350*/ )
		);

		add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_custom_css' ) );
	}

	public function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$tracks = is_array($instance['tracks']) ? $instance['tracks'] : array();
		$parallax       = $instance['parallax'];
		$parallax_speed = $instance['parallax_speed'];


		if( empty( $tracks ) )
			return;


		echo $before_widget;

		$data_speed = !empty($parallax) ? ' data-speed="'.($parallax_speed/10).'" ' : '';

		?><div class="widget-wrap <?php echo $parallax; ?>" <?php echo $data_speed; ?>><?php

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?><div class="container"><?php
		}

		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}


		$track_num = 0; // Helps count songs even if skipped.
		?>
		<div class="row">
			<div class="col-xs-12">
				<ul class="tracklisting tracklisting-top">
					<?php foreach( $tracks as $track ): ?>
						<?php
							$track = (array)$track;
							$track_num++;
							$track_id = $widget_id . '_' . $track_num;
						?>
						<li class="group track" id="player-<?php echo $track_id; ?>">
							<?php if ( empty( $hide_players ) ): ?>
								<a href="<?php echo esc_url( $track['url'] ); ?>" class="sm2_link"><i class="fa fa-play"></i></a>
							<?php endif; ?>

							<div class="track-info">
								<p class="item-title-main"><?php echo $track['title']; ?></p>
								<p class="item-byline"><?php echo $track['subtitle']; ?> &nbsp;</p>

								<span class="track-no"><?php echo $track_num; ?></span>
							</div>

						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div><!-- /row -->
		<?php

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?></div><!-- /container --><?php
		}

		?></div><!-- /widget-wrap --><?php

		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		$titles    = $new_instance['track_titles'];
		$subtitles = $new_instance['track_subtitles'];
		$urls      = $new_instance['track_urls'];

		$count  = max( count( $titles ), count( $subtitles ), count( $urls ) );
		$tracks = array();
		for ( $i = 0; $i < $count; $i++ ) {
			if ( !empty( $titles[$i] ) && !empty( $subtitles[$i] ) && !empty( $urls[$i] ) ) {
				$tracks[$i]['title']    = esc_html( $titles[$i] );
				$tracks[$i]['subtitle'] = esc_html( $subtitles[$i] );
				$tracks[$i]['url']      = esc_url_raw( $urls[$i] );
			}
		}
		$instance['tracks'] = $tracks;

		$instance['no_bottom_margin']  = ci_sanitize_checkbox( $new_instance['no_bottom_margin'] );
		$instance['color']             = ci_sanitize_hex_color( $new_instance['color'] );
		$instance['background_color']  = ci_sanitize_hex_color( $new_instance['background_color'] );
		$instance['background_image']  = esc_url_raw( $new_instance['background_image'] );
		$instance['background_repeat'] = in_array( $new_instance['background_repeat'], array( 'repeat', 'no-repeat', 'repeat-x', 'repeat-y' ) ) ? $new_instance['background_repeat'] : 'repeat';
		$instance['parallax']          = ci_sanitize_checkbox( $new_instance['parallax'], 'parallax' );
		$instance['parallax_speed']    = round( floatval( $new_instance['parallax_speed'] ), 1 );

		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args( (array) $instance, array(
			'title'             => '',
			'tracks'            => array(),
			'no_bottom_margin'  => '',
			'color'             => '',
			'background_color'  => '',
			'background_image'  => '',
			'background_repeat' => 'repeat',
			'parallax'          => '',
			'parallax_speed'    => 4,
		) );

		$title  = $instance['title'];
		$tracks = is_array( $instance['tracks'] ) ? $instance['tracks'] : array();

		$no_bottom_margin  = $instance['no_bottom_margin'];
		$color             = $instance['color'];
		$background_color  = $instance['background_color'];
		$background_image  = $instance['background_image'];
		$background_repeat = $instance['background_repeat'];
		$parallax          = $instance['parallax'];
		$parallax_speed    = $instance['parallax_speed'];

		$track_title_name    = $this->get_field_name( 'track_titles' ) . '[]';
		$track_subtitle_name = $this->get_field_name( 'track_subtitles' ) . '[]';
		$track_url_name      = $this->get_field_name( 'track_urls' ) . '[]';

		echo '<p><label for="'.$this->get_field_id('title').'">' . __('Title:','ci_theme') . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" class="widefat" /></p>';

		?>
		<p><?php _e('Add as many items as you want by pressing the "Add Field" button. Remove any item by selecting "Remove me".', 'ci_theme'); ?></p>
		<fieldset class="ci-repeating-fields ci-top-tracks-widget-admin">
			<div class="inside">
				<?php
					foreach( $tracks as $track )
					{
						$track = (array)$track;
						?>
						<div class="post-field">
							<p><label><?php _e( 'Track Title:', 'ci_theme' ); ?> <input type="text" class="widefat" name="<?php echo $track_title_name; ?>" value="<?php echo esc_attr( $track['title'] ); ?>" /></label></p>
							<p><label><?php _e( 'Track Subtitle:', 'ci_theme' ); ?> <input type="text" class="widefat" name="<?php echo $track_subtitle_name; ?>" value="<?php echo esc_attr( $track['subtitle'] ); ?>" /></label></p>
							<p><label><?php _e( 'Track URL:', 'ci_theme' ); ?> <input type="text" class="widefat uploaded" name="<?php echo $track_url_name; ?>" value="<?php echo esc_url( $track['url'] ); ?>" /><a href="#" class="button ci-upload"><?php _e('Upload', 'ci_theme'); ?></a></label></p>
							<p class="ci-repeating-remove-action"><a href="#" class="button ci-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php _e( 'Remove me', 'ci_theme' ); ?></a></p>
						</div>
						<?php
					}
				?>
				<?php
				//
				// Add an empty and hidden set for jQuery
				//
				?>
				<div class="post-field field-prototype" style="display: none;">
					<p><label><?php _e( 'Track Title:', 'ci_theme' ); ?> <input type="text" class="widefat" name="<?php echo $track_title_name; ?>" value="" /></label></p>
					<p><label><?php _e( 'Track Subtitle:', 'ci_theme' ); ?> <input type="text" class="widefat" name="<?php echo $track_subtitle_name; ?>" value="" /></label></p>
					<p><label><?php _e( 'Track URL:', 'ci_theme' ); ?> <input type="text" class="widefat uploaded" name="<?php echo $track_url_name; ?>" value="" /><a href="#" class="button ci-upload"><?php _e('Upload', 'ci_theme'); ?></a></label></p>
					<p class="ci-repeating-remove-action"><a href="#" class="button ci-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php _e( 'Remove me', 'ci_theme' ); ?></a></p>
				</div>

			</div>
			<a href="#" class="ci-repeating-add-field button"><i class="dashicons dashicons-plus-alt"></i><?php _e('Add Field', 'ci_theme'); ?></a>
		</fieldset>


		<p><label for="<?php echo $this->get_field_id('no_bottom_margin'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('no_bottom_margin'); ?>" id="<?php echo $this->get_field_id('no_bottom_margin'); ?>" value="on" <?php checked($no_bottom_margin, 'on'); ?> /><?php _e('Reduce bottom margin.', 'ci_theme'); ?></label></p>

		<fieldset class="ci-collapsible">
			<legend><?php _e('Custom Colors', 'ci_theme'); ?> <i class="dashicons dashicons-arrow-down"></i></legend>
			<div class="elements">
				<p><label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Foreground Color:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text" value="<?php echo esc_attr($color); ?>" class="colorpckr widefat" /></p>
				<p><label for="<?php echo $this->get_field_id('background_color'); ?>"><?php _e('Background Color:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('background_color'); ?>" name="<?php echo $this->get_field_name('background_color'); ?>" type="text" value="<?php echo esc_attr($background_color); ?>" class="colorpckr widefat" /></p>
				<p class="ci-collapsible-media"><label for="<?php echo $this->get_field_id('background_image'); ?>"><?php _e('Background Image:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('background_image'); ?>" name="<?php echo $this->get_field_name('background_image'); ?>" type="text" value="<?php echo esc_attr($background_image); ?>" class="uploaded widefat" /><a href="#" class="button ci-upload"><?php _e('Upload', 'ci_theme'); ?></a></p>
				<p>
					<label for="<?php echo $this->get_field_id('background_repeat'); ?>"><?php _e('Background Repeat:', 'ci_theme'); ?></label>
					<select id="<?php echo $this->get_field_id('background_repeat'); ?>" class="widefat" name="<?php echo $this->get_field_name('background_repeat'); ?>">
						<option value="repeat" <?php selected('repeat', $background_repeat); ?>><?php _e('Repeat', 'ci_theme'); ?></option>
						<option value="repeat-x" <?php selected('repeat-x', $background_repeat); ?>><?php _e('Repeat Horizontally', 'ci_theme'); ?></option>
						<option value="repeat-y" <?php selected('repeat-y', $background_repeat); ?>><?php _e('Repeat Vertically', 'ci_theme'); ?></option>
						<option value="no-repeat" <?php selected('no-repeat', $background_repeat); ?>><?php _e('No Repeat', 'ci_theme'); ?></option>
					</select>
				</p>

				<p><label for="<?php echo $this->get_field_id('parallax'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('parallax'); ?>" id="<?php echo $this->get_field_id('parallax'); ?>" value="parallax" <?php checked($parallax, 'parallax');?> /><?php _e('Parallax effect (requires a background image).', 'ci_theme'); ?></label></p>
				<p><label for="<?php echo $this->get_field_id('parallax_speed'); ?>"><?php _e('Parallax speed (1-8):', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('parallax_speed'); ?>" name="<?php echo $this->get_field_name('parallax_speed'); ?>" type="number" min="1" max="8" step="1" value="<?php echo esc_attr($parallax_speed); ?>" class="widefat" /></p>

			</div>
		</fieldset>
		<?php

	} // form

	static function _enqueue_admin_scripts() {
		global $pagenow;

		if ( in_array( $pagenow, array( 'widgets.php', 'customize.php' ) ) ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_media();
			ci_enqueue_media_manager_scripts();
		}
	}


	function enqueue_custom_css() {
		$settings = $this->get_settings();

		if( empty( $settings ) )
			return;

		foreach( $settings as $instance_id => $instance ) {
			$id = $this->id_base.'-'.$instance_id;

			if ( !is_active_widget( false, $id, $this->id_base ) ) {
				continue;
			}

			$sidebar_id      = false; // Holds the sidebar id that the widget is assigned to.
			$sidebar_widgets = wp_get_sidebars_widgets();
			if ( !empty( $sidebar_widgets ) ) {
				foreach ( $sidebar_widgets as $sidebar => $widgets ) {
					// We need to check $widgets for emptiness due to https://core.trac.wordpress.org/ticket/14876
					if ( !empty( $widgets ) && array_search( $id, $widgets ) !== false ) {
						$sidebar_id = $sidebar;
					}
				}
			}

			$no_bottom_margin  = $instance['no_bottom_margin'];
			$color             = $instance['color'];
			$background_color  = $instance['background_color'];
			$background_image  = $instance['background_image'];
			$background_repeat = $instance['background_repeat'];

			$css = '';
			$padding_css = '';

			if( !empty( $color ) ) {
				$css .= 'color: ' . $color . '; ';
			}
			if( !empty( $background_color ) ) {
				$css .= 'background-color: ' . $background_color . '; ';
			}
			if( !empty( $background_image ) ) {
				$css .= 'background-image: url(' . esc_url($background_image) . ');';
				$css .= 'background-repeat: ' . $background_repeat . ';';
			}

			if( !empty( $background_color ) or !empty( $background_image ) ) {
				if( !in_array( $sidebar_id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
					$padding_css .= 'padding: 25px 15px; ';
				}
			}

			if( 'on' == $no_bottom_margin && in_array( $sidebar_id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
				$padding_css .= 'padding-bottom: 0; margin-bottom: -30px; ';
			}

			if ( !empty( $css ) || !empty( $padding_css ) ) {
				$css = '#' . $id . ' .widget-wrap { ' . $css . $padding_css . ' } ' . PHP_EOL;
				wp_add_inline_style('ci-style', $css);
			}

		}

	}

} // class CI_Top_Tracks

register_widget('CI_Top_Tracks');

endif;
?>
