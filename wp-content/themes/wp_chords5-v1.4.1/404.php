<?php get_header(); ?>

<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-sm-7 <?php echo ci_get_layout_classes( 'blog', 'content' ); ?>">

				<?php get_template_part('inc_section_titles'); ?>

				<article class="entry">
					<div class="entry-content">
						<p><?php _e( 'Oh, no! The page you requested could not be found. Perhaps searching will help...', 'ci_theme' ); ?></p>
						<?php get_search_form(); ?>
					</div>
				</article>
			</div>

			<div class="col-md-4 col-sm-5 <?php echo ci_get_layout_classes( 'blog', 'sidebar' ); ?>">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>