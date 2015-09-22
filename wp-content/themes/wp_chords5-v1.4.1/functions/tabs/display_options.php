<?php global $ci, $ci_defaults, $load_defaults, $content_width; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_display_options', 30);
	if( !function_exists('ci_add_tab_display_options') ):
		function ci_add_tab_display_options($tabs) 
		{ 
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('Display Options', 'ci_theme'); 
			return $tabs; 
		}
	endif;

	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );
	load_panel_snippet('excerpt');
	load_panel_snippet('seo');
	load_panel_snippet('comments');
	load_panel_snippet('pagination');
	load_panel_snippet('featured_image_single');

	add_filter( 'ci-read-more-link', 'ci_theme_readmore', 10, 3 );
	if ( !function_exists( 'ci_theme_readmore' ) ):
	function ci_theme_readmore( $html, $text, $link ) {
		return '<a class="btn" href="' . $link . '">' . $text . '</a>';
	}
	endif;

	// Set our full width template width.
	add_filter('ci_full_template_width', 'ci_fullwidth_width');
	if( !function_exists('ci_fullwidth_width') ):
	function ci_fullwidth_width()
	{
		return 1140;
	}
	endif;
	load_panel_snippet('featured_image_fullwidth');

?>
<?php else: ?>

	<?php load_panel_snippet('pagination'); ?>

	<?php load_panel_snippet('excerpt'); ?>

	<?php load_panel_snippet('seo'); ?>

	<?php load_panel_snippet('comments'); ?>

	<?php load_panel_snippet('featured_image_single'); ?>

	<?php load_panel_snippet('featured_image_fullwidth'); ?>

<?php endif; ?>