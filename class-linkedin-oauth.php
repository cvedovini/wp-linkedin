<?php

define('WP_LINKEDIN_CACHETIMEOUT', 43200); // 12 hours

// Let people define their own APPKEY if needed
if (!defined('WP_LINKEDIN_APPKEY')) {
	if (WP_DEBUG) {
		define('WP_LINKEDIN_APPKEY', 'h4p3wuu2ibxy');
		define('WP_LINKEDIN_APPSECRET', 'scSZiR3tUB3gcCJ9');
	} else {
		define('WP_LINKEDIN_APPKEY', '57zh7f1nvty5');
		define('WP_LINKEDIN_APPSECRET', 'FL0gcEC2b0G18KPa');
	}
}

class WPLinkedInOAuth {

	function get_access_token() {
		return get_transient('wp-linkedin_oauthtoken');
	}

	function set_last_error($error=false) {
		if ($error) {
			update_option('wp-linkedin_last_error', $error);
		} else {
			delete_option('wp-linkedin_last_error');
		}
	}

	function get_last_error() {
		return get_option('wp-linkedin_last_error', false);
	}

	function invalidate_access_token() {
		delete_transient('wp-linkedin_oauthtoken');
	}

	function set_access_token($code) {
		$this->set_last_error();
		$url = 'https://www.linkedin.com/uas/oauth2/accessToken?grant_type=authorization_code&' . $this->urlencode(array(
			'code' => $code,
			'redirect_uri' => site_url('/wp-admin/options-general.php?page=wp-linkedin'),
			'client_id' => WP_LINKEDIN_APPKEY,
			'client_secret' => WP_LINKEDIN_APPSECRET));

		$response = wp_remote_get($url, array('sslverify' => LINKEDIN_SSL_VERIFYPEER));
		if (!is_wp_error($response)) {
			$body = json_decode($response['body']);

			if (isset($body->access_token)) {
				update_option('wp-linkedin_invalid_token_mail_sent', false);
				return set_transient('wp-linkedin_oauthtoken', $body->access_token, $body->expires_in);
			} elseif (isset($body->error)) {
				return new WP_Error($body->error, $body->error_description);
			} else {
				return new WP_Error('unknown', __('An unknown error has occured and no token was retrieved.'));
			}
		} else {
			return $response;
		}
	}

	function is_access_token_valid() {
		return $this->get_access_token() !== false;
	}

	function get_authorization_url($state) {
		return 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code&' . $this->urlencode(array(
				'client_id' => WP_LINKEDIN_APPKEY,
				'scope' => 'r_fullprofile r_network',
				'state' => $state,
				'redirect_uri' => site_url('/wp-admin/options-general.php?page=wp-linkedin')));
	}

	function clear_cache() {
		delete_option('wp-linkedin_cache');
	}

	function get_profile($options='id', $lang='') {
		$profile = false;
		$cache = get_option('wp-linkedin_cache');
		if (!is_array($cache)) $cache = array();

		// Do we have an up-to-date profile?
		if (isset($cache[$options.$lang])) {
			$expires = $cache[$options.$lang]['expires'];
			$profile = $cache[$options.$lang]['profile'];
			// If yes let's return it.
			if (time() < $expires) return $profile;
		}

		// Else, let's try to fetch one.
		$fetched = $this->fetch_profile($options, $lang);
		if ($fetched) {
			$profile = $fetched;
			$cache[$options.$lang] = array(
					'expires' => time() + WP_LINKEDIN_CACHETIMEOUT,
					'profile' => $profile);
			update_option('wp-linkedin_cache', $cache);
		}

		// But if we cannot fetch one, let's return the outdated one if any.
		return $profile;
	}

	function fetch_profile($options='id', $lang='') {
		$access_token = $this->get_access_token();

		if ($access_token) {
			$url = "https://api.linkedin.com/v1/people/~:($options)?oauth2_access_token=$access_token";
			$headers = array(
					'Content-Type' => 'text/plain; charset=UTF-8',
					'x-li-format' => 'json');

			if (!empty($lang)) {
				$headers['Accept-Language'] = str_replace('_', '-', $lang);
			}

			$response = wp_remote_get($url, array('headers' => $headers));
			if (!is_wp_error($response)) {
				$return_code = $response['response']['code'];
				$body = json_decode($response['body']);

				if ($return_code == 200) {
					$this->set_last_error();
					return $body;
				} else{
					if ($return_code == 401) {
						// Invalidate token
						$this->invalidate_access_token();
					}

					if (isset($body->message)) {
						$error = $body->message;
					} else {
						$error = sprintf(__('HTTP request returned error code %d.'), $return_code);
					}
				}
			} else {
				$error = $response->get_error_code() . ': ' . $response->get_error_message();
			}
		}

		if (isset($error)) {
			$this->set_last_error($error);
			error_log('[WP LinkedIn] ' . $error);
		}

		if (LINKEDIN_SENDMAIL_ON_TOKEN_EXPIRY && !get_option('wp-linkedin_invalid_token_mail_sent', false)) {
			$blog_name = get_option('blogname');
			$admin_email = get_option('admin_email');
			$header = array("From: $blog_name <$admin_email>");
			$subject = '[WP LinkedIn] ' . __('Invalid or expired access token', 'wp-linkedin');

			$message = __("The access token for the WP LinkedIn plugin is either invalid or has expired, please click on the following link to renew it.\n\n%s\n\nThis link will only be valid for a limited period of time.\n-Thank you.", 'wp-linkedin');
			$message = sprintf($message, $this->get_authorization_url(wp_create_nonce('linkedin-oauth')));

			$sent = wp_mail($admin_email, $subject, $message, $header);
			update_option('wp-linkedin_invalid_token_mail_sent', $sent);
		}

		return false;
	}

	function urlencode($params) {
		if (is_array($params)) {
			$p = array();
			foreach($params as $k => $v) {
				$p[] = $k . '=' . urlencode($v);
			}
			return implode('&', $p);
		} else {
			return urlencode($params);
		}
	}
}