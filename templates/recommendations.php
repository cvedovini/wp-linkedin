<?php
	$divid = wp_generate_password(12, false);
?>
<div class="linkedin">
	<div id="<?php echo $divid; ?>" class="scrollable" style="width:<?php  echo $width; ?>px;">
		<div class="items">
		<?php foreach ($recommendations as $recommendation): ?>
			<blockquote>
				<div class="recommendation"><?php  wp_linkedin_excerpt($recommendation->recommendationText, $length); ?></div>
				<div class="recommender"><a href="<?php echo $recommendation->recommender->publicProfileUrl; ?>"
					target="_blank"><?php echo $recommendation->recommender->firstName; ?> <?php echo $recommendation->recommender->lastName; ?></a></div>
			</blockquote>
		<?php  endforeach; ?>
		</div>
	</div>
</div>
<script>
(function($) {
	var h = 0;
	var $div = $('#<?php echo $divid; ?>');
	<?php if ($width === 'auto'):
		echo 'var width = $div.innerWidth();';
	else:
		echo 'var width = ' . $width . ';';
	endif; ?>

	$('.items blockquote', $div).each(function() {
		var $this = $(this);
		$this.outerWidth(width, true);
		h = Math.max(h, $this.height());
	});
	$('#<?php echo $divid; ?>').height(h);
	$('#<?php echo $divid; ?>').scrollable({circular:true}).autoscroll({autoplay:true,autopause:true,interval:<?php  echo $interval; ?>});
})(jQuery);
</script>
