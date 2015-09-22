<?php global $ci, $ci_defaults, $load_defaults, $content_width; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_audio_options', 50);
	if( !function_exists('ci_add_tab_audio_options') ):
		function ci_add_tab_audio_options($tabs)
		{
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('Music Player Options', 'ci_theme');
			return $tabs;
		}
	endif;

	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );
	$ci_defaults['music_player_post_id']     = '';
	$ci_defaults['music_player_stream_url']  = '';
	$ci_defaults['music_player_stream_name'] = __('Streaming Radio', 'ci_theme');
	$ci_defaults['music_player_autoplay'] = '';
	$ci_defaults['music_player_display_current_track'] = '';

?>
<?php else: ?>

	<fieldset class="set">

		<p class="guide"><?php _e('You can enable the front page music player by selecting a Discography item. Its tracklisting will be used as the source of the songs. Any SoundCloud links you might have will be ignored. Alternatively, you can provide a stream URL and name. If a stream URL is set, the Discography item will be ignored.', 'ci_theme'); ?></p>

		<label for="<?php echo THEME_OPTIONS . '[music_player_post_id]'; ?>"><?php _e('Select an album:', 'ci_theme'); ?></label>
		<?php
			wp_dropdown_posts(array(
				'post_type'         => 'cpt_discography',
				'selected'          => $ci['music_player_post_id'],
				'show_option_none'  => '&nbsp;',
				'option_none_value' => '',
				'select_even_if_empty'  => true,
			), THEME_OPTIONS . '[music_player_post_id]' );
		?>

		<p>
			<?php ci_panel_checkbox( 'music_player_autoplay', 'on',__( 'Enable autoplay on page load?', 'ci_theme' ) ); ?>
		</p>
	</fieldset>
	<fieldset class="set">
		<p class="guide"><?php _e('Both Icecast and Shoutcast streams are supported by the player, however you need to find the appropriate stream URL that points to your actual stream, and not a .pls file. If your stream URL contains an IP or it does not seem to work, add a semicolon at the end, e.g. "http://192.102.192.1:8006/stream;".', 'ci_theme'); ?></p>

		<?php
			ci_panel_input( 'music_player_stream_url', __( 'Stream URL:', 'ci_theme' ) );
			ci_panel_input( 'music_player_stream_name', __( 'Stream Name:', 'ci_theme' ) );
		?>

		<fieldset class="set">
			<p class="guide"><?php _e('You can enable fetching and display of the current playing song from your server. Please note that this is an experimental feature and specifically requires a SHOUTcast server version 2.0 or higher, and your Stream URL to be in the form of IP:port (e.g. http://192.102.192.1:8006/stream; or similar). If your server does not meet the above requirements it is highly advised to keep this option turned off.', 'ci_theme'); ?></p>
			<?php
				ci_panel_checkbox( 'music_player_display_current_track', 'on',__( 'Show the current playing song.', 'ci_theme' ) );
			?>
		</fieldset>

	</fieldset>

<?php endif; ?>
