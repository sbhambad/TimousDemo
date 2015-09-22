<?php 
if( !class_exists('CI_Latest_Posts') ):
class CI_Latest_Posts extends WP_Widget {

	function __construct() {
		$widget_ops  = array( 'description' => __( 'Displays a number of the latest (or random) posts from a specific post type.', 'ci_theme' ) );
		$control_ops = array( /*'width' => 300, 'height' => 400*/ );
		parent::__construct( 'ci-latest-posts', __( '-= CI Latest Posts =-', 'ci_theme' ), $widget_ops, $control_ops );

		add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_custom_css' ) );
	}


	function widget($args, $instance) {

		extract($args);
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$post_type      = $instance['post_type'];
		$random         = $instance['random'];
		$count          = $instance['count'];
		$post_meta      = $instance['post_meta'];
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

		$args = array(
			'post_type'      => $post_type,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'posts_per_page' => $count
		);

		if ( 'on' == $random ) {
			$args['orderby'] = 'rand';
			unset( $args['order'] );
		}

		$q = new WP_Query( $args );

		?><div class="row"><?php

		while ( $q->have_posts() ) {
			$q->the_post();

			?>
			<div class="<?php echo $item_classes; ?>">
				<div class="item <?php echo $post_type . ' ' . $full_width_class; ?>">
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

					<?php if( 'on' == $post_meta ): ?>
						<?php get_template_part( 'widget-meta', get_post_type() ); ?>
					<?php else: ?>
						<a class="btn item-btn" href="<?php the_permalink(); ?>"><?php echo ci_get_read_more_text( get_post_type() ); ?></a>
					<?php endif; ?>

				</div>
			</div>
			<?php
		}

		wp_reset_postdata();

		?></div><!-- /row --><?php

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?></div><!-- /container --><?php
		}

		?></div><!-- /widget-wrap --><?php

		echo $after_widget;

	} // widget

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['post_type'] = sanitize_key( $new_instance['post_type'] );
		$instance['random']    = ci_sanitize_checkbox( $new_instance['random'] );
		$instance['count']     = intval( $new_instance['count'] );
		$instance['post_meta'] = ci_sanitize_checkbox( $new_instance['post_meta'] );

		$instance['columns']           = intval( $new_instance['columns'] );
		$instance['no_bottom_margin']  = ci_sanitize_checkbox( $new_instance['no_bottom_margin'] );
		$instance['color']             = ci_sanitize_hex_color( $new_instance['color'] );
		$instance['background_color']  = ci_sanitize_hex_color( $new_instance['background_color'] );
		$instance['background_image']  = esc_url_raw( $new_instance['background_image'] );
		$instance['background_repeat'] = in_array( $new_instance['background_repeat'], array( 'repeat', 'no-repeat', 'repeat-x', 'repeat-y' ) ) ? $new_instance['background_repeat'] : 'repeat';
		$instance['parallax']          = ci_sanitize_checkbox( $new_instance['parallax'], 'parallax' );
		$instance['parallax_speed']    = round( floatval( $new_instance['parallax_speed'] ), 1 );

		return $instance;
	} // save

	function form($instance) {

		$instance = wp_parse_args( (array) $instance, array(
			'title'             => '',
			'post_type'         => 'post',
			'random'            => '',
			'count'             => 1,
			'post_meta'         => '',
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
		$post_type = $instance['post_type'];
		$random    = $instance['random'];
		$count     = $instance['count'];
		$post_meta = $instance['post_meta'];

		$columns          = $instance['columns'];
		$no_bottom_margin = $instance['no_bottom_margin'];

		$color             = $instance['color'];
		$background_color  = $instance['background_color'];
		$background_image  = $instance['background_image'];
		$background_repeat = $instance['background_repeat'];
		$parallax          = $instance['parallax'];
		$parallax_speed    = $instance['parallax_speed'];

		$types = get_post_types( $args = array(
			'public' => true
		), 'objects' );

		unset($types['attachment']);

		echo '<p><label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Title', 'ci_theme' ) . '</label><input id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . esc_attr( $title ) . '" class="widefat" /></p>';
		echo '<p><label for="' . $this->get_field_id( 'post_type' ) . '">' . __( 'Select a post type to display the latest post from', 'ci_theme' ) . '</label>';
		?>
		<select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
			<?php foreach ( $types as $key => $type ): ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $post_type, $key ); ?>>
					<?php echo $type->labels->name; ?>
				</option>
			<?php endforeach; ?>
		</select></p>

		<p><label for="<?php echo $this->get_field_id('random'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('random'); ?>" id="<?php echo $this->get_field_id('random'); ?>" value="on" <?php checked($random, 'on');?> /><?php _e('Show random posts.', 'ci_theme'); ?></label></p>
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of posts to show:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" min="1" step="1" value="<?php echo esc_attr($count); ?>" class="widefat" /></p>

		<p><label><input type="checkbox" name="<?php echo $this->get_field_name('post_meta'); ?>" value="on" <?php checked( $post_meta, 'on' ); ?> /><?php _e('Show metadata (where available).', 'ci_theme'); ?></label></p>

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

} // class


register_widget('CI_Latest_Posts');

endif; // class_exists
?>
