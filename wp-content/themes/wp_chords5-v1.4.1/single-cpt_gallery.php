<?php get_header(); ?>

<main id="main">
	<div class="container">
		<?php while( have_posts() ): the_post(); ?>
			<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry') ; ?>>
				<?php
					$location   = get_post_meta( get_the_ID(), 'ci_cpt_gallery_location', true );
					$caption    = get_post_meta( get_the_ID(), 'ci_cpt_gallery_caption', true ) == 'on' ? true : false;
					$columns    = get_post_meta( get_the_ID(), 'ci_cpt_gallery_cols', true );
					$masonry    = get_post_meta( get_the_ID(), 'ci_cpt_gallery_masonry', true );
					$thumbs     = ci_featgal_get_attachments();
					$thumb_size = 1 == $columns ? 'ci_blog_full' : 'post-thumbnail';

					$div_class = '';
					if( 'on' == $masonry ) {
						$div_class = 'list-masonry';
						if( $columns > 1 ) {
							$thumb_size = 'ci_masonry';
						}
					}

					$item_classes = '';
					switch( $columns ) {
						case 1:
							$item_classes = 'col-xs-12';
							break;
						case 2:
							$item_classes = 'col-xs-12 col-sm-6';
							break;
						case 3:
							$item_classes = 'col-xs-12 col-sm-6 col-md-4';
							break;
						case 4:
							$item_classes = 'col-xs-12 col-sm-6 col-md-4 col-lg-3';
							break;
					}

					$full_width_class = 1 == $columns ? ' item-fullwidth ' : '';
				?>

				<h1 class="section-title">
					<?php the_title(); ?>
					<?php
						if ( !empty( $location ) ) {
							echo ' / ' . $location;
						}
					?>
				</h1>

				<div class="row <?php echo $div_class; ?>">
					<?php while( $thumbs->have_posts() ): $thumbs->the_post(); ?>
						<div class="<?php echo $item_classes; ?>">
							<div class="item <?php echo $full_width_class; ?>">
								<?php
									global $post;
								?>

								<a class="zoom" data-rel="prettyPhoto[gal]" href="<?php echo ci_get_image_src($post->ID, 'large'); ?>">
									<figure class="item-thumb">
										<?php $attachment = wp_prepare_attachment_for_js($post->ID); ?>
										<img src="<?php echo ci_get_image_src($post->ID, $thumb_size); ?>" alt="<?php echo esc_attr($attachment['alt']); ?>">
									</figure>
								</a>
								<?php if( $caption ): ?>
									<div class="item-info">
										<p class="item-byline"><?php the_excerpt(); ?></p>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>

					<div class="col-xs-12">
						<div class="entry-content">
							<?php the_content(); ?>
						</div>
					</div>

				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php get_footer(); ?>