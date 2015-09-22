<?php get_header(); ?>

<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-sm-7 <?php echo ci_get_layout_classes( 'blog', 'content' ); ?>">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php get_template_part('inc_section_titles'); ?>

					<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry') ; ?>>
						<?php if ( ci_has_image_to_show() ) : ?>
							<figure class="entry-thumb">
								<a href="<?php echo ci_get_featured_image_src( 'large' ); ?>" data-rel="prettyPhoto">
									<?php ci_the_post_thumbnail(); ?>
								</a>
							</figure>
						<?php endif; ?>

						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages(); ?>
						</div>
					</article>

					<?php comments_template(); ?>
				<?php endwhile; endif; ?>
			</div>

			<div class="col-md-4 col-sm-5 <?php echo ci_get_layout_classes( 'blog', 'sidebar' ); ?>">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>