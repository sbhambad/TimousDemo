<?php global $ci, $ci_defaults, $load_defaults; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_newsletter_options', 100);
	if( !function_exists('ci_add_tab_newsletter_options') ):
		function ci_add_tab_newsletter_options($tabs) 
		{ 
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('Newsletter Options', 'ci_theme'); 
			return $tabs; 
		}
	endif;

	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );
	$ci_defaults['newsletter_action']     = '#';
	$ci_defaults['newsletter_email_id']   = 'e_id';
	$ci_defaults['newsletter_email_name'] = 'e_name';

	load_panel_snippet('newsletter_hidden_fields');
?>
<?php else: ?>

	<fieldset class="set">
		<p class="guide"><?php echo sprintf(__('This newsletter form can be used in combination with plugins or online providers such as <a href="%1$s">Campaign Monitor</a> and <a href="%2$s">MailChimp</a>. Please refer to their respective documentation if you need to know what the values of <b>Action</b>, <b>field names</b> and <b>field IDs</b> should be. Please note that if the <b>Action URL</b> is blank, then the form will not be displayed.', 'ci_theme'), 'http://www.campaignmonitor.com', 'http://www.mailchimp.com'); ?></p>
		<?php
			ci_panel_input( 'newsletter_action', __( 'Action URL', 'ci_theme' ) );
			ci_panel_input( 'newsletter_email_id', __( '"Email" field ID', 'ci_theme' ) );
			ci_panel_input( 'newsletter_email_name', __( '"Email" field name', 'ci_theme' ) );
		?>
	</fieldset>

	<?php load_panel_snippet('newsletter_hidden_fields'); ?>

<?php endif; ?>