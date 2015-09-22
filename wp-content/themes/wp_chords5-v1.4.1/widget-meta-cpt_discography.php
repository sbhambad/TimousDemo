<?php
	$disc_cat_no    = get_post_meta( get_the_ID(), 'ci_cpt_discography_cat_no', true );
	$disc_date      = get_post_meta( get_the_ID(), 'ci_cpt_discography_date', true );
	$disc_timestamp = strtotime( $disc_date, current_time( 'timestamp' ) );
	$disc_fields    = get_post_meta( get_the_ID(), 'ci_cpt_discography_fields', true );
	$disc_tracks    = get_post_meta( get_the_ID(), 'ci_cpt_discography_tracks', true );
?>
<table class="item-meta">
	<tbody>
		<?php if( !empty( $disc_date ) ): ?>
			<tr>
				<th><?php _e('Release Date', 'ci_theme'); ?></th>
				<td><?php echo date_i18n( get_option('date_format'), $disc_timestamp ); ?></td>
			</tr>
		<?php endif; ?>

		<?php if( !empty( $disc_cat_no ) ): ?>
			<tr>
				<th><?php _e('Catalog Number', 'ci_theme'); ?></th>
				<td><?php echo $disc_cat_no; ?></td>
			</tr>
		<?php endif; ?>

		<?php if ( !empty( $disc_fields ) && is_array( $disc_fields ) ): ?>
			<?php foreach( $disc_fields as $field): ?>
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
