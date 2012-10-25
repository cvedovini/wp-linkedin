<?php
/*
 Plugin Name: LinkedIn for Wordpress
Plugin URI: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Description: This plugin enables you to add various part of your LinkedIn profile to your Wordpress blog.
Author: Claude Vedovini
Author URI: http://vedovini.net/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Version: 1.0.0

# The code in this plugin is free software; you can redistribute the code aspects of
# the plugin and/or modify the code under the terms of the GNU Lesser General
# Public License as published by the Free Software Foundation; either
# version 3 of the License, or (at your option) any later version.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#
# See the GNU lesser General Public License for more details.
*/


define('LINKEDIN_APPKEY', get_option('wp-linkedin_appkey'));
define('LINKEDIN_APPSECRET', get_option('wp-linkedin_appsecret'));
define('LINKEDIN_USERTOKEN', get_option('wp-linkedin_usertoken'));
define('LINKEDIN_USERSECRET', get_option('wp-linkedin_usersecret'));

define('LINKEDIN_FIELDS_BASIC', 'id, first-name, last-name, picture-url, headline, location, industry, public-profile-url');
define('LINKEDIN_FIELDS_DEFAULT', 'summary, specialties, languages, skills, educations, positions, recommendations-received');
define('LINKEDIN_FIELDS', LINKEDIN_FIELDS_BASIC . ', ' . get_option('wp-linkedin_fields', LINKEDIN_FIELDS_DEFAULT));


class WPLinkedInPlugin {

	function WPLinkedInPlugin() {
		add_action('init', array(&$this, 'init'));
	}

	function init() {
		// Make plugin available for translation
		// Translations can be filed in the /languages/ directory
		load_plugin_textdomain('wp-linkedin', false, dirname(plugin_basename(__FILE__)) . '/languages' );

		if (is_admin()) {
			add_action('admin_menu', array(&$this, 'admin_menu'));
		} else {
			wp_register_style('wp-linkedin', plugins_url('wp-linkedin/style.css'), false, '1.0.0');
			wp_register_script('jquery.tools', 'http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js', false, '1.2.7');
			add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
			add_shortcode('li_recommendations', array(& $this, 'recommendations_sc'));
			add_shortcode('li_profile', array(& $this, 'profile_sc'));
		}
	}

	function admin_menu() {
		require_once 'class-admin.php';
		$this->admin = new WPLinkedInAdmin($this);
	}

	function enqueue_scripts() {
		wp_enqueue_style('wp-linkedin');
		wp_enqueue_script('jquery.tools');
	}

	function profile_sc($atts) {
		// In case they want to pass customized attribute to their custom template
		extract(shortcode_atts(array(), $atts));

		$fields = preg_replace('/\s+/', '', LINKEDIN_FIELDS);
		$fields = preg_split('/[,;:]/', $fields);
		$fields = implode(',', $fields);

		$profile = $this->get_profile($fields);
		$template = locate_template('linkedin/profile.php');

		ob_flush();
		ob_start();
		if (!empty($template)) {
			require $template;
		} else {
			require 'templates/profile.php';
		}
		$results = ob_get_contents();
		ob_end_clean();
		return $results;
	}

	function recommendations_sc($atts) {
		extract(shortcode_atts(array(
				'width' => '480',
				'length' => '200'
		), $atts));

		$profile = $this->get_profile('recommendations-received');
		$recommendations = $profile->recommendationsReceived->values;

		$template = locate_template('linkedin/recommendations.php');

		ob_flush();
		ob_start();
		if (!empty($template)) {
			require $template;
		} else {
			require 'templates/recommendations.php';
		}
		$results = ob_get_contents();
		ob_end_clean();
		return $results;
	}

	function get_profile($options='id') {
		require_once 'linkedin_3.2.0.class.php';

		$API_CONFIG = array(
				'appKey'       => LINKEDIN_APPKEY,
				'appSecret'    => LINKEDIN_APPSECRET,
				'callbackUrl'  => NULL
		);

		$linkedin = new LinkedIn($API_CONFIG);
        $linkedin->setTokenAccess(array('oauth_token' => LINKEDIN_USERTOKEN, 'oauth_token_secret' => LINKEDIN_USERSECRET));
        $linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);

		$response = $linkedin->profile("~:($options)");

        if($response['success'] === TRUE) {
        	return json_decode($response['linkedin']);
        } else {
        	echo '<!-- '.json_encode($response).' -->';
        	return false;
        }
	}

}

function wp_linkedin_excerpt($str, $length) {
	$length++;

	if (mb_strlen($str) > $length) {
		$subex = mb_substr($str, 0, $length - 5);
		$exwords = explode(' ', $subex);
		$excut = -mb_strlen($exwords[count($exwords) - 1]);
		if ($excut < 0) {
			echo mb_substr($subex, 0, $excut);
		} else {
			echo $subex;
		}
		echo '[...]';
	} else {
		echo $str;
	}
}

global $the_wp_linked_plugin;
$the_wp_linked_plugin = new WPLinkedInPlugin();
