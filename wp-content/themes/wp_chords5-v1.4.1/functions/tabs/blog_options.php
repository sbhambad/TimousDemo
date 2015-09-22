<?php global $ci, $ci_defaults, $load_defaults, $content_width; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_blog_options', 60);
	if( !function_exists('ci_add_tab_blog_options') ):
		function ci_add_tab_blog_options($tabs)
		{
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('Blog Options', 'ci_theme');
			return $tabs;
		}
	endif;

	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );
	$ci_defaults['layout_blog']         = '';
	$ci_defaults['layout_blog_columns'] = '';

?>
<?php else: ?>

	<fieldset class="set">
		<p class="guide"><?php _e('Set the default layout of the Blog Section. This applies to the primary blog section, i.e. the default installation, as well as to the page that you set as the blog page from <em>Settings -> Reading -> Front page displays -> A static page -> Post page</em>.', 'ci_theme'); ?></p>
		<p>
		<?php
			$options = array(
				''          => __( 'Default - With sidebar', 'ci_theme' ),
				'fullwidth' => __( 'Full width - No sidebar', 'ci_theme' ),
			);
			ci_panel_dropdown('layout_blog', $options, __('Blog layout', 'ci_theme'));
		?>
		</p>
		<p>
		<?php
			$options = array(
				''                           => __( '1 Column', 'ci_theme' ),
				'col-sm-6'                   => __( '2 Columns', 'ci_theme' ),
				'col-md-4 col-sm-6'          => __( '3 Columns', 'ci_theme' ),
				'col-lg-3 col-md-4 col-sm-6' => __( '4 Columns', 'ci_theme' ),
			);
			ci_panel_dropdown('layout_blog_columns', $options, __('Blog Columns (only when Blog layout is Full width)', 'ci_theme'));
		?>
		</p>
	</fieldset>

<?php endif; ?>