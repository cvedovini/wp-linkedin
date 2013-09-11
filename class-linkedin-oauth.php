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
			'client_id' => $this->LINKEDIN_APPKEY,
			'client_secret' => $this->LINKEDIN_APPSECRET));

		$response = wp_remote_get($url);
		if (!is_wp_error($response)) {
			$response = json_decode($response['body']);
			return set_transient('wp-linkedin_oauthtoken', $response['access_token'], $response['expires_in']);
		}

		return false;
	}

	function is_access_token_valid() {
		return $this->get_access_token() !== false;
	}

	function get_authorization_url($state) {
		return 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code&' . $this->urlencode(array(
				'client_id' => $this->LINKEDIN_APPKEY,
				'scope' => 'r_fullprofile',
				'state' => $state,
				'redirect_uri' => site_url('/wp-admin/options-general.php?page=wp-linkedin')));
	}

	function get_profile($options='id', $lang=false) {
		$access_token = get_access_token();

		if ($access_token) {
			$url = "https://api.linkedin.com/v1/people/~.($options)?oauth2_access_token=$access_token";
			$args = array();

			if (!empty($lang)) {
				$lang = str_replace('_', '-', $lang);
				$args['headers'] = array('Accept-Language' => $lang);
			}

			$response = wp_remote_get($url, $args);
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