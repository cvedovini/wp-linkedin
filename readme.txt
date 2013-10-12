=== WP LinkedIn ===
Author: Claude Vedovini
Contributors: cvedovini
Donate link: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Tags: linkedin,resume,recommendations,profile
Requires at least: 2.7
Tested up to: 3.6.1
Stable tag: 1.5.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html


== Description ==

This plugin provides you with shortcodes to insert your full LinkedIn profile and a rotating scroller of your LinkedIn recommendations in any Wordpress page or post.

There are 3 shortcodes available:

* `[li_recommendations width="480" length="200" interval="1000"]` displays a rotating scroller with the recommendations you received
* `[li_profile]` displays your LinkedIn profile. Optional attributes are `fields` and `lang` to overide the general settings. But you can pass any attribute and use it in customized templates.
* `[li_card]` displays a simple LinkedIn card. Optional attributes are `picture_width` and `summary_length`, and `fields` and `lang` to overide the general settings. But you can pass any attribute and use it in customized templates.

To customize the rendering of both shortcodes you must create a `linkedin` folder in your theme and then copy the template file you want to modify.
The default template files, `recommendations.php` and `profile.php`, are located in the plugin's `templates` folder.

See this post for more details on customization: [Showing more of your LinkedIn profile with WP-LinkedIn](http://vedovini.net/2013/06/showing-more-of-your-linkedin-profile-with-wp-linkedin/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin)

There are also 3 widgets. One widget displaying the recommendations scroller and two widgets to show a "profile card". One of which is the standard LinkedIn JavaScript profile widget, the other is using a customizable template.


== Installation ==

This plugin follows the [standard WordPress installation method](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins):

1. Upload the `wp-linkedin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Generate an access token for the LinkedIn API (those tokens expire after 60 days so you will have to regenerate them from time to time)
1. The `Profile fields` field is the list of fields that will be available to the profile template for rendering - see this post for more details on customization: [Showing more of your LinkedIn profile with WP-LinkedIn](http://vedovini.net/2013/06/showing-more-of-your-linkedin-profile-with-wp-linkedin/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin)


== Changelog ==

= Version 1.6 =
- Updating Dutch translations.
- Using `wpautop`instead of `nl2br` in templates.
- Adding template loading function with debug output of the template path. You must have WP_DEBUG set to true to see what template file is used. The file path will be printed inside an HTML comment just before the template output.
- Ability to add your LinkedIn card after each of your posts.
- Adding hook to enable extensions to override the LinkedIn API oauth token.

= Version 1.5.5 =
- Bug fix: Invalid url to refresh token in token expiry alert email.

= Version 1.5.4 =
- Better error reporting and specifying ssl_verify parameter when fetching profile too.

= Version 1.5.3 =
- Fixing wrong name of parameter for wp_remote_get when exchanging code for token m(

= Version 1.5.2 =
- Tweaking templates and CSS
- Option to disable SSL verification (on some servers the proper ssl certificates are not installed thus preventing SSL verification).
- Option to have the plugin send an email when the access token becomes invalid or expires.

= Version 1.5.1 =
- Improved error handling when updating oauth token.
- Using another set of APP key/secret when `WP_DEBUG` is turned on (allows for having a dev environment without the access token being invalidated each time you switch).
- Allowing to override the APP key and secret by defining `WP_LINKEDIN_APPKEY` and `WP_LINKEDIN_APPSECRET` in `wp-config.php`.
- Added a profile widget using the LinkedIn JavaScript API.
- Changed the `readme.txt` file to move some details from the "Installation" page to the "Description" page.
- Changed from using `pre-wrap` in the stylesheet to using `nl2br` in templates in order to better preserve the text formatting.

= Version 1.5 =
- Changing the way the LinkedIn API keys and token are managed in order to simplify installation.
- Added a profile cache to improve performances and limit API calls.

= Version 1.4.3 =
- Updating string translations.

= Version 1.4.2 =
- Nice looking option page with donate button and Twitter widget.

= Version 1.4.1 =
- Fixing language codes in settings.
- Simplifying the javascript for the recommendation slider.

= Version 1.4 =
- Corrected link to post about customization in the readme.
- Modified the javascript for the recommendation slider so that it uses `$(document).ready()`.
- Added a widget and shortcode displaying a simple LinkedIn card.

= Version 1.3.8 =
- Corrected a bug that interfers with other plugins using output buffering.
- Updated French and Dutch localizations.

= Version 1.3.7 =
- Added a 'css' option for the widget width to disable setting the width using javascript. This allows to set the width using CSS, which is particularly useful with responsive themes.
- Added a link to the post on vedovini.net about customizing the plugin's output.

= Version 1.3.6 =
- Fixing how the presence of data is tested and adding error messages when the profile cannot be retrieved.

= Version 1.3.5 =
- Adding French and Dutch translations (Credit to Jan Spoelstra for the Dutch translations).
- Fixing path issue while laoding the text domain.
- Added credit section to the readme file.
- Changed name of bundled classes to avoid name colisions.

= Version 1.3.4 =
- Enable an 'auto' mode for the width of the recommendations in order to accomodate responsive themes. However, it won't work in some occasion where the width of the parent cannot be calculated. To activate it just use 'auto' as the recommendations width.

= Version 1.3.3 =
- Updating the css version.

= Version 1.3.2 =
- Forcing `clear: none` on recommendations blocquote otherwise the scroller might not work.
- Adding support for linking to recommender's profiles in the template and adding the fields in the default list of fields. If you want to add that to your output make sure to change the `recommendations-recieved` field to `recommendations-received:(recommendation-text,recommender:(first-name,last-name,public-profile-url))`.

= Version 1.3.1 =
- Upgrading the widget to use WP_Widget class, enabling several widgets instances.
- Modified the script and CSS to be more respectful of theming.

= Version 1.3 =
- Added the possibility to select the profile language in the settings and the `[li_profile]` shortcode.

= Version 1.2.1 =
- Added a test to avoid PHP error when no recommendations.

= Version 1.2.0 =
- Removed some unecessary code that prevented the fetching of some profile fields.
- Moved the inclusion of the default CSS to the template to enable one to remove and totally replace it.
- Added the option to provide a `field` attribute to the `[li_profile]` shortcode to override the list from the settings and enable having several different profiles.

= Version 1.1.0 =
- Adding the `interval` attribute to the shortcode and the widget to control the scroller's speed.

= Version 1.0.2 =
- Changing version of jQuery Tools to avoid conflicting with WP's jQuery.
- Adding a sidebar widget with the recommendation slider.

= Version 1.0.1 =
- Removing left over HTML comment in recommendations template file.

= Version 1.0.0 =
- Initial release.


== Credits ==

Following is the list of people and projects who helped me with this plugin, many thanks to them :)

- [Jan Spoelstra](http://www.linkedin.com/in/janspoelstra): Contributed the Dutch translations
