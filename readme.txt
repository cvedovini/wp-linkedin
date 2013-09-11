=== WP LinkedIn plugin ===
Author: Claude Vedovini
Contributors: cvedovini
Donate link: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Tags: linkedin,resume,recommendations,profile
Requires at least: 2.7
Tested up to: 3.6
Stable tag: 1.4.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

This plugin provides you with shortcodes to insert your full LinkedIn profile and a rotating scroller of your LinkedIn recommendations in any Wordpress page or post.


== Installation ==

This plugin follows the [standard WordPress installation method](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins):

1. Upload the `wp-linkedin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Generate an access token for the LinkedIn API (those tokens expire after 60 days so you will have to regenrate them from time to time)
1. The `Profile fields` field is the list of fields that will be available to the template for rendering (see below for template customization).

There are two shortcodes available:

* `[li_recommendations width="480" length="200" interval="1000"]` displays a rotating scroller with the recommendations you received
* `[li_profile]` displays your LinkedIn profile. Optional attributes are `fields` and `lang` to overide the general settings. But you can pass any attribute and use it in customized templates.
* `[li_card]` displays a simple LinkedIn card. Optional attributes are `picture_width` and `summary_length`, and `fields` and `lang` to overide the general settings. But you can pass any attribute and use it in customized templates.


To customize the rendering of both shortcodes you must create a `linkedin` folder in your theme and then copy the template file you want to modify.
The default template files, `recommendations.php` and `profile.php`, are located in the plugin's `templates` folder.

See this post for more details on customization: [Showing more of your LinkedIn profile with WP-LinkedIn](http://vedovini.net/2013/06/showing-more-of-your-linkedin-profile-with-wp-linkedin/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin)


== Changelog ==

= version 1.5 =
- Changing the way the LinkedIn API keys and tokens are retreived in order to simplify installation

= version 1.4.3 =
- Updating string translations

= version 1.4.2 =
- Nice looking option page with donate button and Twitter widget

= version 1.4.1 =
- fixing language codes in settings
- simplifying the javascript for the recommendation slider

= version 1.4 =
- Corrected link to post about customization in the readme
- Modified the javascript for the recommendation slider so that it uses `$(document).ready()`
- Added a widget and shortcode displaying a simple LinkedIn card

= version 1.3.8 =
- Corrected a bug that interfers with other plugins using output buffering
- Updated French and Dutch localizations

= version 1.3.7 =
- Added a 'css' option for the widget width to disable setting the width using javascript. This allows to set the width using CSS, which is particularly useful with responsive themes.
- Added a link to the post on vedovini.net about customizing the plugin's output

= version 1.3.6 =
- Fixing how the presence of data is tested and adding error messages when the profile cannot be retrieved

= version 1.3.5 =
- Adding French and Dutch translations (Credit to Jan Spoelstra for the Dutch translations)
- Fixing path issue while laoding the text domain
- Added credit section to the readme file
- Changed name of bundled classes to avoid name colisions

= version 1.3.4 =
- Enable an 'auto' mode for the width of the recommendations in order to accomodate responsive themes. However, it won't work in some occasion where the width of the parent cannot be calculated. To activate it just use 'auto' as the recommendations width.

= version 1.3.3 =
- Updating the css version

= version 1.3.2 =
- Forcing `clear: none` on recommendations blocquote otherwise the scroller might not work
- Adding support for linking to recommender's profiles in the template and adding the fields in the default list of fields. If you want to add that to your output make sure to change the `recommendations-recieved` field to `recommendations-received:(recommendation-text,recommender:(first-name,last-name,public-profile-url))`

= version 1.3.1 =
- Upgrading the widget to use WP_Widget class, enabling several widgets instances
- Modified the script and CSS to be more respectful of theming

= version 1.3 =
- Added the possibility to select the profile language in the settings and the `[li_profile]` shortcode.

= version 1.2.1 =
- Added a test to avoid PHP error when no recommendations

= version 1.2.0 =
- Removed some unecessary code that prevented the fetching of some profile fields
- Moved the inclusion of the default CSS to the template to enable one to remove and totally replace it
- Added the option to provide a `field` attribute to the `[li_profile]` shortcode to override the list from the settings and enable having several different profiles

= version 1.1.0 =
- Adding the `interval` attribute to the shortcode and the widget to control the scroller's speed

= version 1.0.2 =
- Changing version of jQuery Tools to avoid conflicting with WP's jQuery
- Adding a sidebar widget with the recommendation slider

= version 1.0.1 =
- Removing left over HTML comment in recommendations template file

= version 1.0.0 =
- Initial release

== Credits ==

Following is the list of people and projects who helped me with this plugin, many thanks to them :)

- [Jan Spoelstra](http://www.linkedin.com/in/janspoelstra): Contributed the Dutch translations
- [The OAuth project](https://code.google.com/p/oauth/): I bundled a modified version of this library which is distributed under the [Apache  2.0 license](http://www.apache.org/licenses/LICENSE-2.0)
- [The simple-linkedinphp library](https://code.google.com/p/simple-linkedinphp/): I bundled a modified version of this library which is distributed under the [MIT License](http://www.opensource.org/licenses/mit-license.php)

NOTE: Modifications to the bundled libraries have only been done to avoid name colisions.
