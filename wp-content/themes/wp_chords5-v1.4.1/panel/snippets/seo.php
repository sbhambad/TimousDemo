<?php global $ci, $ci_defaults, $load_defaults, $content_width; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	
	$ci_defaults['wp_title_tag']      = 'on';
	// 'enabled' means ci_title(), 'disabled' means wp_title(''), 'auto' uses detection for known plugins
	$ci_defaults['use_ci_head_title'] = 'auto';
	$ci_defaults['title_separator']   = '|';

	if( ci_setting( 'wp_title_tag' ) == 'on' ) {
		add_theme_support( 'title-tag' );

		// Back-compat for native title-tag support in WP < 4.1
		if ( ! function_exists( '_wp_render_title_tag' ) ):
			add_action( 'wp_head', 'ci_wp_render_title_tag' );
			if ( ! function_exists( 'ci_wp_render_title_tag' ) ):
				function ci_wp_render_title_tag() {
					echo '<title>' . wp_title( '|', false, 'right' ) . '</title>' . PHP_EOL;
				}
			endif;
		endif;
	} else {
		add_action( 'wp_head', 'ci_render_title_tag' );
		if ( ! function_exists( 'ci_render_title_tag' ) ):
			function ci_render_title_tag() {
				ob_start();
				ci_e_title();
				$title = ob_get_clean();
				echo '<title>' . $title . '</title>' . PHP_EOL;
			}
		endif;
	}


	function ci_check_seo_plugin() {
		$plugins = array(
			// Add a class (or function) name (important) that is unique to the plugin, and a plugin name (not important)
			'get_wpseo_options'       => 'YOAST WordPress SEO',
			'All_in_One_SEO_Pack'     => 'All In One SEO Pack',
			'b2_c'                    => 'B2 SEO',
			'gregsHighPerformanceSEO' => "Greg's High Performance SEO",
			'Headspace_Plugin'        => 'HeadSpace2',
			'Platinum_SEO_Pack'       => 'Platinum SEO Pack',
			'SEO_Ultimate'            => 'SEO Ultimate',
			'zeo_rewrite_title'       => 'SEO WordPress'
		);

		$is_seo_enabled = false;
		$plugin         = false;
		foreach ( $plugins as $function => $name ) {
			if ( function_exists( $function ) or class_exists( $function ) ) {
				$plugin = $name;
				break;
			}
		}

		return $plugin;
	}

	/**
	 * Echoes the web page title, depending on context.
	 * 
	 * @access public
	 * @return void
	 */
	if( !function_exists('ci_e_title')):
	function ci_e_title()
	{
		if(ci_setting('use_ci_head_title')=='enabled') 
		{
			echo ci_title();
		} 
		else if(ci_setting('use_ci_head_title')=='disabled') 
		{
			wp_title('');
		} 
		else if(ci_setting('use_ci_head_title')=='auto') 
		{
			$plugin = ci_check_seo_plugin();

			if($plugin === false)
			{
				echo ci_title();
			}
			else
			{
				switch($plugin){
					// Greg's High Performance SEO
					case 'ghpseo_output': 
						ghpseo_output('main_title');
						break;
					default:
						wp_title('');
						break;
				}
			}
			
		}
	}
	endif;
	
	/**
	 * Returns the web page title, depending on context.
	 * 
	 * @return string
	 */
	if ( ! function_exists( 'ci_title' ) ):
	function ci_title() {
		$sep = trim( ci_setting( 'title_separator' ) );
		$sep = ! empty( $sep ) ? ' ' . $sep . ' ' : ' | ';

		return wp_title( $sep, false, 'right' );
	}
	endif;


?>
<?php else: ?>
		
	<fieldset id="ci-panel-seo" class="set">
		<legend><?php _e('SEO', 'ci_theme'); ?></legend>
		<p class="guide">
			<?php
				_e('As of WordPress v4.1 it is recommended to let WordPress manage the &lt;title> tag itself in a standard way. Good SEO plugins should be able to manipulate this title.', 'ci_theme');
				echo '<br />';
				_e('The theme provides some title-manipulation options until this feature is fully implemented. Un-check the following checkbox to fine-tune the title tag manually. These options will be removed completely when WordPress reaches v4.3.', 'ci_theme');
			?>
		</p>
		<?php ci_panel_checkbox( 'wp_title_tag', 'on', __( 'Let WordPress manage the title tag.', 'ci_theme' ), array( 'input_class' => 'check toggle-button' ) ); ?>

		<div class="toggle-pane">

			<p class="guide">
				<?php echo
					__('Select how you want the &lt;title> tag handled. "<b>Automatic title</b>" checks a list of known SEO plugins and behaves accordingly. This is the recommended setting if the SEO plugin you use is detected properly.', 'ci_theme') . ' ' .
					__('"<b>Use the theme\'s default title</b>" gives a good result if you don\'t have a SEO plugin installed.', 'ci_theme') . ' ' .
					__('"<b>Use pure WordPress function</b>" should only be used if you have a SEO plugin installed that isn\'t detected, and the plugin\'s documentation suggests to change the title tag function to <b>wp_title(\'\');</b> . In that case, you don\'t need to edit any files, just select this option.', 'ci_theme');
				?>
			</p>
			<p class="guide">
				<?php
					$seo_plugin = ci_check_seo_plugin();
					if($seo_plugin === false) {
						_e('A known SEO plugin could not be detected.', 'ci_theme');
					}
					else {
						echo sprintf(__('The <b>%s</b> plugin was detected. Please use the <b>Automatic title</b> setting.', 'ci_theme'), $seo_plugin);
					}
				?>
			</p>
			<fieldset>
				<label><?php _e('Use the following on the &lt;title> tag', 'ci_theme'); ?></label>
				<?php
					ci_panel_radio('use_ci_head_title', 'use_auto_title', 'auto', __('Automatic title', 'ci_theme'));
					ci_panel_radio('use_ci_head_title', 'use_ci_title', 'enabled', __("Use the theme's default title", 'ci_theme'));
					ci_panel_radio('use_ci_head_title', 'use_wp_title', 'disabled', __("Use pure WordPress function ( wp_title('') )", 'ci_theme'));
				?>
			</fieldset>

			<p class="guide mt20"><?php _e('The title separator is inserted between various elements within the title tag of each page. Leading and trailing spaces are automatically inserted where appropriate. This only applies when the option "Use the theme\'s default title" above is selected.', 'ci_theme'); ?></p>
			<fieldset>
				<?php ci_panel_input('title_separator', __('Title separator', 'ci_theme')); ?>
			</fieldset>

		</div>
	</fieldset>
	
<?php endif; ?>