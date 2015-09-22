<?php
/*
 * Template Name: Homepage Template
 */
?>

<?php get_header(); ?>

<?php $slides = ci_theme_get_slides( false, get_queried_object_id() ); ?>
<?php if( $slides->have_posts() ): ?>
	<div id="home-slider" class="flexslider loading">
		<ul class="slides">
			<?php while( $slides->have_posts() ): $slides->the_post(); ?>
				<?php
					$url       = esc_url( get_post_meta( get_the_ID(), 'ci_cpt_slider_url', true ) );
					$video_url = get_post_meta( get_the_ID(), 'ci_cpt_slider_video_url', true );
				?>
				<li style="background-image: url('<?php echo esc_url( ci_get_featured_image_src( 'ci_slider' ) ); ?>');">
					<?php if ( empty( $video_url ) ) : ?>
						<div class="slide-content">
							<h3 class="slide-title"><?php the_title(); ?></h3>
							<p class="slide-subtitle"><?php echo get_post_meta( get_the_ID(), 'ci_cpt_slider_text', true ); ?></p>
							<?php if( !empty( $url ) ): ?>
								<a class="btn btn-lg" href="<?php echo $url; ?>"><?php ci_e_setting( 'read_more_text' ); ?></a>
							<?php endif; ?>
						</div>
					<?php else : ?>
						<div class="slide-video-wrap">
							<?php echo wp_oembed_get( $video_url, array( 'height' => 440 ) ); ?>
						</div>
					<?php endif; ?>
				</li>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</ul>
	</div>
<?php endif; ?>

<?php get_template_part( 'inc_hero_player' ); ?>

<main id="main" class="home-sections">
	<?php dynamic_sidebar( 'frontpage-widgets' ); ?>
</main>

<?php get_footer(); ?>