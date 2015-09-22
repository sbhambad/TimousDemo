<?php
/*
 * Template Name: Blog - Full width
 */
?>

<?php get_header(); ?>

<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php get_template_part('inc_section_titles'); ?>

				<div class="row item-list">
					<?php
						$args = array(
							'post_type' => 'post',
							'paged'     => ci_get_page_var()
						);

						$posts = new WP_Query($args);
					?>
					<?php if ( $posts->have_posts() ) : while ( $posts->have_posts() ) : $posts->the_post(); ?>
						<div class="<?php echo esc_attr( ci_setting( 'layout_blog_columns' ) ); ?>">
							<div class="item cpt_post <?php echo ci_setting( 'layout_blog_columns' ) == '' ? 'item-fullwidth' : ''; ?>">
								<a href="<?php the_permalink(); ?>" class="item-hold">
									<figure class="item-thumb">
										<?php
											if( ci_setting( 'layout_blog_columns' ) == '' ) {
												the_post_thumbnail( 'ci_blog_full' );
											} else {
												the_post_thumbnail();
											}
										?>
									</figure>
								</a>

								<div class="item-info">
									<p class="item-title-intro">
										<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
									</p>

									<p class="item-title-main"><?php the_title(); ?></p>
								</div>

								<a class="btn item-btn" href="<?php the_permalink(); ?>"><?php echo ci_get_read_more_text( get_post_type() ); ?></a>
							</div>
						</div>
					<?php endwhile; endif; ?>

					<?php wp_reset_postdata(); ?>
				</div>

				<?php ci_pagination(array(), $posts); ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>