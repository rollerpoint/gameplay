=== Change Author Link Structure ===
Contributors: wpyb
Tags: author, permalink, username, userid, url, author slug, author base, rewrite rules
Requires at least: 4.0
Tested up to: 4.8
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

To prevent that usernames are publically visible, the username in the author's permalink is replaced with the author's ID.  

== Description ==

To prevent that usernames are publically visible, the username in the author's permalink is replaced with the author's ID.  
In case the page is invoked with the default permalink structure, it is displayed "Page not found".

Additionally it is possible to modify the author base. It is recommended to make use of this plugin to change it, but it also works with other plugins that have the functionality to customize the author base.

== Installation ==

1. Upload `change-author-link-structure.zip` to the `/wp-content/plugins/` directory
2. Unzip `change-author-link-structure.zip`.
3. Activate the plugin in the admin menu 'Plugins'.

== Changelog ==

= 0.1 =
* First release
= 0.1.1 =
* Readme Update
= 0.2 =
* Bug Fix: Addition to ensure that in case the username is manually entered, it is displayed 404 page instead of the standard post page.
= 1.0 =
* New Feature: Support for individual author base
* Bug Fix: Rewrite rules for feed pages have been corrected
* Enhancement: Several code improvements

