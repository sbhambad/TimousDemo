<form action="<?php echo esc_url(home_url('/')); ?>" class="searchform" method="get" role="search">
	<div>
		<label for="s" class="screen-reader-text"><?php _e('Search for:','ci_theme'); ?></label> <input type="text" placeholder="<?php _e('Search for Products', 'ci_theme'); ?>" id="s" name="s" value="<?php echo (get_search_query()!="" ? get_search_query() : '' ); ?>">
		<button class="searchsubmit" type="submit"><i class="fa fa-search"></i></button>
		<input type="hidden" name="post_type" value="product" />
	</div>
</form>