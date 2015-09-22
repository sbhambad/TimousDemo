<?php
	$event_date      = get_post_meta( get_the_ID(), 'ci_cpt_event_date', true );
	$event_time      = get_post_meta( get_the_ID(), 'ci_cpt_event_time', true );
	$event_timestamp = strtotime( $event_date . ' ' . $event_time, current_time( 'timestamp' ) );
	$event_location  = get_post_meta( get_the_ID(), 'ci_cpt_event_location', true );
	$event_venue     = get_post_meta( get_the_ID(), 'ci_cpt_event_venue', true );
	$recurrent       = get_post_meta( get_the_ID(), 'ci_cpt_event_recurrent', true ) == 'enabled' ? true : false;
	$recurrence      = get_post_meta( get_the_ID(), 'ci_cpt_event_recurrence', true );
	$event_lon       = get_post_meta( get_the_ID(), 'ci_cpt_event_lon', true );
	$event_lat       = get_post_meta( get_the_ID(), 'ci_cpt_event_lat', true );
	$event_fields    = get_post_meta( get_the_ID(), "ci_cpt_event_fields", true );

	if( $event_timestamp > current_time( 'timestamp' ) || $recurrent ) {
		$event_wording = get_post_meta( get_the_ID(), 'ci_cpt_event_upcoming_button', true );
		$event_url = esc_url( get_post_meta( get_the_ID(), 'ci_cpt_event_upcoming_url', true ) );
	} else {
		$event_wording = get_post_meta( get_the_ID(), 'ci_cpt_event_past_button', true );
		$event_url = esc_url( get_post_meta( get_the_ID(), 'ci_cpt_event_past_url', true ) );
	}
?>
<?php if( ! $recurrent ):  ?>
	<?php $remaining = ci_get_remaining_time_array( $event_date, $event_time ); ?>
	<?php if( $remaining ): ?>
		<div class="item-timer">
			<div class="count">
				<b><?php echo intval( $remaining['days'] ); ?></b>
				<span><?php _e('Days', 'ci_theme'); ?></span>
			</div>
			<div class="count">
				<b><?php echo intval( $remaining['hours'] ); ?></b>
				<span><?php _e('Hours', 'ci_theme'); ?></span>
			</div>
			<div class="count">
				<b><?php echo intval( $remaining['minutes'] ); ?></b>
				<span><?php _e('Minutes', 'ci_theme'); ?></span>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>

<table class="item-meta">
	<tbody>
		<?php if ( !empty( $event_fields ) && is_array( $event_fields ) ): ?>
			<?php foreach( $event_fields as $field): ?>
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

		<?php if ( !empty( $event_wording ) ): ?>
			<tr>
				<th></th>
				<td class="action">
					<?php if( !empty( $event_url ) ): ?>
						<a class="btn" href="<?php echo $event_url; ?>"><?php echo $event_wording; ?></a>
					<?php else: ?>
						<span class="btn"><?php echo $event_wording; ?></span>
					<?php endif; ?>
				</td>
			</tr>
		<?php endif; ?>

	</tbody>
</table>
