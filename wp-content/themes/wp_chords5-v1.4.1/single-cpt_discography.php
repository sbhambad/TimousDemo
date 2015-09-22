<?php get_header(); ?>

<main id="main">
	<div class="container">
		<?php while( have_posts() ): the_post(); ?>
			<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry') ; ?>>
				<div class="row">
					<div class="col-xs-12">
						<h1 class="section-title"><?php the_title(); ?></h1>
					</div>

					<?php $disc_tracks = get_post_meta( get_the_ID(), 'ci_cpt_discography_tracks', true ); ?>

					<div class="col-md-4 col-sm-5 col-xs-12 <?php echo ci_get_layout_classes( 'cpt', 'sidebar' ); ?>">
						<div class="sidebar">
							<div class="item">

								<?php if( has_post_thumbnail() ): ?>
									<a data-rel="prettyPhoto" class="zoom" href="<?php echo ci_get_featured_image_src( 'large' ); ?>">
										<figure class="item-thumb">
											<?php the_post_thumbnail( 'ci_masonry' ); ?>
										</figure>
									</a>
								<?php endif; ?>

								<?php get_template_part( 'single-meta', get_post_type() ); ?>

							</div>

							<?php dynamic_sidebar( 'album-sidebar' );?>
						</div>
					</div>

					<div class="col-md-8 col-sm-7 col-xs-12 <?php echo ci_get_layout_classes( 'cpt', 'content' ); ?>">
						<div class="entry-content">
							<?php
								if ( !empty( $disc_tracks ) && !has_shortcode( get_the_content(), 'tracklisting' ) ) {
									echo do_shortcode( '[tracklisting]' );
								}
							?>
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php get_footer(); ?>
