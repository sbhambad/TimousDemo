<?php global $ci, $ci_defaults, $load_defaults, $content_width; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_titles_options', 80);
	if( !function_exists('ci_add_tab_titles_options') ):
		function ci_add_tab_titles_options($tabs)
		{
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('Section/Button titles', 'ci_theme');
			return $tabs;
		}
	endif;

	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );
	$ci_defaults['title_blog']        = _x( 'From the blog', 'section title', 'ci_theme' );
	$ci_defaults['title_discography'] = _x( 'Discography', 'section title', 'ci_theme' );
	$ci_defaults['title_videos']      = _x( 'Videos', 'section title', 'ci_theme' );
	$ci_defaults['title_galleries']   = _x( 'Galleries', 'section title', 'ci_theme' );
	$ci_defaults['title_artists']     = _x( 'Artists', 'section title', 'ci_theme' );
	$ci_defaults['title_events']      = _x( 'Events', 'section title', 'ci_theme' );

	$ci_defaults['read_more_text_cpt_event']       = _x( 'Learn more', 'button wording', 'ci_theme' );
	$ci_defaults['read_more_text_cpt_discography'] = _x( 'Learn more', 'button wording', 'ci_theme' );
	$ci_defaults['read_more_text_cpt_artist']      = _x( 'Learn more', 'button wording', 'ci_theme' );
	$ci_defaults['read_more_text_cpt_gallery']     = _x( 'View Photos', 'button wording', 'ci_theme' );
	$ci_defaults['read_more_text_cpt_video']       = _x( 'View Video', 'button wording', 'ci_theme' );
	$ci_defaults['read_more_text_product']         = _x( 'Buy Now', 'button wording', 'ci_theme' );

?>
<?php else: ?>

	<fieldset class="set">
		<legend><?php _e( 'Button wording', 'ci_theme' ); ?></legend>
		<p class="guide"><?php _e( 'Set the button wording, depending on the post type. These buttons appear on listing pages and on widgets.', 'ci_theme' ); ?></p>
		<?php
			ci_panel_input( 'read_more_text_cpt_event', __( "Events' button text:", 'ci_theme' ) );
			ci_panel_input( 'read_more_text_cpt_discography', __( "Discographies' button text:", 'ci_theme' ) );
			ci_panel_input( 'read_more_text_cpt_artist', __( "Artists' button text:", 'ci_theme' ) );
			ci_panel_input( 'read_more_text_cpt_gallery', __( "Galleries' button text:", 'ci_theme' ) );
			ci_panel_input( 'read_more_text_cpt_video', __( "Videos' button text:", 'ci_theme' ) );
			ci_panel_input( 'read_more_text_product', __( "Products' button text (requires WooCommerce):", 'ci_theme' ) );
		?>
	</fieldset>

	<fieldset class="set">
		<legend><?php _e( 'Section titles', 'ci_theme' ); ?></legend>
		<p class="guide"><?php _e( 'Set the titles for the various sections of your website. These titles appear on your blog and post type archives (i.e. NOT listings created with a page template).', 'ci_theme' ); ?></p>
		<?php
			ci_panel_input( 'title_blog', __( 'Blog section title:', 'ci_theme' ) );
			ci_panel_input( 'title_discography', __( 'Discography section title:', 'ci_theme' ) );
			ci_panel_input( 'title_videos', __( 'Videos section title:', 'ci_theme' ) );
			ci_panel_input( 'title_galleries', __( 'Galleries section title:', 'ci_theme' ) );
			ci_panel_input( 'title_artists', __( 'Artists section title:', 'ci_theme' ) );
			ci_panel_input( 'title_events', __( 'Events section title:', 'ci_theme' ) );
		?>
	</fieldset>

<?php endif; ?>