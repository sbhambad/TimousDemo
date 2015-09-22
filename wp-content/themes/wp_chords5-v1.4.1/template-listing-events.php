<?php
/*
 * Template Name: Events Listing
 */
?>

<?php get_header(); ?>

<?php while( have_posts() ): the_post(); ?>

	<main id="main">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<?php
						$cpt             = 'cpt_event';
						$cpt_taxonomy    = 'event-category';

						$events_upcoming       = get_post_meta( get_the_ID(), 'events_listing_upcoming', true );
						$events_upcoming_title = get_post_meta( get_the_ID(), 'events_listing_upcoming_title', true );
						$events_past           = get_post_meta( get_the_ID(), 'events_listing_past', true );
						$events_past_title     = get_post_meta( get_the_ID(), 'events_listing_past_title', true );

						$masonry         = get_post_meta( get_the_ID(), 'events_listing_masonry', true );
						$isotope         = get_post_meta( get_the_ID(), 'events_listing_isotope', true );
						$columns         = get_post_meta( get_the_ID(), 'events_listing_columns', true );
						$posts_per_page  = get_post_meta( get_the_ID(), 'events_listing_posts_per_page', true );

						$div_class = '';
						if( 'on' == $isotope || 'on' == $masonry ) {
							$div_class = 'list-masonry';
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

					<?php if( 'on' == $events_upcoming ): ?>
						<div class="event-list">
							<h3 class="section-title"><?php echo $events_upcoming_title; ?></h3>

							<?php
								$recurrent_params = array(
									'post_type'      => $cpt,
									'posts_per_page' => -1,
									'meta_key'       => 'ci_cpt_event_recurrence',
									'orderby'        => 'meta_value',
									'order'          => 'ASC',
									'meta_query'     => array(
										array(
											'key'     => 'ci_cpt_event_recurrent',
											'value'   => 'enabled',
											'compare' => '='
										)
									)
								);

								$date_params = array(
									'post_type'  => $cpt,
									'paged'      => ci_get_page_var(),
									'meta_key'   => 'ci_cpt_event_date',
									'orderby'    => 'meta_value',
									'order'      => 'ASC',
									'meta_query' => array(
										array(
											'key'     => 'ci_cpt_event_date',
											'value'   => date_i18n( 'Y-m-d' ),
											'compare' => '>=',
											'type'    => 'date'
										)
									)
								);

								if ( $posts_per_page >= 1 ) {
									$date_params['posts_per_page'] = $posts_per_page;
								} elseif ( $posts_per_page <= -1 ) {
									$date_params['posts_per_page'] = -1;
								} else {
									$date_params['posts_per_page'] = get_option('posts_per_page');
								}

								if( 'on' == $isotope ) {
									$date_params['posts_per_page'] = -1;
								}

								$future_events = merge_wp_queries($recurrent_params, $date_params);

								/*
								 * These are needed purely for the pagination of Upcoming events.
								 * Since $future_events is a merged query with posts_per_page = -1
								 * ci_pagination() gets confused, so this is needed to pass the correct values.
								 */
								$date_params['fields'] = 'ids';
								$dated_events = new WP_Query( $date_params );
							?>

							<?php if ( 'on' == $isotope ): ?>
								<ul class="filters-nav group">
									<li><a href="#filter" class="selected btn small transparent" data-filter="*"><?php _e( 'All Items', 'ci_theme' ); ?></a></li>
									<?php
										$cats = array();
										foreach ( $future_events->posts as $p ) {
											$p_cats = wp_get_object_terms( $p->ID, $cpt_taxonomy );
											foreach ( $p_cats as $p_cat ) {
												if ( !isset( $cats[$p_cat->slug] ) ) {
													$c = new stdClass();
													$c->slug = $p_cat->slug;
													$c->name = $p_cat->name;
													$cats[ $p_cat->slug ] = $c;
												}
											}
										}
									?>
									<?php foreach ( $cats as $cat ): ?>
										<li><a href="#filter" class="btn small transparent" data-filter=".<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>


							<div class="row item-list <?php echo $div_class; ?>">

								<?php while( $future_events->have_posts() ): $future_events->the_post(); ?>

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
							<?php ci_pagination( array(), $dated_events ); ?>
						</div>
					<?php endif; ?>

					<?php if( 'on' == $events_past ): ?>
						<div class="event-list">
							<h3 class="section-title"><?php echo $events_past_title; ?></h3>

							<?php
								$past_events_args = array(
									'post_type'    => $cpt,
									'paged'        => ci_get_page_var(),
									'meta_key'     => 'ci_cpt_event_date',
									'meta_value'   => date_i18n( 'Y-m-d' ),
									'meta_compare' => '<',
									'orderby'      => 'meta_value',
									'order'        => 'DESC',
								);

								if ( $posts_per_page >= 1 ) {
									$past_events_args['posts_per_page'] = $posts_per_page;
								} elseif ( $posts_per_page <= -1 ) {
									$past_events_args['posts_per_page'] = -1;
								}

								if( 'on' == $isotope ) {
									$past_events_args['posts_per_page'] = -1;
								}

								$past_events = new WP_Query( $past_events_args );
							?>

							<?php if ( 'on' == $isotope ): ?>
								<ul class="filters-nav group">
									<li><a href="#filter" class="selected btn small transparent" data-filter="*"><?php _e( 'All Items', 'ci_theme' ); ?></a></li>
									<?php
										$cats = array();
										foreach ( $past_events->posts as $p ) {
											$p_cats = wp_get_object_terms( $p->ID, $cpt_taxonomy );
											foreach ( $p_cats as $p_cat ) {
												if ( !isset( $cats[$p_cat->slug] ) ) {
													$c = new stdClass();
													$c->slug = $p_cat->slug;
													$c->name = $p_cat->name;
													$cats[ $p_cat->slug ] = $c;
												}
											}
										}
									?>
									<?php foreach ( $cats as $cat ): ?>
										<li><a href="#filter" class="btn small transparent" data-filter=".<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>


							<div class="row item-list <?php echo $div_class; ?>">
								<?php while( $past_events->have_posts() ): $past_events->the_post(); ?>

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

											<div class="item-info">
												<?php
													$event_date      = get_post_meta( get_the_ID(), 'ci_cpt_event_date', true );
													$event_time      = get_post_meta( get_the_ID(), 'ci_cpt_event_time', true );
													$event_timestamp = strtotime( $event_date . ' ' . $event_time, current_time( 'timestamp' ) );
													$event_location  = get_post_meta( get_the_ID(), 'ci_cpt_event_location', true );
													$recurrent       = get_post_meta( $post->ID, 'ci_cpt_event_recurrent', true ) == 'enabled' ? true : false;
													$recurrence      = get_post_meta( $post->ID, 'ci_cpt_event_recurrence', true );
												?>
												<?php if( $recurrent ): ?>
													<p class="item-title-intro"><?php echo $recurrence; ?></p>
												<?php else: ?>
													<p class="item-title-intro"><?php echo date_i18n( get_option('date_format'), $event_timestamp ); ?></p>
												<?php endif; ?>
												<p class="item-title-main"><?php the_title(); ?></p>
												<p class="item-byline"><?php echo $event_location; ?></p>
											</div>

											<a class="btn item-btn" href="<?php the_permalink(); ?>"><?php echo ci_get_read_more_text( get_post_type() ); ?></a>
										</div>
									</div>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>

							</div>
							<?php ci_pagination( array(), $past_events ); ?>
						</div>
					<?php endif; ?>

				</div>
			</div>
		</div>
	</main>

<?php endwhile; ?>

<?php get_footer(); ?>