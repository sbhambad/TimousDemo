<?php
/**
 * Tracklisting Widget.
 */
if( !class_exists('CI_Tracklisting') ):

class CI_Tracklisting extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'ci-tracklisting', // Base ID
			'-= CI Album Tracklisting =-', // Name
			array( 'description' => __( 'Display an album and optionally its track listing.', 'ci_theme' ), ),
			array( /*'width'=> 400, 'height'=> 350*/ )
		);

		add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_custom_css' ) );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$disc_no      = $instance['disc_no'];
		$show_tracks  = $instance['show_tracks'];
		$hide_players = !empty($instance['hide_players']) ? ' hide_players="true" ' : '';

		$parallax       = $instance['parallax'];
		$parallax_speed = $instance['parallax_speed'];

		echo $before_widget;

		$data_speed = !empty($parallax) ? ' data-speed="'.($parallax_speed/10).'" ' : '';

		?><div class="widget-wrap <?php echo $parallax; ?>" <?php echo $data_speed; ?>><?php

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?><div class="container"><?php
		}


		$q = new WP_Query( array(
			'post_type' => 'cpt_discography',
			'p' => $disc_no
		));


		while( $q->have_posts() ): $q->the_post();

			if ( !empty( $title ) ) {
				echo $before_title . $title . $after_title;
			} else {
				echo $before_title . get_the_title() . $after_title;
			}

			$img_cols   = '';
			$track_cols = '';
			if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
				$img_cols   = 'col-sm-4';
				$track_cols = 'col-sm-8';
			}
			?>
			<div class="row">
				<div class="<?php echo $img_cols; ?> col-xs-12">
					<a class="item-hold" href="<?php the_permalink(); ?>">
						<figure class="item-thumb">
							<?php
								the_post_thumbnail( 'ci_square' );
							?>
						</figure>
					</a>

					<a class="btn item-btn" href="<?php the_permalink(); ?>"><?php echo ci_get_read_more_text( get_post_type() ); ?></a>
				</div>

				<?php if ( !empty( $show_tracks ) ): ?>
					<div class="<?php echo $track_cols; ?> col-xs-12">

						<?php echo do_shortcode('[tracklisting id="' . $disc_no . '" hide_buttons="true" ' . $hide_players . ']'); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php
		endwhile;

		wp_reset_postdata();

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?></div><!-- /container --><?php
		}

		?></div><!-- /widget-wrap --><?php

		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']        = sanitize_text_field( $new_instance['title'] );
		$instance['disc_no']      = absint( $new_instance['disc_no'] );
		$instance['show_tracks']  = ci_sanitize_checkbox( $new_instance['show_tracks'] );
		$instance['hide_players'] = ci_sanitize_checkbox( $new_instance['hide_players'] );

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
			'disc_no'           => '',
			'show_tracks'       => 'on',
			'hide_players'      => '',
			'no_bottom_margin'  => '',
			'color'             => '',
			'background_color'  => '',
			'background_image'  => '',
			'background_repeat' => 'repeat',
			'parallax'          => '',
			'parallax_speed'    => 4,
		) );

		$title        = $instance['title'];
		$disc_no      = $instance['disc_no'];
		$showtracks   = $instance['show_tracks'];
		$hide_players = $instance['hide_players'];

		$no_bottom_margin = $instance['no_bottom_margin'];

		$color             = $instance['color'];
		$background_color  = $instance['background_color'];
		$background_image  = $instance['background_image'];
		$background_repeat = $instance['background_repeat'];
		$parallax          = $instance['parallax'];
		$parallax_speed    = $instance['parallax_speed'];

		echo '<p><label for="'.$this->get_field_id('title').'">' . __('Title (optional):','ci_theme') . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" class="widefat" /></p>';
		?>

		<p>
			<label for="<?php echo $this->get_field_id('disco_no'); ?>"><?php _e( 'Select Discography Item:','ci_theme' ); ?></label>
			<?php wp_dropdown_posts( array(
				'post_type' => 'cpt_discography',
				'selected'  => $disc_no,
				'class'     => 'widefat'
			), $this->get_field_name( 'disc_no' ) ); ?>
		</p>
		<p><label><input type="checkbox" name="<?php echo $this->get_field_name('show_tracks'); ?>" id="<?php echo $this->get_field_id('show_tracks'); ?>" value="on" <?php checked($showtracks, 'on'); ?> /><?php _e('Show track listing.', 'ci_theme'); ?></label></p>
		<p><label><input type="checkbox" name="<?php echo $this->get_field_name('hide_players'); ?>" id="<?php echo $this->get_field_id('hide_players'); ?>" value="on" <?php checked($hide_players, 'on'); ?> /><?php _e('Hide player buttons.', 'ci_theme'); ?></label></p>
		<p><?php _e('Please note that the "Show track listing" option above, only works for discography items that have their tracks registered through the <strong>Discography Settings - Track details</strong> pane.', 'ci_theme'); ?></p>


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

} // class CI_Tracklisting

register_widget('CI_Tracklisting');

endif;
?>
