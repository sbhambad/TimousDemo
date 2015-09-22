<?php get_header(); ?>

<main id="main">
	<div class="container">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php
				$event_venue = get_post_meta( get_the_ID(), 'ci_cpt_event_venue', true );
				$event_lon   = get_post_meta( get_the_ID(), 'ci_cpt_event_lon', true );
				$event_lat   = get_post_meta( get_the_ID(), 'ci_cpt_event_lat', true );
			?>
			<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry') ; ?>>

				<div class="row">
					<div class="col-xs-12">
						<h1 class="section-title"><?php the_title(); ?></h1>
					</div>
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
						</div>
					</div>

					<div class="col-md-8 col-sm-7 col-xs-12 <?php echo ci_get_layout_classes( 'cpt', 'content' ); ?>">
						<?php if( !empty($event_lat) and !empty($event_lon) ): ?>
							<div id="event_map" data-lat="<?php echo esc_attr( $event_lat ); ?>" data-lng="<?php echo esc_attr( $event_lon ); ?>" data-tooltip-txt="<?php echo esc_attr( $event_venue ); ?>" title="<?php the_title_attribute(); ?>"></div>
						<?php endif; ?>

						<div class="entry-content">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</article>
		<?php endwhile; endif; ?>
	</div>
</main>

<?php get_footer(); ?>