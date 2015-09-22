	<?php
		if ( ! is_page_template( 'template-frontpage.php' ) ) {
			if ( is_active_sidebar( 'inner-sidebar' ) ) {
				get_sidebar( 'inner' );
			} else {
			?>
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<hr class="footer-separator">
					</div>
				</div>
			</div>
			<?php
			}
		}
	?>

	<footer id="footer">
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<?php dynamic_sidebar( 'footer-sidebar-one' ); ?>
				</div>

				<div class="col-sm-4">
					<?php dynamic_sidebar( 'footer-sidebar-two' ); ?>
				</div>

				<div class="col-sm-4">
					<?php dynamic_sidebar( 'footer-sidebar-three' ); ?>
				</div>
			</div>
		</div>

		<div class="container footer-credits">
			<div class="row">
				<div class="col-xs-12">
					<p><?php echo ci_footer(); ?></p>
				</div>
			</div>
		</div>
	</footer>

</div> <!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
