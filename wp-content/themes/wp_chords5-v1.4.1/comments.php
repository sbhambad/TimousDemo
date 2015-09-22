<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!', 'ci_theme'));

	if ( post_password_required() )
		return;
?>

<?php ob_start(); ?>

<?php if (have_comments()): ?>
	<div class="post-comments group">
		<h3><?php comments_number(__('No comments', 'ci_theme'), __('1 comment', 'ci_theme'), __('% comments', 'ci_theme')); ?></h3>
		<div class="comments-pagination"><?php paginate_comments_links(); ?></div>
		<ol id="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'type'        => 'comment',
					'avatar_size' => 64
				) );
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'type'       => 'pings'
				) );
			?>
		</ol>
		<div class="comments-pagination"><?php paginate_comments_links(); ?></div>
	</div><!-- .post-comments -->
<?php else: ?>
	<?php if ( !comments_open() and ci_setting('comments_off_message')=='enabled' ): ?>
		<div class="post-comments">
			<p><?php _e('Comments are closed.', 'ci_theme'); ?></p>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ): ?>
	<section id="respond">
		<div id="form-wrapper" class="group">
			<?php comment_form(); ?>
		</div><!-- #form-wrapper -->
	</section>
<?php endif; ?>

<?php $comments_output = trim( ob_get_clean() ); ?>
<?php if( !empty($comments_output) ): ?>
	<div id="comments">
		<?php echo $comments_output; ?>
	</div>
<?php endif; ?>