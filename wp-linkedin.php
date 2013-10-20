<?php
/*
Plugin Name: WP LinkedIn
Plugin URI: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Description: This plugin enables you to add various part of your LinkedIn profile to your Wordpress blog.
Author: Claude Vedovini
Author URI: http://vedovini.net/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Version: 1.6

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

define('LINKEDIN_FIELDS_BASIC', 'id, first-name, last-name, picture-url, headline, location, industry, public-profile-url');
define('LINKEDIN_FIELDS_RECOMMENDATIONS', 'recommendations-received:(recommendation-text,recommender:(first-name,last-name,public-profile-url))');
define('LINKEDIN_FIELDS_DEFAULT', 'summary, specialties, languages, skills, educations, positions, ' . LINKEDIN_FIELDS_RECOMMENDATIONS);
define('LINKEDIN_FIELDS', get_option('wp-linkedin_fields', LINKEDIN_FIELDS_DEFAULT));
define('LINKEDIN_PROFILELANGUAGE', get_option('wp-linkedin_profilelanguage'));
define('LINKEDIN_SENDMAIL_ON_TOKEN_EXPIRY', get_option('wp-linkedin_sendmail_on_token_expiry', false));
define('LINKEDIN_SSL_VERIFYPEER', get_option('wp-linkedin_ssl_verifypeer', true));
define('LINKEDIN_ADD_CARD_TO_CONTENT', get_option('wp-linkedin_add_card_to_content', false));

include 'class-recommendations-widget.php';
include 'class-card-widget.php';
include 'class-profile-widget.php';


class WPLinkedInPlugin {

	function WPLinkedInPlugin() {
		add_action('init', array(&$this, 'init'));
		add_action('widgets_init', array(&$this, 'widgets_init'));
		add_action('admin_menu', array(&$this, 'admin_init'));
	}

	function init() {
		// Make plugin available for translation
		// Translations can be filed in the /languages/ directory
		load_plugin_textdomain('wp-linkedin', false, dirname(plugin_basename(__FILE__)) . '/languages/' );

		if (!is_admin()) {
			wp_register_script('jquery.tools', 'http://cdn.jquerytools.org/1.2.7/all/jquery.tools.min.js', array('jquery'), '1.2.7', true);
			wp_register_script('jquery-dimension-etc', plugins_url('jquery.dimensions.etc.min.js', __FILE__), array('jquery'), '1.0.0', true);
			wp_register_script('responsive-scrollable', plugins_url('responsive-scrollable.js', __FILE__), array('jquery.tools', 'jquery-dimension-etc'), '1.0.0', true);
			wp_register_style('wp-linkedin', plugins_url('style.css', __FILE__), false, '1.5.2');

			add_shortcode('li_recommendations', 'wp_linkedin_recommendations');
			add_shortcode('li_profile', 'wp_linkedin_profile');
			add_shortcode('li_card', 'wp_linkedin_card');

			if (LINKEDIN_ADD_CARD_TO_CONTENT) {
				add_filter('the_content', array(&$this, 'filter_content'), 1);
			}
		}
	}

	function filter_content($content) {
		if (is_single()) {
			$content .= wp_linkedin_card(array('summary_length' => 2000));
		}

		return $content;
	}

	function widgets_init() {
		register_widget('WP_LinkedIn_Recommendations_Widget');
		register_widget('WP_LinkedIn_Card_Widget');
		register_widget('WP_LinkedIn_Profile_Widget');
	}

	function admin_init() {
		require_once 'class-linkedin-oauth.php';
		require_once 'class-admin.php';
		$this->admin = new WPLinkedInAdmin($this);
	}
}


function wp_linkedin_error($message) {
	if (WP_DEBUG) {
		return "<p>$message</p>";
	} else {
		return "<!-- $message -->";
	}

}

function wp_linkedin_get_profile($options='id', $lang=LINKEDIN_PROFILELANGUAGE) {
	require_once 'class-linkedin-oauth.php';
	$oauth = new WPLinkedInOAuth();
	return $oauth->get_profile($options, $lang);
}


function wp_linkedin_load_template($name, $args) {
	$template = locate_template('linkedin/'. $name . '.php');

	if (!$template) {
		$template = dirname( __FILE__ ) . '/templates/' . $name . '.php';
	}

	$template = apply_filters('linkedin_template', $template);

	extract($args);
	ob_start();
	if (WP_DEBUG) echo '<!-- template path: ' . $template . ' -->';
	require($template);
	return ob_get_clean();
}


function wp_linkedin_profile($atts) {
	extract(shortcode_atts(array(
			'fields' => LINKEDIN_FIELDS,
			'lang' => LINKEDIN_PROFILELANGUAGE
	), $atts, 'li_profile'));

	$fields = preg_replace('/\s+/', '', LINKEDIN_FIELDS_BASIC . ',' . $fields);

	$profile = wp_linkedin_get_profile($fields, $lang);
	if (isset($profile) && is_object($profile)) {
		return wp_linkedin_load_template('profile', array('profile' => $profile));
	} else {
		return wp_linkedin_error(__('There\'s something wrong and the profile could not be retreived, please check the list of profile fields to be fetched. If everything seems good try regenerating the access token.', 'wp-linkedin'));
	}
}

function wp_linkedin_card($atts) {
	extract(shortcode_atts(array(
			'picture_width' => '80',
			'summary_length' => '200',
			'fields' => 'summary',
			'lang' => LINKEDIN_PROFILELANGUAGE
	), $atts, 'li_card'));

	$fields = preg_replace('/\s+/', '', LINKEDIN_FIELDS_BASIC . ',' . $fields);

	$profile = wp_linkedin_get_profile($fields, $lang);
	if (isset($profile) && is_object($profile)) {
		return wp_linkedin_load_template('card', array('profile' => $profile,
				'picture_width' => $picture_width, 'summary_length' => $summary_length));
	} else {
		return wp_linkedin_error(__('There\'s something wrong and the profile could not be retreived, please check the list of profile fields to be fetched. If everything seems good try regenerating the access token.', 'wp-linkedin'));
	}
}


function wp_linkedin_recommendations($atts) {
	extract(shortcode_atts(array(
			'width' => 'auto',
			'length' => '200',
			'interval' => '4000'
	), $atts, 'li_recommendations'));

	$profile = wp_linkedin_get_profile(LINKEDIN_FIELDS_RECOMMENDATIONS);

	if (isset($profile) && is_object($profile)) {
		if (isset($profile->recommendationsReceived->values) && is_array($profile->recommendationsReceived->values)) {
			return wp_linkedin_load_template('recommendations', array('recommendations' => $profile->recommendationsReceived->values,
					'width' => $width, 'length' => $length, 'interval' => $interval));
		} else {
			return wp_linkedin_error(__('You don\'t have any recommendation to show.', 'wp-linkedin'));
		}
	} else {
		return wp_linkedin_error(__('There\'s something wrong and the profile could not be retreived, please check the list of profile fields to be fetched. If everything seems good try regenerating the access token.', 'wp-linkedin'));
	}
}


function wp_linkedin_excerpt($str, $length, $postfix='[...]') {
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
		echo $postfix;
	} else {
		echo $str;
	}
}

function wp_linkedin_cause($cause_name) {
	static $causes;
	if (!isset($causes)) {
		$causes = array(
			'animalRights' => __('Animal Welfare', 'wp-linkedin'),
			'artsAndCulture' => __('Arts and Culture', 'wp-linkedin'),
			'children' => __('Children', 'wp-linkedin'),
			'civilRights' => __('Civil Rights and Social Action', 'wp-linkedin'),
			'humanitarianRelief' => __('Disaster and Humanitarian Relief', 'wp-linkedin'),
			'economicEmpowerment' => __('Economic Empowerment', 'wp-linkedin'),
			'education' => __('Education', 'wp-linkedin'),
			'environment' => __('Environment', 'wp-linkedin'),
			'health' => __('Health', 'wp-linkedin'),
			'humanRights' => __('Human Rights', 'wp-linkedin'),
			'politics' => __('Politics', 'wp-linkedin'),
			'povertyAlleviation' => __('Poverty Alleviation', 'wp-linkedin'),
			'scienceAndTechnology' => __('Science and Technology', 'wp-linkedin'),
			'socialServices' => __('Social Services', 'wp-linkedin'));
	}

	return $causes[$cause_name];
}

global $the_wp_linked_plugin;
$the_wp_linked_plugin = new WPLinkedInPlugin();
