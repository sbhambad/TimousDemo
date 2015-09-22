<?php global $ci, $ci_defaults, $load_defaults; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_color_options', 20);
	if( !function_exists('ci_add_tab_color_options') ):
		function ci_add_tab_color_options($tabs) 
		{ 
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('Appearance Options', 'ci_theme');
			return $tabs; 
		}
	endif;

	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );
	load_panel_snippet('color_scheme');
	load_panel_snippet('custom_background');

	$ci_defaults['layout_site']              = '';
	$ci_defaults['default_header_bg']        = ''; // Holds the URL of the image file to use as header background
	$ci_defaults['default_header_bg_hidden'] = ''; // Holds the ID of the image file to use as header background
	$ci_defaults['thick_border']             = '';

	add_filter('body_class','ci_theme_thick_border_class');
	if( !function_exists('ci_theme_thick_border_class')):
	function ci_theme_thick_border_class($classes) {
		if ( ci_setting( 'thick_border' ) != '' ) {
			$classes[] = ci_setting( 'thick_border' );
		}

		return $classes;
	}
	endif;

?>
<?php else: ?>

	<fieldset class="set">
		<p class="guide"><?php _e( 'Set the default layout of your website. This may be overridden by individual templates and/or posts.', 'ci_theme' ); ?></p>
		<?php
			$options = array(
				''    => __( 'Default - Sidebar on the right', 'ci_theme' ),
				'alt' => __( 'Alternative - Sidebar on the left', 'ci_theme' ),
			);
			ci_panel_dropdown( 'layout_site', $options, __( 'Site layout', 'ci_theme' ) );
		?>
	</fieldset>

	<?php load_panel_snippet('color_scheme'); ?>

	<fieldset class="set">
		<legend><?php _e('Header Display', 'ci_theme'); ?></legend>
		<p class="guide"><?php _e('Upload or select an image to be used as the default header background on your header. This will be displayed in all your inner pages except from the frontpage template. For best results, use a high resolution image, at least 1920x160 pixels in size.', 'ci_theme'); ?></p>
		<?php ci_panel_upload_image('default_header_bg', __('Upload an image:', 'ci_theme')); ?>
		<input type="hidden" class="uploaded-id" name="<?php echo THEME_OPTIONS; ?>[default_header_bg_hidden]" value="<?php echo esc_attr( $ci['default_header_bg_hidden'] ); ?>"/>
	</fieldset>

	<fieldset class="set">
		<legend><?php _e('Surrounding border', 'ci_theme'); ?></legend>
		<p class="guide"><?php _e('You can enable/disable the thick border that surrounds the website.', 'ci_theme'); ?></p>
		<?php
			$options = array(
				''         => __( 'Show border', 'ci_theme' ),
				'noborder' => __( "Don't show border", 'ci_theme' ),
			);
			ci_panel_dropdown( 'thick_border', $options, __( 'Surrounding border:', 'ci_theme' ) );
		?>
	</fieldset>

	<?php load_panel_snippet('custom_background'); ?>

<?php endif; ?>