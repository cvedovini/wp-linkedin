<div class="linkedin"><div class="profile">
<div id ="cartouche" class="section">
	<img class="picture" src="<?php echo $profile->pictureUrl; ?>" width="80px" />
	<div class="name"><a href="<?php echo $profile->publicProfileUrl; ?>"><?php echo $profile->firstName; ?> <?php echo $profile->lastName; ?></a></div>
	<div class="headline"><?php echo $profile->headline; ?></div>
	<div class="location"><?php echo $profile->location->name; ?> | <?php echo $profile->industry; ?></div>
</div>

<?php if (isset($profile->summary)): ?>
<div id="summary" class="section">
<div class="heading"><?php _e('Summary', 'wp-linkedin'); ?></div>
<div class="summary"><?php echo nl2br($profile->summary); ?></div>
</div>
<?php endif; ?>

<?php if (isset($profile->specialties)): ?>
<div id="specialties" class="section">
<div class="heading"><?php _e('Specialties', 'wp-linkedin'); ?></div>
<div class="specialties"><?php echo nl2br($profile->specialties); ?></div>
</div>
<?php endif; ?>

<?php if (isset($profile->positions->values) && is_array($profile->positions->values)): ?>
<div id="positions" class="section">
<div class="heading"><?php _e('Experience', 'wp-linkedin'); ?></div>
<?php foreach ($profile->positions->values as $v): ?>
<div class="position">
	<div class="title"><strong><?php echo $v->title; ?></strong> (<?php echo $v->startDate->year; ?> - <?php echo isset($v->endDate) ? $v->endDate->year : __('Present', 'wp-linkedin'); ?>)</div>
	<div class="company"><?php echo $v->company->name; ?></div>
	<div class="industry"><?php if (isset($v->company->type)) { echo $v->company->type.', '; } if (isset($v->company->size)) { echo $v->company->size.', '; } echo $v->company->industry; ?></div>
	<?php if (isset($v->summary)): ?>
		<div class="summary"><?php echo nl2br($v->summary); ?></div>
	<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (isset($profile->skills->values) && is_array($profile->skills->values)): ?>
<div id="skills" class="section">
<div class="heading"><?php _e('Skills &amp; Expertise', 'wp-linkedin'); ?></div>
<?php
$skills = array();
foreach ($profile->skills->values as $v) {
	$skills[] = '<span class="skill">'.$v->skill->name.'</span>';
} ?>
<p><?php echo implode(', ', $skills); ?></p>
</div>
<?php endif;?>

<?php if (isset($profile->languages->values) && is_array($profile->languages->values)): ?>
<div id="languages" class="section">
<div class="heading"><?php _e('Languages', 'wp-linkedin'); ?></div>
<ul>
<?php foreach ($profile->languages->values as $v): ?>
<li class="language"><?php echo $v->language->name; ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif;?>

<?php if (isset($profile->educations->values) && is_array($profile->educations->values)): ?>
<div id="educations" class="section">
<div class="heading"><?php _e('Education', 'wp-linkedin'); ?></div>
<?php foreach ($profile->educations->values as $v): ?>
<div class="education">
	<div class="school"><strong><?php echo $v->schoolName; ?></strong> (<?php echo $v->startDate->year; ?> - <?php echo isset($v->endDate) ? $v->endDate->year : __('Present', 'wp-linkedin'); ?>)</div>
	<div class="degree"><?php echo $v->degree; ?>, <?php echo $v->fieldOfStudy; ?></div>
	<?php if (isset($v->activities) && !empty($v->activities)): ?>
		<div class="activities"><em><?php _e('Activities and societies', 'wp-linkedin'); ?>:</em> <?php echo $v->activities; ?></div>
	<?php endif; ?>
	<?php if (isset($v->notes) && !empty($v->notes)): ?>
		<div class="notes"><?php echo nl2br($v->notes); ?></div>
	<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (isset($profile->recommendationsReceived->values) && is_array($profile->recommendationsReceived->values)): ?>
<div id="recommendations" class="section">
<div class="heading"><?php _e('Recommendations', 'wp-linkedin'); ?></div>
<?php foreach ($profile->recommendationsReceived->values as $v): ?>
<blockquote>
	<div class="recommendation"><?php  echo nl2br($v->recommendationText); ?></div>
	<div class="recommender"><?php
			if (isset($v->recommender->publicProfileUrl)) echo '<a href="' . $v->recommender->publicProfileUrl . '" target="_blank">';
			echo $v->recommender->firstName . ' ' . $v->recommender->lastName;
			if (isset($v->recommender->publicProfileUrl)) echo '</a>';
	?></div>
</blockquote>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div></div>
