=== Statistics Widgets ===

Contributors: gVectors Team
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=U22KJYA4VVBME
Tags: posts statistics, authors statistics, statistics, stats, visits
Requires at least: 4.1.0
Tested up to: 4.9
Stable tag: 2.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Statistics Widgets is an easy solution to display authors' activity and popular posts with statistic information in sidebar widgets. 

== Description ==

Statistics Widgets is an easy solution to display authors' activity and popular posts statistic information in sidebar widgets. This plugin comes with many smart widgets, which show adaptive statistic information depended on current page.

= Widgets - Author & Popular Post Statistics =

<blockquote>

* My statistics,<br>
* My most viewed posts,<br>
* My most commented posts,<br>
* Post author statistics,<br>
* Post author most viewed posts,<br>
* Post author most commented posts,<br><br>

</blockquote>


= Widgets - Popular Users =

<blockquote>

* Popular users (by posts count),<br>
* Popular users (by posts views count),<br>
* Popular users (by posts comments count),<br><br>

</blockquote>


= Widgets - Popular Posts =

<blockquote>

* Most viewed posts,<br>
* Most commented posts,<br><br>

</blockquote>

= Features =

* Quick translation for all front-end phrases
* Page views statistic under post content
* Dashboard: General Settings
* Dashboard: Widget Settings
* Dashboard: Widget Styles Settings
* Dashboard: Reset Statistics

* | [Pro](http://gvectors.com/product/author-and-post-statistic-widgets-pro/) | Dashboard: Own Published Posts Graphical Statistic
* | [Pro](http://gvectors.com/product/author-and-post-statistic-widgets-pro/) | Dashboard: Own Posts' Views Graphical Statistic
* | [Pro](http://gvectors.com/product/author-and-post-statistic-widgets-pro/) | Dashboard: Own Posts Popularity Graphical Statistic
* | [Pro](http://gvectors.com/product/author-and-post-statistic-widgets-pro/) | Dashboard: Own Posts' Readers Graphical Statistic by Countries
* | [Pro](http://gvectors.com/product/author-and-post-statistic-widgets-pro/) | Dashboard: If you're the admin, there is also a statistic for all authors activity and posts popularity


== Installation ==

1. Activate plugin.
2. Go to Dashboard -> Appearance -> Widgets to add/remove APSW Widgets
3. Go to Dashboard -> Statistics Widgets to manage widget settings.

== Screenshots ==

1.  Author and Post Statistic Widget | Screenshot #1
2.  Popular Posts Widget | Screenshot #2
3.  Popular Users Widget | Screenshot #3
4.  Dashboard > Widgets | Screenshot #5
5.  APSW Settings | Screenshot #6
6.  Page views statistic under post content | Screenshot #4

== Frequently Asked Questions ==

Now you can set custom time interval for stat widgets. Put according code in current active theme's functions.php file.

`
//Example - Author and Post Widget (set for last 45 days)
add_filter('apsw_author_and_post_last', 'custom_author_and_post_last');
function custom_author_and_post_last($last) {
    $last = 45;
    return $last;
}
`

`
//Example - Popular Posts Widget (set for last 20 days)
add_filter('apsw_popular_posts_last', 'custom_popular_posts_last');
function custom_popular_posts_last($last) {
    $last = 20;
    return $last;
}
`

`
//Example - Popular Users Widget (set for last 730 days)
add_filter('apsw_popular_users_last', 'custom_popular_users_last');
function custom_popular_users_last($last) {
    $last = 730;
    return $last;
}
`


Support Forum: <http://gvectors.com/forum/author-post-statistic-widgets/>

== Changelog ==

= 2.1.2 =

* Fixed Bug : Database error on adding statistics
* Changes : Small CSS issues

= 2.1.1 =

* Added : WP Multisite support

= 2.1.0 =

Fixed Bug: Conflict with bbpres if guests allowed to post topics/replies
Added Filter: Dropdown filter in dashboard posts list page by posts views and comments count
Added Filter: Dropdown filter in dashboard users list page by users' posts' count, views count, comments count
Added Wordpress Filters: For more information see plugin settings -> functions and shortcodes tab

= 2.0.4 =

* Added: New time interval for stat widgets (last 180 days)
* Added: New filter hook to set custom time interval. More info in FAQ.

= 2.0.3 =

* Fixed Bug: Widgets "custom before/after HTML" saving problem.

= 2.0.2 =

* Added: Option to exclude posts by IDs (author and post stat widget)
* Added: Option to exclude posts by IDs (popular posts widget)
* Added: Option to exclude users by IDs (popular users widget)
* Added: Quick translation options for all front-end phrases
* Fixed Bug: global post issue in widget function

= 2.0.1 =

* Fixied Bug: Fatal error on updating

= 2.0.0 =
* Added : Compatible with WordPress 4.5
* Added : Tabbed Widgets (can be used as separate too)
	1. Author & Popular Post Stat - | Author Stat | Author Post Stat|
	2. Popular Users - | by Posts Count | by Posts Views | by Comments Count |
	3. Popular Posts - | Most Viewed Posts | Most commented posts |

* Added : option to set post author OR current user stat for A&P Stat Widget
* Added : option to show/hide post thumbnails
* Added : option to manage post thumbnail size
* Added : option to show/hide avatars
* Added : option to manage avatar size
* Added : option to set limit for:
	1. popular posts
	2. popular users
	3. author popular posts
									
* Added : option to set post title max-length
* Added : option to set default thumbnail for posts without image
* Added : option to show logged in user statistic in Author & Post Statistic widget by default 
* Added : POT file - ready for translations
* Added : clears post statistic for deleted posts
* Optimized : less number of SQL queries
* Optimized : decreased the number and size of js/css files, 
* Fixed bug : option saving issues
* Fixed bug : Posts view count under post content for custom post types
* Fixed bug : Date interval problem when interval set "yesterday"
* Changed : Deprecated function get_currentuserinfo() to wp_get_current_user()

= 1.5.3 =
* Fixed bug : Posts' view counter invalid argument issue

= 1.5.2 =
* Fixed Bug : Daily view counter for post types

= 1.5.1 =
* Added : Wordpress 4.3 Compability

= 1.5.0 =
* Fixed Bug : Incorrect daily views count

= 1.4.9 =
* Fixed Bug : Compatibility with WordPress 4.2 version

= 1.4.8 =
* Fixed Bug : Invalid argument issue in "Author & Post" Widget

= 1.4.7 =
* Added: Template Tags to locate widget information in template files
`
function apsw_pp_static_date_widget($from, $to)
function apsw_au_static_date_widget($from, $to)
function apsw_pu_widget()
function apsw_pp_dynamic_date_widget($last = -1)
function apsw_pa_dynamic_date_widget($last = -1)
`
* Added: Option to hide/show custom html fields on widget settings area
* Fixed Bug: Issues with unicode characters

= 1.4.6 =
* Fixed Bug: Ultimate Member Integration problem on WEB Servers w/o DomObject support

= 1.4.5 =
* Changed: jQuery UI widget tabs to better and modern tab layout 
* Changed: jQuery UI admin section tabs to better and modern tab layout 
* Added: Integration with User Profile Plugins
	- BuddyPress, Ultimate Member, Users Ultra, UserPro

= 1.4.4 =
* Fixed Bug: Invalid argument issue for post types

= 1.4.3 =
* Added: Button in options page to reset APSW options

= 1.4.2 =
* Fixed Bug: serialize/unserialize Warning issue

= 1.4.1 =
* Added: "Settings" link on plugins page
* Changed: "Popular Authors" widget to "Popular Users" widget
* Changed: "Popular Users" logic by "comments count"
* Changed: "Simple Tabs" option ON by default

= 1.4.0 =
* Added: General Widget for Popular Authors with dynamic period of stat date
* Added: General Widget for Popular Posts with dynamic period of stat date
* Added: Widget display settings for custom post types
* Added: "Page Views" statistic information under post content
* Added: Language translation support with .mo and .po files
* Fixed Bug: Correction with some statistic information and incomplete cat/post lists

= 1.3.2 =
* Fixed Bug: Problem with "Popular Authors" widget on Wordpress multi-sites

= 1.3.1 =
* Fixed Bug: Problem with options page redirection on Wordpress multi-sites

= 1.3.0 =
Added: Public functions to show statistics directly from template file

= 1.2.0 =
Added: Simple tabs for widgets on front-end

= 1.1.1 =
Matching Wordpress 4.0 Standards

= 1.0.0 =
Initial version
