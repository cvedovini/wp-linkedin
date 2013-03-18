<?php

class WPLinkedInAdmin {

	function WPLinkedInAdmin($plugin) {
		$this->plugin = $plugin;
		add_submenu_page('options-general.php', __('LinkedIn Options', 'wp-linkedin'), __('LinkedIn', 'wp-linkedin'), 'manage_options', 'wp-linkedin', array(&$this, 'options_page'));
	}

	function options_page() { ?>
<div class="wrap">
<h2><?php _e('LinkedIn Options', 'wp-linkedin'); ?></h2>
<p><?php _e('Go to the <a href="https://www.linkedin.com/secure/developer">LinkedIn Developer Network</a> to generate the following keys.', 'wp-linkedin'); ?></p>
<form method="post" action="options.php"><?php wp_nonce_field('update-options'); ?>

<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e('Application key', 'wp-linkedin'); ?></th>
		<td><input id="wp-linkedin_appkey" name="wp-linkedin_appkey" type="text"
				value="<?php echo get_option('wp-linkedin_appkey'); ?>" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Application secret', 'wp-linkedin'); ?></th>
		<td><input id="wp-linkedin_appsecret" name="wp-linkedin_appsecret" type="text"
				value="<?php echo get_option('wp-linkedin_appsecret'); ?>" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('User token', 'wp-linkedin'); ?></th>
		<td><input id="wp-linkedin_usertoken" name="wp-linkedin_usertoken" type="text"
				value="<?php echo get_option('wp-linkedin_usertoken'); ?>" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('User secret', 'wp-linkedin'); ?></th>
		<td><input id="wp-linkedin_usersecret" name="wp-linkedin_usersecret" type="text"
				value="<?php echo get_option('wp-linkedin_usersecret'); ?>" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Profile fields', 'wp-linkedin'); ?></th>
		<td><textarea id="wp-linkedin_fields" name="wp-linkedin_fields" rows="5"
				cols="50"><?php echo get_option('wp-linkedin_fields', LINKEDIN_FIELDS_DEFAULT); ?></textarea>
			<p><em><?php _e('Comma separated list of fields to show on the profile.', 'wp-linkedin'); ?><br/>
			<?php _e('You can overide this setting in the shortcode with the `fields` attribute.', 'wp-linkedin'); ?><br/>
			<?php _e('See the <a href="https://developers.linkedin.com/documents/profile-fields" target="_blank">LinkedIn API documentation</a> for the complete list of fields.', 'wp-linkedin'); ?></em></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Profile language', 'wp-linkedin'); ?></th>
		<td><select id="wp-linkedin_profilelanguage" name="wp-linkedin_profilelanguage">
			<?php
				$lang = get_option('wp-linkedin_profilelanguage');
				$languages = $this->getLanguages();

				foreach ($languages as $k => $v) {
					$selected = ($k == $lang) ? ' selected=""' : '';
					echo '<option value="'.$k.'"'.$selected.'>'.__($v, 'wp-linkedin').'</option>';
				}
			?>
			</select>
			<p><em><?php _e('The language of the profile to display if you have several profiles in different languages.', 'wp-linkedin'); ?><br/>
			<?php _e('You can overide this setting in the shortcode with the `lang` attribute.', 'wp-linkedin'); ?><br/>
			<?php _e('See "Selecting the profile language" <a href="https://developer.linkedin.com/documents/profile-api" target="_blank">LinkedIn API documentation</a> for details.', 'wp-linkedin'); ?></em></p>
		</td>
	</tr>
</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="wp-linkedin_appkey,wp-linkedin_appsecret,wp-linkedin_usertoken,wp-linkedin_usersecret,wp-linkedin_fields,wp-linkedin_profilelanguage" />
<p class="submit"><input type="submit" name="submit"
	value="<?php _e('Save'); ?>" /></p>
</form>
</div><?php
	}

	function getLanguages() {
		return array(
				'' => 'Default',
				'in_ID' => 'Bahasa Indonesia',
				'cs_CZ' => 'Czech',
				'da_DK' => 'Danish',
				'nl_NL' => 'Dutch',
				'fr_FR' => 'French',
				'de_DE' => 'German',
				'it_IT' => 'Italian',
				'ja_JP' => 'Japanese',
				'ko_KR' => 'Korean',
				'ms_MY' => 'Malay',
				'no_NO' => 'Norwegian',
				'pl_PL' => 'Polish',
				'pt_BR' => 'Portuguese',
				'ro_RO' => 'Romanian',
				'ru_RU' => 'Russian',
				'es_ES' => 'Spanish',
				'sv_SE' => 'Swedish',
				'tr_TR' => 'Turkish',
				'sq_AL' => 'Albanian',
				'hy_AM' => 'Armenian',
				'bs_BA' => 'Bosnian',
				'my_MM' => 'Burmese (Myanmar)',
				'zh_CN' => 'Chinese (Simplified)',
				'zh_TW' => 'Chinese (Traditional)',
				'hr_HR' => 'Croatian',
				'fi_FI' => 'Finnish',
				'el_GR' => 'Greek',
				'hi_IN' => 'Hindi',
				'hu_HU' => 'Hungarian',
				'is_IS' => 'Icelandic',
				'jv_JV' => 'Javanese',
				'kn_IN' => 'Kannada',
				'lv_LV' => 'Latvian',
				'lt_LT' => 'Lithuanian',
				'ml_IN' => 'Malayalam',
				'sr_BA' => 'Serbian',
				'sk_SK' => 'Slovak',
				'tl_PH' => 'Tagalog',
				'ta_IN' => 'Tamil',
				'te_IN' => 'Telugu',
				'th_TH' => 'Thai',
				'uk_UA' => 'Ukrainian',
				'vi_VN' => 'Vietnamese',
				'xx_XX' => 'Other'
		);
	}
}