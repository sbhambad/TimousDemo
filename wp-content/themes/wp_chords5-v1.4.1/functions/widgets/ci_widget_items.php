<?php
if( !class_exists('CI_Items_Widget') ):
class CI_Items_Widget extends WP_Widget {

	function __construct() {
		$widget_ops  = array( 'description' => __( 'Displays a hand-picked selection of posts from a selected post type.', 'ci_theme' ) );
		$control_ops = array( /*'width' => 300, 'height' => 400*/ );
		parent::__construct( 'ci-items', $name = __( '-= CI Items =-', 'ci_theme' ), $widget_ops, $control_ops );

		add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_custom_css' ) );

		if ( is_admin() === true ) {
			add_action( 'wp_ajax_ci_items_widget_post_type_ajax_get_posts', 'CI_Items_Widget::_ajax_get_posts' );
		}
	}

	function widget($args, $instance) {
		extract($args);

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$types          = $instance['post_types'];
		$ids            = $instance['postids'];
		$meta           = $instance['post_meta'];
		$columns        = $instance['columns'];
		$parallax       = $instance['parallax'];
		$parallax_speed = $instance['parallax_speed'];
		$count          = max( count( $types ), count( $ids ) );


		if ( empty($types) or empty($ids) )
			return;

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

		?>
		<div class="row">
			<?php
				for ( $i = 0; $i < $count; $i++ ) {
					$pid       = $ids[ $i ];
					$post_type = $types[ $i ];
					$show_meta = $meta[ $i ];

					$q = new WP_Query( array(
						'post_type' => $post_type,
						'p'         => $pid
					) );

					while( $q->have_posts() ): $q->the_post();
						$thumb_size = 'post-thumbnail';
						switch ( get_post_type() ) {
							case 'product' :
								$thumb_size = 'shop_catalog';
								break;
							case 'cpt_discography' :
								$thumb_size = 'ci_square';
								break;
						}
						?>
						<div class="<?php echo $item_classes; ?>">
							<div class="item <?php echo $post_type . ' ' . $full_width_class; ?>">
								<a class="item-hold" href="<?php the_permalink(); ?>">
									<figure class="item-thumb">
										<?php
											if ( 1 == $columns && in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
												the_post_thumbnail( 'ci_blog_full' );
											} else {
												the_post_thumbnail( $thumb_size );
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
				}
			?>
		</div><!-- /row -->
		<?php

		wp_reset_postdata();

		if ( in_array( $id, array( 'frontpage-widgets', 'inner-sidebar' ) ) ) {
			?></div><!-- /container --><?php
		}

		?></div><!-- /widget-wrap --><?php

		echo $after_widget;

	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Sanitize repeating fields. Remove empty entries.
		$instance['post_types'] = array();
		$instance['postids']    = array();
		$instance['post_meta']  = array();

		$types = $new_instance['post_types'];
		$ids   = $new_instance['postids'];
		$meta  = $new_instance['post_meta'];
		$count = max( count( $types ), count( $ids ) );

		for($i = 0; $i < $count; $i++) {
			if ( !empty( $types[ $i ] ) && !empty( $ids[ $i ] ) ) {
				$instance['post_types'][] = sanitize_key( $types[ $i ] );
				$instance['postids'][]    = absint( $ids[ $i ] );
				$tmp = $meta[ $i ];
				$instance['post_meta'][]  = ci_sanitize_checkbox( $tmp );
			}
		}

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

	function form($instance){

		$instance = wp_parse_args( (array) $instance, array(
			'title'             => '',
			'post_types'        => array(),
			'postids'           => array(),
			'post_meta'         => array(),
			'columns'           => 1,
			'no_bottom_margin'  => '',
			'color'             => '',
			'background_color'  => '',
			'background_image'  => '',
			'background_repeat' => 'repeat',
			'parallax'          => '',
			'parallax_speed'    => 4,
		) );

		$title            = $instance['title'];
		$post_types       = $instance['post_types'];
		$postids          = $instance['postids'];
		$post_meta        = $instance['post_meta'];
		$columns          = $instance['columns'];
		$no_bottom_margin = $instance['no_bottom_margin'];

		$color             = $instance['color'];
		$background_color  = $instance['background_color'];
		$background_image  = $instance['background_image'];
		$background_repeat = $instance['background_repeat'];
		$parallax          = $instance['parallax'];
		$parallax_speed    = $instance['parallax_speed'];

		?><p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" class="widefat" /></p><?php

		$post_types_available = get_post_types( array('public' => true), 'objects' );
		unset($post_types_available['attachment']);

		$posttypes_name = $this->get_field_name('post_types') . '[]';
		$ids_name = $this->get_field_name('postids') . '[]';
		$meta_name = $this->get_field_name('post_meta') . '[]';

		?>
		<p><?php _e('Add as many items as you want by pressing the "Add Field" button. Remove any item by selecting "Remove me".', 'ci_theme'); ?></p>
		<fieldset class="ci-repeating-fields ci-items">
			<div class="inside">
				<?php
					if (!empty($postids) && !empty($post_types))
					{
						$count = max( count($postids), count($post_types) );
						for( $i = 0; $i < $count; $i++ )
						{
							?>
							<div class="post-field">
								<label class="post-field-type"><?php _e( 'Post type:', 'ci_theme' ); ?>
									<select name="<?php echo $posttypes_name; ?>" class="widefat posttype_dropdown">
										<?php
											foreach( $post_types_available as $key => $pt ) {
												?><option value="<?php echo esc_attr($key); ?>" <?php selected($key, $post_types[ $i ]); ?>><?php echo $pt->labels->name; ?></option><?php
											}
										?>
									</select>
								</label>
								<label class="post-field-item"><?php _e( 'Item:', 'ci_theme' ); ?>
									<?php
										wp_dropdown_posts( array(
											'post_type'        => $post_types[ $i ],
											'selected'         => $postids[ $i ],
											'class'            => 'widefat posts_dropdown',
											'show_option_none' => '&nbsp;',
											'select_even_if_empty' => true,
										), $ids_name );
									?>
								</label>
								<p><label><input type="checkbox" name="<?php echo $meta_name; ?>" value="on" <?php checked( $post_meta[ $i ], 'on' ); ?> /><?php _e('Show metadata (where available).', 'ci_theme'); ?></label></p>
								<p class="ci-repeating-remove-action"><a href="#" class="button ci-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php _e( 'Remove me', 'ci_theme' ); ?></a></p>
							</div>
							<?php
						}
					}
				?>
				<?php
				//
				// Add an empty and hidden set for jQuery
				//
				?>
				<div class="post-field field-prototype" style="display: none;">
					<label class="post-field-type"><?php _e( 'Post type:', 'ci_theme' ); ?>
						<select name="<?php echo $posttypes_name; ?>" class="widefat posttype_dropdown">
							<?php
								foreach( $post_types_available as $key => $pt )
								{
									?><option value="<?php echo esc_attr($key); ?>"><?php echo $pt->labels->name; ?></option><?php
								}
							?>
						</select>
					</label>
					<label class="post-field-item"><?php _e( 'Item:', 'ci_theme' ); ?>
						<?php
							wp_dropdown_posts( array(
								'post_type'            => 'post',
								'class'                => 'widefat posts_dropdown',
								'show_option_none'     => '&nbsp;',
								'select_even_if_empty' => true,
							), $ids_name );
						?>
					</label>
					<p><label><input type="checkbox" name="<?php echo $meta_name; ?>" value="on" /><?php _e('Show metadata (where available).', 'ci_theme'); ?></label></p>
					<p class="ci-repeating-remove-action"><a href="#" class="button ci-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php _e( 'Remove me', 'ci_theme' ); ?></a></p>
				</div>
			</div>
			<a href="#" class="ci-repeating-add-field button"><i class="dashicons dashicons-plus-alt"></i><?php _e('Add Field', 'ci_theme'); ?></a>
		</fieldset>

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
	}

	static function _ajax_get_posts()
	{
		$post_type_name = sanitize_key( $_POST['post_type_name'] );
		$name_field     = esc_attr( $_POST['name_field'] );

		$str = wp_dropdown_posts( array(
			'echo'                 => false,
			'post_type'            => $post_type_name,
			'class'                => 'widefat posts_dropdown',
			'show_option_none'     => '&nbsp;',
			'select_even_if_empty' => true,
		), $name_field );

		echo $str;
		die;
	}

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


register_widget('CI_Items_Widget');

endif; // !class_exists
?>
