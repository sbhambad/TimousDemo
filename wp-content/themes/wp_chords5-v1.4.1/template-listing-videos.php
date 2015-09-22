<?php
/*
 * Template Name: Videos Listing
 */
?>

<?php get_header(); ?>

<?php while( have_posts() ): the_post(); ?>

	<main id="main">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h3 class="section-title"><?php the_title(); ?></h3>

					<?php
						$cpt            = 'cpt_video';
						$cpt_taxonomy   = 'video-category';
						$masonry        = get_post_meta( get_the_ID(), 'videos_listing_masonry', true );
						$isotope        = get_post_meta( get_the_ID(), 'videos_listing_isotope', true );
						$columns        = get_post_meta( get_the_ID(), 'videos_listing_columns', true );
						$posts_per_page = get_post_meta( get_the_ID(), 'videos_listing_posts_per_page', true );

						$div_class = '';
						if( 'on' == $isotope || 'on' == $masonry ) {
							$div_class = 'list-masonry';
						}
					?>

					<?php if ( 'on' == $isotope ): ?>
						<ul class="filters-nav group">
							<li><a href="#filter" class="selected btn small transparent" data-filter="*"><?php _e( 'All Items', 'ci_theme' ); ?></a></li>
							<?php $cats = get_terms( $cpt_taxonomy, array( 'hide_empty' => 1 ) ); ?>
							<?php foreach ( $cats as $cat ): ?>
								<li><a href="#filter" class="btn small transparent" data-filter=".<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>


					<div class="row item-list <?php echo $div_class; ?>">
						<?php
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

							$args = array(
								'paged'     => ci_get_page_var(),
								'post_type' => $cpt,
							);


							if ( $posts_per_page >= 1 ) {
								$args['posts_per_page'] = $posts_per_page;
							} elseif ( $posts_per_page <= -1 ) {
								$args['posts_per_page'] = -1;
							}

							if( 'on' == $isotope ) {
								$args['posts_per_page'] = -1;
							}

							$full_width_class = 1 == $columns ? ' item-fullwidth ' : '';

							$q = new WP_Query( $args );
						?>

						<?php while( $q->have_posts() ): $q->the_post(); ?>

							<?php
								$isotope_classes = '';
								if ( 'on' == $isotope ) {
									$cats = wp_get_object_terms( $post->ID, $cpt_taxonomy );
									foreach ( $cats as $cat ) {
										$isotope_classes .= ' ' . $cat->slug;
									}
								}
							?>
							<div class="<?php echo $item_classes . $isotope_classes; ?>">
								<div <?php post_class( 'item' . $full_width_class ); ?>>
									<a href="<?php the_permalink(); ?>" class="item-hold">
										<figure class="item-thumb">
											<?php
												if ( 1 == $columns ) {
													the_post_thumbnail( 'ci_blog_full' );
												} elseif ( 'on' == $masonry ) {
													the_post_thumbnail( 'ci_masonry' );
												} else {
													the_post_thumbnail();
												}
											?>
										</figure>
									</a>

									<?php get_template_part( 'listing-meta' ); ?>

									<a class="btn item-btn" href="<?php the_permalink(); ?>"><?php echo ci_get_read_more_text( get_post_type() ); ?></a>
								</div>
							</div>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>

					</div>
					<?php ci_pagination( array(), $q ); ?>
				</div>
			</div>
		</div>
	</main>

<?php endwhile; ?>

<?php get_footer(); ?>