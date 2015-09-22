<?php get_header(); ?>

<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php get_template_part('inc_section_titles'); ?>

				<?php
					$cpt          = 'cpt_gallery';
					$cpt_taxonomy = get_query_var( 'taxonomy' );
					$masonry      = ci_setting('galleries_masonry');
					$isotope      = ci_setting('galleries_isotope');
					$columns      = ci_setting('galleries_columns');

					$term = get_term_by( 'slug', get_query_var( 'term' ), $cpt_taxonomy );

					$div_class = '';
					if( 'on' == $isotope || 'on' == $masonry ) {
						$div_class = 'list-masonry';
					}
				?>

				<?php if ( 'on' == $isotope ): ?>
					<ul class="filters-nav group">
						<li><a href="#filter" class="selected btn small transparent" data-filter="*"><?php _e( 'All Items', 'ci_theme' ); ?></a></li>
						<?php $cats = get_terms( $cpt_taxonomy, array( 'hide_empty' => 1, 'child_of' => $term->term_id ) ); ?>
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
							'tax_query' => array(
								array(
									'taxonomy'         => $cpt_taxonomy,
									'terms'            => $term->term_id,
									'include_children' => true,
								),
							),
						);


						if( 'on' == $isotope ) {
							$args['posts_per_page'] = -1;
						}

						$full_width_class = 1 == $columns ? ' item-fullwidth ' : '';

						if ( !empty( $isotope ) ) {
							query_posts( $args );
						}
					?>

					<?php while( have_posts() ): the_post(); ?>

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

				</div>
				<?php ci_pagination(); ?>
				<?php
					if ( !empty( $isotope ) ) {
						wp_reset_query();
					}
				?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>