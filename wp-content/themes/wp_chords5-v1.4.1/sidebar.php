<div class="sidebar">
	<?php
		if ( is_page() ) {
			dynamic_sidebar('pages-sidebar');
		} else {
			dynamic_sidebar('blog-sidebar');
		}
	?>
</div>