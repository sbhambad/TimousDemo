<?php
if( !class_exists('CI_Split_Content_Widget') ):
class CI_Split_Content_Widget extends WP_Widget {

	function __construct() {
		$widget_ops  = array( 'description' => __( 'Displays a selected post from a selected post type on the left, and the featured image on the right.', 'ci_theme' ) );
		$control_ops = array( /*'width' => 300, 'height' => 400*/ );
		parent::__construct( 'ci-split-content', $name = __( '-= CI Split Content =-', 'ci_theme' ), $widget_ops, $control_ops );

		add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_custom_css' ) );

		if ( is_admin() === true ) {
			add_action( 'wp_ajax_ci_widget_split_content_ajax_get_posts', 'CI_Split_Content_Widget::_ajax_get_posts' );
		}

	}


	function widget( $args, $instance ) {
		extract( $args );

		$title          = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$ci_post_id     = $instance['postid'];
		$post_type_name = $instance['post_type_name'];
		$parallax       = $instance['parallax'];
		$parallax_speed = $instance['parallax_speed'];
		$image_left     = $instance['image_left'];

		if( empty($ci_post_id) or empty($post_type_name) )
			return;

		$q = new WP_Query( array(
			'post_type' => $post_type_name,
			'p'         => $ci_post_id
		) );

		echo $before_widget;

		$data_speed = !empty($parallax) ? ' data-speed="'.($parallax_speed/10).'" ' : '';

		?><div class="widget-wrap <?php echo $parallax; ?>" <?php echo $data_speed; ?>><?php

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?><div class="container"><?php
		}

		?>
		<div class="row">
			<?php while ( $q->have_posts() ): $q->the_post(); ?>
				<div class="col-md-5 <?php echo empty( $image_left ) ? 'col-md-push-7' : ''; ?>">
					<?php the_post_thumbnail(); ?>
				</div>

				<div class="col-md-7 <?php echo empty( $image_left ) ? 'col-md-pull-5' : ''; ?>">
					<?php
						if ( $title ) {
							echo '<h3>' . $title . '</h3>';
						} else {
							the_title('<h3>', '</h3>');
						}

						the_excerpt();
					?>
					<a class="btn btn-lg" href="<?php the_permalink(); ?>"><?php echo ci_get_read_more_text( get_post_type() ); ?></a>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div><!-- /row -->
		<?php

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?></div><!-- /container --><?php
		}

		?></div><!-- /widget-wrap --><?php

		echo $after_widget;

	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title']          = sanitize_text_field( $new_instance['title'] );
		$instance['post_type_name'] = sanitize_key( $new_instance['post_type_name'] );
		$instance['postid']         = absint( $new_instance['postid'] );
		$instance['image_left']     = ci_sanitize_checkbox( $new_instance['image_left'] );

		$instance['no_bottom_margin']  = ci_sanitize_checkbox( $new_instance['no_bottom_margin'] );

		$instance['color']             = ci_sanitize_hex_color( $new_instance['color'] );
		$instance['background_color']  = ci_sanitize_hex_color( $new_instance['background_color'] );
		$instance['background_image']  = esc_url_raw( $new_instance['background_image'] );
		$instance['background_repeat'] = in_array( $new_instance['background_repeat'], array( 'repeat', 'no-repeat', 'repeat-x', 'repeat-y', ) ) ? $new_instance['background_repeat'] : 'repeat';
		$instance['parallax']          = ci_sanitize_checkbox( $new_instance['parallax'], 'parallax' );
		$instance['parallax_speed']    = round( floatval( $new_instance['parallax_speed'] ), 1 );

		return $instance;
	}

	function form($instance){

		$instance = wp_parse_args( (array) $instance, array(
			'title'             => '',
			'post_type_name'    => 'post',
			'postid'            => '',
			'image_left'        => '',
			'no_bottom_margin'  => '',
			'color'             => '',
			'background_color'  => '',
			'background_image'  => '',
			'background_repeat' => 'repeat',
			'parallax'          => '',
			'parallax_speed'    => 4,
		) );

		$title            = $instance['title'];
		$post_type_name   = $instance['post_type_name'];
		$post_id          = $instance['postid'];
		$image_left       = $instance['image_left'];

		$no_bottom_margin = $instance['no_bottom_margin'];

		$color             = $instance['color'];
		$background_color  = $instance['background_color'];
		$background_image  = $instance['background_image'];
		$background_repeat = $instance['background_repeat'];
		$parallax          = $instance['parallax'];
		$parallax_speed    = $instance['parallax_speed'];

		?><p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" class="widefat" /></p><?php

		$post_types = get_post_types( array('public' => true), 'objects' );
		unset($post_types['attachment'], $post_types['cpt_slider'], $post_types['cpt_testimonial']);

		?><p><label for="<?php echo $this->get_field_id('post_type_name'); ?>"><?php _e('Show posts from this post type:', 'ci_theme'); ?></label></p><?php
		?><select name="<?php echo $this->get_field_name('post_type_name'); ?>" id="<?php echo $this->get_field_id('post_type_name'); ?>" class="widefat" ><?php
			foreach( $post_types as $key => $pt )
			{
				?><option value="<?php echo esc_attr($key); ?>" <?php selected($key, $post_type_name); ?>><?php echo $pt->labels->name; ?></option><?php
			}
		?></select> <img src="<?php echo get_child_or_parent_file_uri('/panel/img/ajax-loader-16x16.gif'); ?>" class="loading_posts" style="display: none;"><?php
		?><p></p><?php
		?><p><label for="<?php echo $this->get_field_id('postid'); ?>"><?php _e('Select a post to show:', 'ci_theme'); ?></label></p><?php
		?><p class="ci_widget_post_type_posts_dropdown"><?php

		wp_dropdown_posts( array(
			'post_type'            => $post_type_name,
			'show_option_none'     => '&nbsp;',
			'selected'             => $post_id,
			'class'                => 'widefat',
			'select_even_if_empty' => true,
		), $this->get_field_name( 'postid' ) );

		?></p><?php
		?><p><small><?php _e( 'For better control of the output, write a custom excerpt on the post you are about to show.', 'ci_theme' ); ?></small></p><?php

		?><p><label for="<?php echo $this->get_field_id('image_left'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('image_left'); ?>" id="<?php echo $this->get_field_id('image_left'); ?>" value="on" <?php checked($image_left, 'on'); ?> /><?php _e('Image on the left.', 'ci_theme'); ?></label></p><?php

		?><p><label for="<?php echo $this->get_field_id('no_bottom_margin'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('no_bottom_margin'); ?>" id="<?php echo $this->get_field_id('no_bottom_margin'); ?>" value="on" <?php checked($no_bottom_margin, 'on'); ?> /><?php _e('Reduce bottom margin.', 'ci_theme'); ?></label></p><?php

		?>
		<fieldset class="ci-collapsible">
			<legend><?php _e('Custom Colors', 'ci_theme'); ?> <i class="dashicons dashicons-arrow-down"></i></legend>
			<div class="elements">
				<p><label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Foreground Color:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text" value="<?php echo esc_attr($color); ?>" class="colorpckr widefat" /></p>
				<p><label for="<?php echo $this->get_field_id('background_color'); ?>"><?php _e('Background Color:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('background_color'); ?>" name="<?php echo $this->get_field_name('background_color'); ?>" type="text" value="<?php echo esc_attr($background_color); ?>" class="colorpckr widefat" /></p>
				<p class="ci-collapsible-media"><label for="<?php echo $this->get_field_id('background_image'); ?>"><?php _e('Background Image:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('background_image'); ?>" name="<?php echo $this->get_field_name('background_image'); ?>" type="text" value="<?php echo esc_attr($background_image); ?>" class="uploaded widefat" /><a href="#" class="button ci-upload"><?php _e('Upload', 'ci_theme'); ?></a></p>
				<p>
					<label for="<?php echo $this->get_field_id('background_repeat'); ?>"><?php _e('Background Repeat:', 'ci_theme'); ?></label>
					<select id="<?php echo $this->get_field_id('background_repeat'); ?>" name="<?php echo $this->get_field_name('background_repeat'); ?>">
						<option value="repeat" <?php selected('repeat', $background_repeat); ?>><?php _e('Repeat', 'ci_theme'); ?></option>
						<option value="repeat-x" <?php selected('repeat-x', $background_repeat); ?>><?php _e('Repeat Horizontally', 'ci_theme'); ?></option>
						<option value="repeat-y" <?php selected('repeat-y', $background_repeat); ?>><?php _e('Repeat Vertically', 'ci_theme'); ?></option>
						<option value="no-repeat" <?php selected('no-repeat', $background_repeat); ?>><?php _e('No Repeat', 'ci_theme'); ?></option>
					</select>
				</p>

				<p><label for="<?php echo $this->get_field_id('parallax'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('parallax'); ?>" id="<?php echo $this->get_field_id('parallax'); ?>" value="parallax" <?php checked($parallax, 'parallax');?> /><?php _e('Parallax effect (requires a background image).', 'ci_theme'); ?></label></p>
				<p><label for="<?php echo $this->get_field_id('parallax_speed'); ?>"><?php _e('Parallax speed (1-8):', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('parallax_speed'); ?>" name="<?php echo $this->get_field_name('parallax_speed'); ?>" type="number" min="1" max="10" step="1" value="<?php echo esc_attr($parallax_speed); ?>" class="widefat" /></p>

			</div>
		</fieldset>
		<?php
	}

	static function _enqueue_admin_scripts() {
		global $pagenow;

		if ( in_array( $pagenow, array( 'widgets.php', 'customize.php' ) ) ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
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

	static function _ajax_get_posts()
	{
		$post_type_name = sanitize_key( $_POST['post_type_name'] );
		$name_field     = esc_attr( $_POST['name_field'] );

		$str = wp_dropdown_posts( array(
			'echo'             => false,
			'post_type'        => $post_type_name,
			'show_option_none' => '&nbsp;',
			'class'            => 'widefat'
		), $name_field );

		echo $str;
		die;
	}

} // class


register_widget('CI_Split_Content_Widget');

endif; // !class_exists
?>
