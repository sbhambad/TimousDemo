<h1 class="section-title">
	<?php
		if ( woocommerce_enabled() and is_woocommerce() ) {
			woocommerce_page_title();
		} elseif ( is_singular( 'cpt_discography' ) or is_post_type_archive( 'cpt_discography' ) ) {
			ci_e_setting( 'title_discography' );
		} elseif ( is_singular( 'cpt_artist' ) or is_post_type_archive( 'cpt_artist' ) ) {
			ci_e_setting( 'title_artists' );
		} elseif ( is_singular( 'cpt_gallery' ) or is_post_type_archive( 'cpt_gallery' ) ) {
			ci_e_setting( 'title_galleries' );
		} elseif ( is_singular( 'cpt_video' ) or is_post_type_archive( 'cpt_video' ) ) {
			ci_e_setting( 'title_videos' );
		} elseif ( is_post_type_archive( 'cpt_event' ) ) {
			ci_e_setting( 'title_events' );
		} elseif ( is_page_template( 'template-blog-fullwidth.php' ) ) {
			ci_e_setting( 'title_blog' );
		} elseif ( is_page() ) {
			single_post_title();
		} elseif ( is_singular( 'post' ) or is_home() ) {
			ci_e_setting( 'title_blog' );
		} elseif ( is_category() || is_tag() ) {
			single_term_title();
		} elseif ( is_month() ) {
			single_month_title();
		} elseif ( is_date() ) {
			single_term_title();
		} elseif ( is_search() ) {
			_e( 'Search results', 'ci_theme' );
		} elseif ( is_404() ) {
			_e( 'Page not found! (404)', 'ci_theme' );
		} elseif ( is_tax() ) {
			$taxonomy = get_query_var( 'taxonomy' );
			$term     = get_term( get_queried_object_id(), $taxonomy );
			echo $term->name;
		}
	?>
</h1>