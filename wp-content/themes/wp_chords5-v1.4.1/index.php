<?php get_header(); ?>

<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-sm-7 <?php echo ci_get_layout_classes( 'blog', 'content' ); ?>">
				<?php get_template_part('inc_section_titles'); ?>

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry') ; ?>>
						<?php if ( has_post_thumbnail() ) : ?>
						<figure class="entry-thumb">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('ci_blog_thumb'); ?>
							</a>
						</figure>
						<?php endif; ?>

						<h1 class="entry-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h1>

						<div class="entry-meta">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="entry-time"><?php echo esc_html( get_the_date() ); ?></time>
							&bull;
							<?php the_category( ', ' ); ?>
							&bull;
							<a href="<?php comments_link(); ?>"><?php comments_number( __( 'No Comments', 'ci_theme' ), __( '1 Comment', 'ci_theme' ), __( '% Comments', 'ci_theme' ) ); ?></a>
						</div>

						<div class="entry-content">
							<?php ci_e_content(); ?>
						</div>

						<?php ci_read_more(); ?>
					</article>
				<?php endwhile; endif; ?>

				<?php ci_pagination(); ?>
			</div>

			<div class="col-md-4 col-sm-5 <?php echo ci_get_layout_classes( 'blog', 'sidebar' ); ?>">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>