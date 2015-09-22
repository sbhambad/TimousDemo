<?php global $ci, $ci_defaults, $load_defaults; ?>
<?php if ($load_defaults===TRUE): ?>
<?php

	$ci_defaults['google_maps_api_enable'] = 'on';
	$ci_defaults['google_maps_api_key']    = '';

	add_action( 'init', 'ci_register_google_maps_api' );
	function ci_register_google_maps_api() {
		$key = '';
		if ( ci_setting( 'google_maps_api_key' ) ) {
			$key = 'key=' . ci_setting( 'google_maps_api_key' ) . '&';
		}
		$google_url = "//maps.googleapis.com/maps/api/js?" . $key . "v=3.5&sensor=false";
		wp_register_script('google-maps', $google_url, array(), null, false);
	}

?>
<?php else: ?>

	<fieldset id="ci-panel-google-maps-api" class="set">
		<p class="guide"><?php _e( 'The Google Maps API must be loaded only once in each page. Since many plugins may try to load it as well, you might want to disable it from the theme to avoid potential errors.', 'ci_theme' ); ?></p>
		<?php ci_panel_checkbox( 'google_maps_api_enable', 'on', __( 'Load Google Maps API.', 'ci_theme' ) ); ?>

		<p class="guide"><?php echo sprintf( __( 'Enter here your Google Maps API Key. While your maps will be displayed at first without an API key, if you get a lot of visits to your site (more than 25.000 per day currently), the maps might stop working. In that case, you need to issue a key from <a href="%s">Google Accounts</a>', 'ci_theme' ), 'https://code.google.com/apis/console/' ); ?></p>
		<?php ci_panel_input( 'google_maps_api_key', __( 'Google Maps API Key', 'ci_theme' ) ); ?>
	</fieldset>

<?php endif; ?>