<?php
	wp_enqueue_style('wp-linkedin', plugins_url('wp-linkedin/style.css'), false, '1.0.0');

?>
<div class="linkedin"><div class="profile">
<div class="cartouche">
	<img class="picture" src="<?php echo $profile->pictureUrl; ?>" width="80px" height="80px" />
	<div class="name"><a href="<?php echo $profile->publicProfileUrl; ?>"><?php echo $profile->firstName; ?> <?php echo $profile->lastName; ?></a></div>
	<div class="headline"><?php echo $profile->headline; ?></div>
	<div class="location"><?php echo $profile->location->name; ?> | <?php echo $profile->industry; ?></div>
</div>

<?php if (isset($profile->summary)): ?>
<div class="section">
<div class="heading"><?php _e('Summary', 'wp-linkedin'); ?></div>
<div class="summary"><?php echo $profile->summary; ?></div>
</div>
<?php endif; ?>

<?php if (isset($profile->specialties)): ?>
<div class="section">
<div class="heading"><?php _e('Specialties', 'wp-linkedin'); ?></div>
<div class="specialties"><?php echo $profile->specialties; ?></div>
</div>
<?php endif; ?>

<?php if (isset($profile->positions) && is_array($profile->positions->values)): ?>
<div class="section">
<div class="heading"><?php _e('Experience', 'wp-linkedin'); ?></div>
<?php foreach ($profile->positions->values as $v): ?>
<div class="position">
	<div class="title"><strong><?php echo $v->title; ?></strong> (<?php echo $v->startDate->year; ?> - <?php echo isset($v->endDate) ? $v->endDate->year : __('Present', 'wp-linkedin'); ?>)</div>
	<div class="company"><?php echo $v->company->name; ?></div>
	<div class="industry"><?php if (isset($v->company->type)) { echo $v->company->type.', '; } if (isset($v->company->size)) { echo $v->company->size.', '; } echo $v->company->industry; ?></div>
	<?php if (isset($v->summary)): ?>
		<div class="summary"><?php echo $v->summary; ?></div>
	<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (isset($profile->skills) && is_array($profile->skills->values)): ?>
<div class="section">
<div class="heading"><?php _e('Skills &amp; Expertise', 'wp-linkedin'); ?></div>
<?php
$skills = array();
foreach ($profile->skills->values as $v) {
	$skills[] = '<span class="skill">'.$v->skill->name.'</span>';
} ?>
<p><?php echo implode(', ', $skills); ?></p>
</div>
<?php endif;?>

<?php if (isset($profile->languages) && is_array($profile->languages->values)): ?>
<div class="section">
<div class="heading"><?php _e('Languages', 'wp-linkedin'); ?></div>
<ul>
<?php foreach ($profile->languages->values as $v): ?>
<li class="language"><?php echo $v->language->name; ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif;?>

<?php if (isset($profile->educations) && is_array($profile->educations->values)): ?>
<div class="section">
<div class="heading"><?php _e('Education', 'wp-linkedin'); ?></div>
<?php foreach ($profile->educations->values as $v): ?>
<div class="education">
	<div class="school"><strong><?php echo $v->schoolName; ?></strong> (<?php echo $v->startDate->year; ?> - <?php echo isset($v->endDate) ? $v->endDate->year : __('Present', 'wp-linkedin'); ?>)</div>
	<div class="degree"><?php echo $v->degree; ?>, <?php echo $v->fieldOfStudy; ?></div>
	<?php if (isset($v->activities)): ?>
		<div class="activities"><em><?php _e('Activities and societies', 'wp-linkedin'); ?>:</em> <?php echo $v->activities; ?></div>
	<?php endif; ?>
	<?php if (isset($v->notes)): ?>
		<div class="notes"><?php echo $v->notes; ?></div>
	<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (isset($profile->recommendationsReceived) && is_array($profile->recommendationsReceived->values)): ?>
<div class="section">
<div class="heading"><?php _e('Recommendations', 'wp-linkedin'); ?></div>
<?php foreach ($profile->recommendationsReceived->values as $v): ?>
<blockquote class="recommendation">
	<p><?php  echo $v->recommendationText; ?></p>
	<small><?php echo $v->recommender->firstName; ?> <?php echo $v->recommender->lastName; ?></small>
</blockquote>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div></div>