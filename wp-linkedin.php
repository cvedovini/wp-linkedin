<?php
/*
Plugin Name: LinkedIn for Wordpress
Plugin URI: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Description: This plugin enables you to add various part of your LinkedIn profile to your Wordpress blog.
Author: Claude Vedovini
Author URI: http://vedovini.net/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Version: 1.5

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

include 'class-recommendations-widget.php';
include 'class-card-widget.php';


class WPLinkedInPlugin {

	function WPLinkedInPlugin() {
		add_action('init', array(&$this, 'init'));
		add_action('widgets_init', array(&$this, 'widgets_init'));
	}

	function init() {
		// Make plugin available for translation
		// Translations can be filed in the /languages/ directory
		load_plugin_textdomain('wp-linkedin', false, dirname(plugin_basename(__FILE__)) . '/languages/' );

		if (is_admin()) {
			add_action('admin_menu', array(&$this, 'admin_menu'));
		} else {
			wp_register_script('jquery.tools', 'http://cdn.jquerytools.org/1.2.7/all/jquery.tools.min.js', array('jquery'), '1.2.7');
			wp_register_script('jquery-dimension-etc', plugins_url('wp-linkedin/jquery.dimensions.etc.min.js'), array('jquery'), '1.0.0');
			wp_register_style('wp-linkedin', plugins_url('wp-linkedin/style.css'), false, '1.0.2');
			add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
			add_shortcode('li_recommendations', 'wp_linkedin_recommendations');
			add_shortcode('li_profile', 'wp_linkedin_profile');
			add_shortcode('li_card', 'wp_linkedin_card');
		}
	}

	function widgets_init() {
		register_widget('WP_LinkedIn_Recommendations_Widget');
		register_widget('WP_LinkedIn_Card_Widget');
	}

	function admin_menu() {
		require_once 'class-linkedin-oauth.php';
		require_once 'class-admin.php';
		$this->admin = new WPLinkedInAdmin($this);
	}

	function enqueue_scripts() {
		wp_enqueue_script('jquery.tools');
		wp_enqueue_script('jquery-dimension-etc');
		wp_enqueue_style('wp-linkedin', plugins_url('wp-linkedin/style.css'), false, '1.0.0');
	}
}


function wp_linked_get_profile($options='id', $lang=LINKEDIN_PROFILELANGUAGE) {
	require_once 'class-linkedin-oauth.php';
	$oauth = new WPLinkedInOAuth();
	return $oauth->get_profile($options, $lang);
}


function wp_linkedin_profile($atts) {
	extract(shortcode_atts(array(
			'fields' => LINKEDIN_FIELDS,
			'lang' => LINKEDIN_PROFILELANGUAGE
	), $atts));

	$fields = preg_replace('/\s+/', '', LINKEDIN_FIELDS_BASIC . ',' . $fields);

	$profile = wp_linked_get_profile($fields, $lang);
	if (isset($profile) && is_object($profile)) {
		$template = locate_template('linkedin/profile.php');

		ob_start();
		if (!empty($template)) {
			require $template;
		} else {
			require 'templates/profile.php';
		}
		return ob_get_clean();
	} else {
		return '<p>' . __('There\'s something wrong and the profile could not be retreived, please check your API keys and the list of profile fields to be fetched. If everything seems good try regenerating the keys.', 'wp-linkedin') . '</p>';
	}
}


function wp_linkedin_card($atts) {
	extract(shortcode_atts(array(
			'picture_width' => '80',
			'summary_length' => '200',
			'fields' => 'summary',
			'lang' => LINKEDIN_PROFILELANGUAGE
	), $atts));

	$fields = preg_replace('/\s+/', '', LINKEDIN_FIELDS_BASIC . ',' . $fields);

	$profile = wp_linked_get_profile($fields, $lang);
	if (isset($profile) && is_object($profile)) {
		$template = locate_template('linkedin/card.php');

		ob_start();
		if (!empty($template)) {
			require $template;
		} else {
			require 'templates/card.php';
		}
		return ob_get_clean();
	} else {
		return '<p>' . __('There\'s something wrong and the profile could not be retreived, please check the list of profile fields to be fetched. If everything seems good try regenerating the access token.', 'wp-linkedin') . '</p>';
	}
}


function wp_linkedin_recommendations($atts) {
	extract(shortcode_atts(array(
			'width' => 'auto',
			'length' => '200',
			'interval' => '4000'
	), $atts));

	$profile = wp_linked_get_profile(LINKEDIN_FIELDS_RECOMMENDATIONS);

	if (isset($profile) && is_object($profile)) {
		if (isset($profile->recommendationsReceived->values) && is_array($profile->recommendationsReceived->values)) {
			$recommendations = $profile->recommendationsReceived->values;
			$template = locate_template('linkedin/recommendations.php');

			ob_start();
			if (!empty($template)) {
				require $template;
			} else {
				require 'templates/recommendations.php';
			}
			return ob_get_clean();
		} else {
			return '<p>' . __('You don\'t have any recommendation to show.', 'wp-linkedin') . '</p>';
		}
	} else {
		return '<p>' . __('There\'s something wrong and the profile could not be retreived, please check the list of profile fields to be fetched. If everything seems good try regenerating the access token.', 'wp-linkedin') . '</p>';
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


global $the_wp_linked_plugin;
$the_wp_linked_plugin = new WPLinkedInPlugin();
