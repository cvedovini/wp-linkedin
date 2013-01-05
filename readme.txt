=== WP LinkedIn plugin ===
Author: Claude Vedovini
Contributors: cvedovini
Donate link: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Tags: linkedin,resume,recommendations,profile
Requires at least: 2.7
Tested up to: 3.4.2
Stable tag: 1.1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

This plugin provides you with shortcodes to insert your full LinkedIn profile and a rotating scroller of your LinkedIn recommendations in any Wordpress page or post.


== Installation ==

This plugin follows the [standard WordPress installation method](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins):

1. Upload the `wp-linkedin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the LinkedIn Developer Network and sign-up for a LinkedIn Application Key <https://www.linkedin.com/secure/developer>
1. Go to the settings page for the plugin and fill in the key, secrets and token fields.
1. The `Profile fields` field is the list of fields that will be available to the template for rendering (see below for template customization).

There are two shortcodes available:

* [li_profile] displays your LinkedIn profile
* [li_recommendations width="480" length="200" interval="1000"] displays a rotating scroller with the recommendations you received

To customize the rendering of both shortcodes you must create a `linkedin` folder in your theme and then copy the template file you want to modify.
The default template files, `recommendations.php` and `profile.php`, are located in the plugin's `template` folder.

== Changelog ==

= version 1.1.0 =
- Adding the `interval` attribute to the shortcode and the widget to control the scroller's speed

= version 1.0.2 =
- Changing version of jQuery Tools to avoid conflicting with WP's jQuery
- Adding a sidebar widget with the recommendation slider

= version 1.0.1 =
- Removing left over HTML comment in recommendations template file

= version 1.0.0 =
- Initial release
