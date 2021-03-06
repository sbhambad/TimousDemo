<?php get_header(); ?>

<main id="main">
	<div class="container">
		<?php while( have_posts() ): the_post(); ?>
			<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry') ; ?>>
				<div class="row">
					<div class="col-xs-12">
						<h1 class="section-title"><?php the_title(); ?></h1>
					</div>

					<?php $video_url = get_post_meta( get_the_ID(), 'ci_cpt_video_url', true ); ?>

					<?php if( !empty( $video_url ) ): ?>
						<div class="col-xs-12">
							<div class="video-wrap">
								<?php echo wp_oembed_get($video_url); ?>
							</div>
						</div>
					<?php endif; ?>

					<div class="col-md-4 col-sm-5 col-xs-12 <?php echo ci_get_layout_classes( 'cpt', 'sidebar' ); ?>">
						<div class="sidebar">
							<div class="item">

								<?php get_template_part( 'single-meta', get_post_type() ); ?>

							</div>
						</div>
					</div>

					<div class="col-md-8 col-sm-7 col-xs-12 <?php echo ci_get_layout_classes( 'cpt', 'content' ); ?>">
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