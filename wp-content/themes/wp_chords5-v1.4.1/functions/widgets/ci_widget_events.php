<?php 
/**
 * Recent Events Widgets.
 */
if( !class_exists('CI_Events') ):

class CI_Events extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'ci-events', // Base ID
			__( '-= CI Upcoming Events =-', 'ci_theme' ), // Name
			array( 'description' => __( 'Display your upcoming events.', 'ci_theme' ), ),
			array( /* 'width' => 300, 'height' => 400 */ )
		);

		add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_custom_css' ) );

	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$events_no      = $instance['events_no'];
		$show_rec       = $instance['show_recurrent'];
		$show_meta      = $instance['show_metadata'];
		$columns        = $instance['columns'];
		$parallax       = $instance['parallax'];
		$parallax_speed = $instance['parallax_speed'];


		$item_classes = '';
		switch( $columns ) {
			case 1:
				$item_classes = 'col-xs-12';
				break;
			case 2:
				$item_classes = 'col-xs-12 col-sm-6';
				break;
			case 3:
				$item_classes = 'col-xs-12 col-sm-6 col-md-4';
				break;
			case 4:
				$item_classes = 'col-xs-12 col-sm-6 col-md-4 col-lg-3';
				break;
			default:
				$item_classes = 'col-xs-12';
				break;
		}

		$full_width_class = 1 == $columns ? ' item-fullwidth ' : '';

		echo $before_widget;

		$data_speed = !empty($parallax) ? ' data-speed="'.($parallax_speed/10).'" ' : '';

		?><div class="widget-wrap <?php echo $parallax; ?>" <?php echo $data_speed; ?>><?php

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?><div class="container"><?php
		}

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		$recurrent_params = array(
			'post_type'      => 'cpt_event',
			'posts_per_page' => -1,
			'meta_key'       => 'ci_cpt_event_recurrence',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'ci_cpt_event_recurrent',
					'value'   => 'enabled',
					'compare' => '='
				)
			)
		);

		$date_params = array(
			'post_type'      => 'cpt_event',
			'posts_per_page' => $events_no,
			'meta_key'       => 'ci_cpt_event_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'ci_cpt_event_date',
					'value'   => date_i18n( 'Y-m-d' ),
					'compare' => '>=',
					'type'    => 'date'
				)
			)
		);

		if($show_rec=='on')
			$latest_events = merge_wp_queries($recurrent_params, $date_params);
		else
			$latest_events = new WP_Query($date_params);

		?><div class="row item-list list-masonry"><?php

		while ( $latest_events->have_posts() ) : $latest_events->the_post();
			?>
			<div class="<?php echo $item_classes; ?>">
				<div class="item cpt_event <?php echo $full_width_class; ?>">
					<a class="item-hold" href="<?php the_permalink(); ?>">
						<figure class="item-thumb">
							<?php
								if ( 1 == $columns && in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
									the_post_thumbnail( 'ci_blog_full' );
								} else {
									the_post_thumbnail();
								}
							?>
						</figure>
					</a>

					<?php get_template_part( 'listing-meta' ); ?>
					<?php if( 'on' == $show_meta ): ?>
						<?php get_template_part( 'widget-meta', get_post_type() ); ?>
					<?php else: ?>
						<a class="btn item-btn" href="<?php the_permalink(); ?>"><?php echo ci_get_read_more_text( get_post_type() ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<?php
		endwhile;
		wp_reset_postdata();

		?></div><!-- /row --><?php

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?></div><!-- /container --><?php
		}

		?></div><!-- /widget-wrap --><?php

		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']          = sanitize_text_field( $new_instance['title'] );
		$instance['events_no']      = absint( $new_instance['events_no'] ) ;
		$instance['show_recurrent'] = ci_sanitize_checkbox( $new_instance['show_recurrent'] );

		$instance['show_metadata']  = ci_sanitize_checkbox( $new_instance['show_metadata'] );

		$instance['columns']           = intval( $new_instance['columns'] );
		$instance['no_bottom_margin']  = ci_sanitize_checkbox( $new_instance['no_bottom_margin'] );
		$instance['color']             = ci_sanitize_hex_color( $new_instance['color'] );
		$instance['background_color']  = ci_sanitize_hex_color( $new_instance['background_color'] );
		$instance['background_image']  = esc_url_raw( $new_instance['background_image'] );
		$instance['background_repeat'] = in_array( $new_instance['background_repeat'], array( 'repeat', 'no-repeat', 'repeat-x', 'repeat-y' ) ) ? $new_instance['background_repeat'] : 'repeat';
		$instance['parallax']          = ci_sanitize_checkbox( $new_instance['parallax'], 'parallax' );
		$instance['parallax_speed']    = round( floatval( $new_instance['parallax_speed'] ), 1 );

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'             => '',
			'events_no'         => 3,
			'show_recurrent'    => '',
			'show_metadata'     => 'on',
			'columns'           => 1,
			'no_bottom_margin'  => '',
			'color'             => '',
			'background_color'  => '',
			'background_image'  => '',
			'background_repeat' => 'repeat',
			'parallax'          => '',
			'parallax_speed'    => 4,
		) );

		$title     = $instance['title'];
		$events_no = $instance['events_no'];
		$show_rec  = $instance['show_recurrent'];
		$show_meta = $instance['show_metadata'];

		$columns          = $instance['columns'];
		$no_bottom_margin = $instance['no_bottom_margin'];

		$color             = $instance['color'];
		$background_color  = $instance['background_color'];
		$background_image  = $instance['background_image'];
		$background_repeat = $instance['background_repeat'];
		$parallax          = $instance['parallax'];
		$parallax_speed    = $instance['parallax_speed'];

		echo '<p><label for="'.$this->get_field_id('title').'">' . __('Title:', 'ci_theme') . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" class="widefat" /></p>';
		?>
		<p><label for="<?php echo $this->get_field_id('events_no'); ?>"><?php _e('Events Number:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('events_no'); ?>" name="<?php echo $this->get_field_name('events_no'); ?>" type="number" min="1" step="1" value="<?php echo esc_attr($events_no); ?>" class="widefat" /></p>
		<p><label for="<?php echo $this->get_field_id('show_recurrent'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('show_recurrent'); ?>" id="<?php echo $this->get_field_id('show_recurrent'); ?>" value="on" <?php checked($show_rec, 'on'); ?> /><?php _e('Show recurring events?', 'ci_theme'); ?></label></p>
		<?php
		echo '<p>' . __("Recurrent events don't count towards the limit you set above. If enabled, the widget will display all recurrent events, and a number of dated events according to the number you set.", 'ci_theme') . '</p>';

		?>
		<p><label for="<?php echo $this->get_field_id('show_metadata'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('show_metadata'); ?>" id="<?php echo $this->get_field_id('show_metadata'); ?>" value="on" <?php checked($show_meta, 'on'); ?> /><?php _e('Show metadata.', 'ci_theme'); ?></label></p>

		<p>
			<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Output Columns:', 'ci_theme'); ?></label>
			<select id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>">
				<option value="1" <?php selected(1, $columns); ?>>1</option>
				<option value="2" <?php selected(2, $columns); ?>>2</option>
				<option value="3" <?php selected(3, $columns); ?>>3</option>
				<option value="4" <?php selected(4, $columns); ?>>4</option>
			</select>
		</p>

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

} // class CI_Events

register_widget('CI_Events');

endif; // !class_exists
?>
