<?php

class WPLinkedInOAuth {
	const LINKEDIN_APPKEY = '57zh7f1nvty5';
	const LINKEDIN_APPSECRET = 'FL0gcEC2b0G18KPa';

	function get_access_token() {
		return get_transient('wp-linkedin_oauthtoken');
	}

	function set_access_token($code) {
		$url = 'https://www.linkedin.com/uas/oauth2/accessToken?grant_type=authorization_code&' . $this->urlencode(array(
			'code' => $code,
			'redirect_uri' => site_url('/wp-admin/options-general.php?page=wp-linkedin'),
			'client_id' => WPLinkedInOAuth::LINKEDIN_APPKEY,
			'client_secret' => WPLinkedInOAuth::LINKEDIN_APPSECRET));

		$response = wp_remote_get($url);
		if (!is_wp_error($response)) {
			$response = json_decode($response['body']);
			return set_transient('wp-linkedin_oauthtoken', $response->access_token, $response->expires_in);
		}

		return false;
	}

	function is_access_token_valid() {
		return $this->get_access_token() !== false;
	}

	function get_authorization_url($state) {
		return 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code&' . $this->urlencode(array(
				'client_id' => WPLinkedInOAuth::LINKEDIN_APPKEY,
				'scope' => 'r_fullprofile',
				'state' => $state,
				'redirect_uri' => site_url('/wp-admin/options-general.php?page=wp-linkedin')));
	}

	function clear_cache() {
		delete_option('wp-linkedin-cache');
	}

	function get_profile($options='id', $lang='') {
		$profile = false;
		$cache = get_option('wp-linkedin-cache');
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
			$cache[$options.$lang] = array('expires' => time() + 3600, 'profile' => $profile);
			update_option('wp-linkedin-cache', $cache);
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
				return json_decode($response['body']);
			}
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