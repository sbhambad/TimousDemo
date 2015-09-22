<?php $artist_fields = get_post_meta( $post->ID, "ci_cpt_artist_fields", true ); ?>

<table class="item-meta">
	<tbody>
		<?php if ( has_term( '', 'artist-category', get_the_ID() ) ): ?>
			<tr>
				<th><?php _e('Category', 'ci_theme'); ?></th>
				<td><?php the_terms(get_the_ID(), 'artist-category', '', ', '); ?></td>
			</tr>
		<?php endif; ?>

		<?php if ( !empty( $artist_fields ) && is_array( $artist_fields ) ): ?>
			<?php foreach( $artist_fields as $field): ?>
				<tr>
					<th><?php echo $field['title']; ?></th>
					<?php
						$td_class = '';
						if( ci_is_repeating_button( $field['description'] ) ) {
							$td_class = 'class="action"';
						}
					?>
					<td <?php echo $td_class; ?>><?php echo $field['description']; ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>
