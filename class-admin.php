<?php

class WPLinkedInAdmin {

	function WPLinkedInAdmin($plugin) {
		$this->plugin = $plugin;
		$this->oauth = new WPLinkedInOAuth();
		add_submenu_page('options-general.php', __('LinkedIn Options', 'wp-linkedin'), __('LinkedIn', 'wp-linkedin'), 'manage_options', 'wp-linkedin', array(&$this, 'options_page'));
		add_action('admin_notices', array(&$this, 'admin_notices'));
	}

	function get_authorization_url() {
		return $this->oauth->get_authorization_url(wp_create_nonce('linkedin-oauth'));
	}

	function admin_notices() {
		if (!$this->oauth->is_access_token_valid()) {
			$format = __('Your LinkedIn access token is invalid or has expired, please <a href="%s">click here</a> to get a new one.', 'wp-linkedin');
			$notice = sprintf($format, $this->get_authorization_url()); ?>
			<div class="error">
		        <p><?php echo $notice; ?></p>
		    </div>
		<?php }
	}

	function options_page() {
		if (isset($_GET['code']) && isset($_GET['state'])) {
			if (wp_verify_nonce($_GET['state'], 'linkedin-oauth')) {
				if ($this->oauth->set_access_token($_GET['code'])) {
					wp_redirect(site_url('/wp-admin/options-general.php?page=wp-linkedin&clear_cache'));
				} else {?>
					<div class="error"><p><?php _e('An error has occured while retreiving the access token, please try again.', 'wp-linkedin'); ?></p></div>
				<?php }
			} else { ?>
				<div class="error"><p><?php _e('Invalid state.', 'wp-linkedin'); ?></p></div>
			<?php }
		} elseif (isset($_GET['clear_cache'])) {
			$this->oauth->clear_cache();
		} ?>
<div class="wrap">
	<h2><?php _e('LinkedIn Options', 'wp-linkedin'); ?></h2>
	<div id="main-container" class="postbox-container metabox-holder" style="width:75%;"><div style="margin:0 8px;">
		<div class="postbox">
			<h3 style="cursor:default;"><span><?php _e('Options', 'wp-linkedin'); ?></span></h3>
			<div class="inside">
				<form method="post" action="options.php"><?php wp_nonce_field('update-options'); ?>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="wp-linkedin_fields,wp-linkedin_profilelanguage" />
				<table class="form-table">
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
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes'); ?>"></p>
				</form>
			</div> <!-- .inside -->
		</div> <!-- .postbox -->
		<div class="postbox">
			<h3 style="cursor:default;"><span><?php _e('Management', 'wp-linkedin'); ?></span></h3>
			<div class="inside">
				<p>
					<span class="submit"><a href="<?php echo $this->get_authorization_url(); ?>" class="button button-primary"><?php _e('Regenerate LinkedIn Access Token', 'wp-linkedin'); ?></a></span>
					<span class="submit"><a href="<?php echo site_url('/wp-admin/options-general.php?page=wp-linkedin&clear_cache'); ?>" class="button button-primary"><?php _e('Clear Profile Cache', 'wp-linkedin'); ?></a></span>
				</p>
			</div> <!-- .inside -->
		</div> <!-- .postbox -->
		</div></div> <!-- #main-container -->

	<div id="side-container" class="postbox-container metabox-holder" style="width:24%;"><div style="margin:0 8px;">
		<div class="postbox">
			<h3 style="cursor:default;"><span><?php _e('Do you like this Plugin?', 'wp-linkedin'); ?></span></h3>
			<div class="inside">
				<p><?php _e('Please consider a donation.', 'wp-linkedin'); ?></p>
				<div style="text-align:center">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
					">
					<input type="image" src="https://www.paypalobjects.com/en_US/CH/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
			</div> <!-- .inside -->
		</div> <!-- .postbox -->
		<div>
			<a class="twitter-timeline" href="https://twitter.com/cvedovini" data-widget-id="377037845489139712">Tweets by @cvedovini</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
	</div></div> <!-- #side-container -->

</div><?php
	}

	function getLanguages() {
		return array(
				'' => __('Default', 'wp-linkedin'),
				'in-ID' => __('Bahasa Indonesia', 'wp-linkedin'),
				'cs-CZ' => __('Czech', 'wp-linkedin'),
				'da-DK' => __('Danish', 'wp-linkedin'),
				'nl-NL' => __('Dutch', 'wp-linkedin'),
				'fr-FR' => __('French', 'wp-linkedin'),
				'de-DE' => __('German', 'wp-linkedin'),
				'it-IT' => __('Italian', 'wp-linkedin'),
				'ja-JP' => __('Japanese', 'wp-linkedin'),
				'ko-KR' => __('Korean', 'wp-linkedin'),
				'ms-MY' => __('Malay', 'wp-linkedin'),
				'no-NO' => __('Norwegian', 'wp-linkedin'),
				'pl-PL' => __('Polish', 'wp-linkedin'),
				'pt-BR' => __('Portuguese', 'wp-linkedin'),
				'ro-RO' => __('Romanian', 'wp-linkedin'),
				'ru-RU' => __('Russian', 'wp-linkedin'),
				'es-ES' => __('Spanish', 'wp-linkedin'),
				'sv-SE' => __('Swedish', 'wp-linkedin'),
				'tr-TR' => __('Turkish', 'wp-linkedin'),
				'sq-AL' => __('Albanian', 'wp-linkedin'),
				'hy-AM' => __('Armenian', 'wp-linkedin'),
				'bs-BA' => __('Bosnian', 'wp-linkedin'),
				'my-MM' => __('Burmese (Myanmar)', 'wp-linkedin'),
				'zh-CN' => __('Chinese (Simplified)', 'wp-linkedin'),
				'zh-TW' => __('Chinese (Traditional)', 'wp-linkedin'),
				'hr-HR' => __('Croatian', 'wp-linkedin'),
				'fi-FI' => __('Finnish', 'wp-linkedin'),
				'el-GR' => __('Greek', 'wp-linkedin'),
				'hi-IN' => __('Hindi', 'wp-linkedin'),
				'hu-HU' => __('Hungarian', 'wp-linkedin'),
				'is-IS' => __('Icelandic', 'wp-linkedin'),
				'jv-JV' => __('Javanese', 'wp-linkedin'),
				'kn-IN' => __('Kannada', 'wp-linkedin'),
				'lv-LV' => __('Latvian', 'wp-linkedin'),
				'lt-LT' => __('Lithuanian', 'wp-linkedin'),
				'ml-IN' => __('Malayalam', 'wp-linkedin'),
				'sr-BA' => __('Serbian', 'wp-linkedin'),
				'sk-SK' => __('Slovak', 'wp-linkedin'),
				'tl-PH' => __('Tagalog', 'wp-linkedin'),
				'ta-IN' => __('Tamil', 'wp-linkedin'),
				'te-IN' => __('Telugu', 'wp-linkedin'),
				'th-TH' => __('Thai', 'wp-linkedin'),
				'uk-UA' => __('Ukrainian', 'wp-linkedin'),
				'vi-VN' => __('Vietnamese', 'wp-linkedin'),
				'xx-XX' => __('Other', 'wp-linkedin')
		);
	}
}