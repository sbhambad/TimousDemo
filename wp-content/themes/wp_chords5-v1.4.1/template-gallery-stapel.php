<?php
/*
 * Template Name: Galleries Stapel
 */
?>

<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<main id="main">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="stapel-head">
						<h3 class="section-title"><?php the_title(); ?></h3>

						<a class="tp-close" href="#"><i class="fa fa-arrow-circle-left"></i></a>
					</div>


					<ul id="tp-grid" class="tp-grid">
						<?php
							$args = array(
								'post_type' => 'cpt_gallery',
								'posts_per_page' => -1
							);

							$q = new WP_Query( $args );
						?>

						<?php while( $q->have_posts() ): $q->the_post(); ?>
							<?php
								$gallery_title = get_the_title();
								$thumbs        = ci_featgal_get_attachments(get_the_ID());
								$gallery_id    = get_the_ID();
								$caption    = get_post_meta( get_the_ID(), 'ci_cpt_gallery_caption', true ) == 'on' ? true : false;

							?>
							<li data-pile="<?php echo $gallery_title; ?>">
								<a class="zoom" data-rel="prettyPhoto[<?php echo $gallery_id; ?>]" href="<?php echo ci_get_featured_image_src('large'); ?>">
									<figure class="item-thumb">
										<?php the_post_thumbnail('ci_thumb_sm'); ?>
									</figure>

									<?php if( $caption ): ?>
										<div class="item-info">
											<p class="item-byline"><?php the_excerpt(); ?></p>
										</div>
									<?php endif; ?>
								</a>
							</li>

							<?php while ( $thumbs->have_posts() ) : $thumbs->the_post(); ?>
							<li data-pile="<?php echo $gallery_title; ?>">
								<a class="zoom" data-rel="prettyPhoto[<?php echo $gallery_id; ?>]" href="<?php echo ci_get_image_src($post->ID, 'large'); ?>">
									<figure class="item-thumb">
										<img src="<?php echo ci_get_image_src($post->ID, 'ci_thumb_sm'); ?>" alt="">
									</figure>
									<?php if( $caption ): ?>
										<div class="item-info">
											<p class="item-byline"><?php the_excerpt(); ?></p>
										</div>
									<?php endif; ?>
								</a>
							</li>
							<?php endwhile; wp_reset_postdata(); ?>

						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</ul>

					<?php ci_pagination( array(), $q ); ?>
				</div>
			</div>
		</div>
	</main>

<?php endwhile; ?>

<?php get_footer(); ?>