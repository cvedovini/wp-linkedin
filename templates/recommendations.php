<?php
	wp_enqueue_script('responsive-scrollable');
	$divid = wp_generate_password(12, false);
?>
<div class="linkedin">
	<div id="<?php echo $divid; ?>" class="scrollable" style='width:<?php echo (is_numeric($width)) ? "{$width}px" : '100%'; ?>'>
		<div class="items">
		<?php foreach ($recommendations as $recommendation): ?>
			<blockquote>
				<div class="recommendation"><?php  wpautop(wp_linkedin_excerpt($recommendation->recommendationText, $length)); ?></div>
				<div class="recommender"><?php if (isset($recommendation->recommender->publicProfileUrl)): ?>
					<a href="<?php echo $recommendation->recommender->publicProfileUrl; ?>"
						target="_blank"><?php echo $recommendation->recommender->firstName; ?>
						<?php echo $recommendation->recommender->lastName; ?></a>
					<?php else: ?>
					<?php _e('Anonymous', 'wp-linkedin'); ?>
				<?php endif; ?></div>
			</blockquote>
		<?php  endforeach; ?>
		</div>
	</div>
</div>
<script>
jQuery(document).ready(function($) {
	$('#<?php echo $divid; ?>').responsiveScrollable(<?php echo is_numeric($width) ? $width : "'$width'"; ?>, <?php  echo $interval; ?>);
});
</script>
