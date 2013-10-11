<?php
	$divid = wp_generate_password(12, false);
?>
<div class="linkedin">
	<div id="<?php echo $divid; ?>" class="scrollable" <?php if (is_numeric($width)) { echo "style='width:{$width}px'"; } ?>>
		<div class="items">
		<?php foreach ($recommendations as $recommendation): ?>
			<blockquote>
				<div class="recommendation"><?php  wpautop(wp_linkedin_excerpt($recommendation->recommendationText, $length)); ?></div>
				<div class="recommender"><a href="<?php echo $recommendation->recommender->publicProfileUrl; ?>"
					target="_blank"><?php echo $recommendation->recommender->firstName; ?> <?php echo $recommendation->recommender->lastName; ?></a></div>
			</blockquote>
		<?php  endforeach; ?>
		</div>
	</div>
</div>
<script>
jQuery(document).ready(function($) {
	var h = 0;
	var $scrollable = $('#<?php echo $divid; ?>');
<?php if ($width === 'auto'): ?>
	var width = $scrollable.width();
<?php elseif (is_numeric($width)): ?>
	var width = <?php echo $width; ?>;
<?php endif; ?>
	$('.items blockquote', $scrollable).each(function() {
<?php if ($width !== 'css'): ?>
		$(this).outerWidth(width, true);
<?php endif; ?>
		h = Math.max(h, $(this).height());
	});
	$scrollable.height(h);
	$scrollable.scrollable({circular:true}).autoscroll({autoplay:true,autopause:true,interval:<?php  echo $interval; ?>});
});
</script>
