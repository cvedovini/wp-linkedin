<?php
if (!function_exists('profile_name')) {
	function profile_name($v) {
		if (isset($v->siteStandardProfileRequest->url)) {
			return sprintf('<a href="%3$s">%1$s %2$s</a>', $v->firstName, $v->lastName, $v->siteStandardProfileRequest->url);
		} else {
			return sprintf('%1$s %2$s', $v->firstName, $v->lastName);
		}
	}
}

if (!function_exists('find_links')) {
	function find_links($v) {
		$regex = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/i';
		return preg_replace($regex, '<a href="\\0">\\0</a>', $v);
	}
}
?>

<div class="linkedin"><ul class="updates">
<?php foreach ($updates->values as $update):
$p = isset($update->updateContent->person) ? $update->updateContent->person : '';
if (is_object($p) && ($p->firstName == 'private')) continue;

switch ($update->updateType) {
	case 'CONN':
		foreach ($p->connections->values as $c) {
			echo '<li class="type-'.strtolower($update->updateType) . '">';
			printf(__('%1$s is now connected to %2$s.', 'wp-linkedin'), profile_name($p), profile_name($c));
			echo '</li>';
		}
		break;
	case 'NCON':
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%s is now a connection.', 'wp-linkedin'), profile_name($p));
		echo '</li>';
		break;
	case 'CCEM':
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%s has joined LinkedIn.', 'wp-linkedin'), profile_name($p));
		echo '</li>';
		break;
	case 'SHAR':
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%1$s says: %2$s', 'wp-linkedin'), profile_name($p), find_links($p->currentShare->comment));
		echo '</li>';
		break;
	case 'STAT':
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%1$s says: %2$s', 'wp-linkedin'), profile_name($p), find_links($p->currentStatus));
		echo '</li>';
		break;
	case 'VIRL':
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%1$s likes: %4$s', 'wp-linkedin'), profile_name($p),
				find_links($p->updateAction->originalUpdate->updateContent->currentShare->comment));
		echo '</li>';
		break;
	case 'JGRP':
		foreach ($p->memberGroups->values as $g) {
			echo '<li class="type-'.strtolower($update->updateType) . '">';
			printf(__('%1$s joined the group %2$s.', 'wp-linkedin'), profile_name($p), $g->name);
			echo '</li>';
		}
		break;
	case 'APPS':
	case 'APPM':
		foreach ($p->personActivities->values as $a) {
			echo '<li class="type-'.strtolower($update->updateType) . '">';
			echo $a->body;
			echo '</li>';
		}
		break;
	case 'PICU':
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%s has a new profile picture.', 'wp-linkedin'), profile_name($p));
		echo '</li>';
		break;
	case 'PROF':
	case 'PRFU':
	case 'PRFX':
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%s has an updated profile.', 'wp-linkedin'), profile_name($p));
		echo '</li>';
		break;
	case 'PREC':
	case 'SVPR':
		foreach ($p->recommendationsGiven->values as $r) {
			echo '<li class="type-'.strtolower($update->updateType) . '">';
			printf(__('%1$s recommends %2$s.', 'wp-linkedin'), profile_name($p), profile_name($r->recommendee));
			echo '</li>';
		}
		break;
	case 'JOBP':
		$j = $update->updateContent->job;
		$p = $j->jobPoster;
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%1$s posted a job: %2$s at %3$s.', 'wp-linkedin'), profile_name($p),
				$j->position->title, $j->company->name);
		echo '</li>';
		break;
	case 'MSFC':
		$p = $update->updateContent->companyPersonUpdate->person;
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%1$s is now following %2$s.', 'wp-linkedin'), profile_name($p),
				$update->updateContent->company->name);
		echo '</li>';
		break;
	case 'CMPY':
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		printf(__('%s has an updated profile.', 'wp-linkedin'),
				$update->updateContent->company->name);
		echo '</li>';
		break;
	default:
		echo '<li class="type-'.strtolower($update->updateType) . '">';
		echo $update->updateType;
		echo '</li>';
}
?></li>
<?php endforeach; ?>
</ul></div>
