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
				'' => __('Default', 'wp-linkedin'),
				'in_ID' => __('Bahasa Indonesia', 'wp-linkedin'),
				'cs_CZ' => __('Czech', 'wp-linkedin'),
				'da_DK' => __('Danish', 'wp-linkedin'),
				'nl_NL' => __('Dutch', 'wp-linkedin'),
				'fr_FR' => __('French', 'wp-linkedin'),
				'de_DE' => __('German', 'wp-linkedin'),
				'it_IT' => __('Italian', 'wp-linkedin'),
				'ja_JP' => __('Japanese', 'wp-linkedin'),
				'ko_KR' => __('Korean', 'wp-linkedin'),
				'ms_MY' => __('Malay', 'wp-linkedin'),
				'no_NO' => __('Norwegian', 'wp-linkedin'),
				'pl_PL' => __('Polish', 'wp-linkedin'),
				'pt_BR' => __('Portuguese', 'wp-linkedin'),
				'ro_RO' => __('Romanian', 'wp-linkedin'),
				'ru_RU' => __('Russian', 'wp-linkedin'),
				'es_ES' => __('Spanish', 'wp-linkedin'),
				'sv_SE' => __('Swedish', 'wp-linkedin'),
				'tr_TR' => __('Turkish', 'wp-linkedin'),
				'sq_AL' => __('Albanian', 'wp-linkedin'),
				'hy_AM' => __('Armenian', 'wp-linkedin'),
				'bs_BA' => __('Bosnian', 'wp-linkedin'),
				'my_MM' => __('Burmese (Myanmar)', 'wp-linkedin'),
				'zh_CN' => __('Chinese (Simplified)', 'wp-linkedin'),
				'zh_TW' => __('Chinese (Traditional)', 'wp-linkedin'),
				'hr_HR' => __('Croatian', 'wp-linkedin'),
				'fi_FI' => __('Finnish', 'wp-linkedin'),
				'el_GR' => __('Greek', 'wp-linkedin'),
				'hi_IN' => __('Hindi', 'wp-linkedin'),
				'hu_HU' => __('Hungarian', 'wp-linkedin'),
				'is_IS' => __('Icelandic', 'wp-linkedin'),
				'jv_JV' => __('Javanese', 'wp-linkedin'),
				'kn_IN' => __('Kannada', 'wp-linkedin'),
				'lv_LV' => __('Latvian', 'wp-linkedin'),
				'lt_LT' => __('Lithuanian', 'wp-linkedin'),
				'ml_IN' => __('Malayalam', 'wp-linkedin'),
				'sr_BA' => __('Serbian', 'wp-linkedin'),
				'sk_SK' => __('Slovak', 'wp-linkedin'),
				'tl_PH' => __('Tagalog', 'wp-linkedin'),
				'ta_IN' => __('Tamil', 'wp-linkedin'),
				'te_IN' => __('Telugu', 'wp-linkedin'),
				'th_TH' => __('Thai', 'wp-linkedin'),
				'uk_UA' => __('Ukrainian', 'wp-linkedin'),
				'vi_VN' => __('Vietnamese', 'wp-linkedin'),
				'xx_XX' => __('Other', 'wp-linkedin')
		);
	}
}