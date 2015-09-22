<?php
add_action( 'widgets_init', 'ci_widgets_init' );
if( !function_exists('ci_widgets_init') ):
function ci_widgets_init() {

	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'ci_theme' ),
		'id'            => 'blog-sidebar',
		'description'   => __( 'Place here the widgets that you want to display on your blog-related pages', 'ci_theme' ),
		'before_widget' => '<aside id="%1$s" class="%2$s widget group">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	register_sidebar( array(
		'name'          => __( 'Pages Sidebar', 'ci_theme' ),
		'id'            => 'pages-sidebar',
		'description'   => __( 'Place here the widgets that you want to display on your pages', 'ci_theme' ),
		'before_widget' => '<aside id="%1$s" class="%2$s widget group">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	register_sidebar( array(
		'name'          => __( 'Inner Widget Area', 'ci_theme' ),
		'id'            => 'inner-sidebar',
		'description'   => __( 'Place here the widgets that you want to display on the bottom of every single inner page of your website.', 'ci_theme' ),
		'before_widget' => '<section id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="row"><div class="col-xs-12"><h2 class="section-title">',
		'after_title'   => '</h2></div></div>'
	) );

	register_sidebar( array(
		'name'          => __( 'Front Page Widgets', 'ci_theme' ),
		'id'            => 'frontpage-widgets',
		'description'   => __( 'This is the main front page widget area. Assign widgets here to create your front page.', 'ci_theme' ),
		'before_widget' => '<section id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="row"><div class="col-xs-12"><h2 class="section-title">',
		'after_title'   => '</h2></div></div>'
	) );

	register_sidebar( array(
		'name'          => __( 'Artist Sidebar', 'ci_theme' ),
		'id'            => 'artist-sidebar',
		'description'   => __( 'Place here the widgets that you want to display in single artists pages.', 'ci_theme' ),
		'before_widget' => '<aside id="%1$s" class="%2$s widget group">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	register_sidebar( array(
		'name'          => __( 'Album Sidebar', 'ci_theme' ),
		'id'            => 'album-sidebar',
		'description'   => __( 'Place here the widgets that you want to display in single discography pages.', 'ci_theme' ),
		'before_widget' => '<aside id="%1$s" class="%2$s widget group">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	if ( woocommerce_enabled() ) {
		register_sidebar( array(
			'name'          => __( 'Eshop Sidebar', 'ci_theme' ),
			'id'            => 'eshop-sidebar',
			'description'   => __( 'Widgets placed in this sidebar will appear on your e-shop pages.', 'ci_theme' ),
			'before_widget' => '<aside id="%1$s" class="%2$s widget group">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );
	}

	register_sidebar( array(
		'name'          => __( 'Footer sidebar #1', 'ci_theme' ),
		'id'            => 'footer-sidebar-one',
		'description'   => __( 'Place here the widgets that you want to display on your footer column #1', 'ci_theme' ),
		'before_widget' => '<aside id="%1$s" class="%2$s widget group">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	register_sidebar( array(
		'name'          => __( 'Footer sidebar #2', 'ci_theme' ),
		'id'            => 'footer-sidebar-two',
		'description'   => __( 'Place here the widgets that you want to display on your footer column #2', 'ci_theme' ),
		'before_widget' => '<aside id="%1$s" class="%2$s widget group">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

	register_sidebar( array(
		'name'          => __( 'Footer sidebar #3', 'ci_theme' ),
		'id'            => 'footer-sidebar-three',
		'description'   => __( 'Place here the widgets that you want to display on your footer column #3', 'ci_theme' ),
		'before_widget' => '<aside id="%1$s" class="%2$s widget group">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );

}
endif;
?>