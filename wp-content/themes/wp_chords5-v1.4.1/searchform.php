<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search" class="searchform">
	<div>
		<label for="s" class="screen-reader-text"><?php _e( 'Search for:', 'ci_theme' ); ?></label>
		<input type="text" id="s" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'SEARCH', 'ci_theme' ); ?>">
		<button type="submit" value="<?php _e( 'GO', 'ci_theme' ); ?>" class="searchsubmit"><i class="fa fa-search"></i></button>
	</div>
</form>