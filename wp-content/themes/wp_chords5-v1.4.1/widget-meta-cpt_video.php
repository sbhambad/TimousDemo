<?php
	$video_date      = get_post_meta( get_the_ID(), 'ci_cpt_video_date', true );
	$video_timestamp = strtotime( $video_date, current_time( 'timestamp' ) );
	$video_fields    = get_post_meta( $post->ID, "ci_cpt_video_fields", true );
?>
<table class="item-meta">
	<tbody>
		<?php if( !empty( $video_date ) ): ?>
			<tr>
				<th><?php _e('Date', 'ci_theme'); ?></th>
				<td><?php echo date_i18n( get_option('date_format'), $video_timestamp ); ?></td>
			</tr>
		<?php endif; ?>

		<?php if ( has_term( '', 'video-category', get_the_ID() ) ): ?>
			<tr>
				<th><?php _e('Category', 'ci_theme'); ?></th>
				<td><?php the_terms(get_the_ID(), 'video-category', '', ', '); ?></td>
			</tr>
		<?php endif; ?>

		<?php if ( !empty( $video_fields ) && is_array( $video_fields ) ): ?>
			<?php foreach( $video_fields as $field): ?>
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
