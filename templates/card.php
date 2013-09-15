<div class="linkedin"><div class="card">
<div class="cartouche" style="padding-left:<?php echo ($picture_width + 8); ?>px">
	<img class="picture" style="margin-left:-<?php echo ($picture_width + 8); ?>px" src="<?php echo $profile->pictureUrl; ?>" width="<?php echo $picture_width; ?>px"/>
	<div class="name"><a href="<?php echo $profile->publicProfileUrl; ?>"><?php echo $profile->firstName; ?> <?php echo $profile->lastName; ?></a></div>
	<div class="headline"><?php echo $profile->headline; ?></div>
</div>

<?php if (isset($profile->summary) && $summary_length): ?>
<div class="summary"><?php echo nl2br(wp_linkedin_excerpt($profile->summary, $summary_length)); ?></div>
<?php endif; ?>

</div></div>
