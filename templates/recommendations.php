<?php
$divid = wp_generate_password(12, false);
?>
<div class="linkedin">
	<div id="<?php echo $divid; ?>" class="scrollable" style="width:<?php  echo $width; ?>px;">
		<div class="items">
		<?php foreach ($recommendations as $recommendation): ?>
			<blockquote style="width:<?php  echo $width - 25; ?>px">
				<p><?php  wp_linkedin_excerpt($recommendation->recommendationText, $length); ?></p>
				<small><?php echo $recommendation->recommender->firstName; ?> <?php echo $recommendation->recommender->lastName; ?></small>
			</blockquote>
		<?php  endforeach; ?>
		</div>
	</div>
</div>
<script>
(function($) {
	var h = 0;
	$('#<?php echo $divid; ?> .items blockquote').each(function() {
		h = Math.max(h, $(this).height());
	});
	$('#<?php echo $divid; ?>').height(h);
	$('#<?php echo $divid; ?>').scrollable({circular:true}).autoscroll({autoplay:true,autopause:true});
})(jQuery);
</script>

<!--
height:<?php  echo $height; ?>px
 style="max-height:<?php  echo $height - 18; ?>px"
-->