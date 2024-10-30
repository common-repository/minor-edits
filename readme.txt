=== Minor Edits ===
Contributors: geekysoft
Tags: Atom, caching, last modified
Requires at least: 4.4.1
Tested up to: 5.0.2
Stable tag: 1.0.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Don’t update a post’s “modified” timestamp for insignificant edits.


== Description ==

Whenever you make any edits to your posts, WordPress will set the post’s “last modified/updated” date to the time of your edit. However, neither your readers nor most publishing protocols don’t think of your posts as updated just because you fixed a few small spelling mistake or appended a new set of tags.

The *Minor Edits* makes an automatic determination of whether an edit is significant or insignificant. When a edit is determined to be significant, the last modified date is set to now; but when an edit is determined to be insignificant the last modified date is left unchanged.

This behaviour lets you use the last modification time in your themes and syndication feed with more authority. When something says it’s updated, it means the post actually has been updated significantly rather than just having a tiny spelling correction or a fixed link.

The *Minor Edits* plugin is an excellent companion for WordPress themes and widgets that displays a list of recently updated posts; or automatically prefixes updated posts with “Updated:”.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/minor-edits/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the ‘Plugins’ screen in WordPress.
3. Review the available options on the options page


== Frequently Asked Questions ==

= Why? =

The Atom syndication feed standard says “[Last modified] indicates the last time the entry was modified in a significant way. This value need not change after a typo is fixed, only after a substantial modification.” You can find similar wordings and definitions in many standards where WordPress use the post modified date.

When you use this plugin, it can assists the use of plugins that produces a lists of recently updated posts. It can also be used with themes that emphasizes the date a post was last modified over it’s published date. (Like a non-time-linear website versus a blog, for example.)

= What is defined as a small edit? =

The specific qualifier is more than 5 letters changed in the post title, or more than 50 characters in the post text (including formatting). These numbers can be configured on the options page.

Changes to tags and other metadata except the publication time and status, and the slug are ignored. E.g. changes to tags won’t be considered a significant change on their own.

= Can I force a post to update its modification time? =

If you consider a post update to truly be significant for your readers but it doesn’t meet the definition of a small edit; you can change the title to include “Updated: ” (9 characters), or include a new paragraph of text at the bottom of your post like “This post was updated to include more information about ducks.” (62 characters).

You can also disable this plugin from the plugin screen, make the change to your post, and then enable the plugin again afterwards.

= I’m a developer. Got any action hooks? =

Yes, indeed. See the file HACKING in the plugin installation directory.


== Known problems ==

= Doesn’t work for very long posts. =

Posts longer than 40 000 characters (roughly 5000 words in English) are not processed by this plugin and will always be marked as modified. This limit is put in place to ensure the plugin doesn’t cause the PHP process to run out of memory under default memory limitations.


== Changelog ==

= 1.0.2 =

* Added a new options page that lets you customize the number of characters that needs to be different for a change to be considered different.
* Changed the number of characters that must be different from 40 to 50.
* Added a new filter `minor_edits_text_diff_filter` for modifying strings prior to comparison for differences. (See HACKING file.)

= 0.9.3 =

* Resolved a problem where modification dates weren’t updated when publishing posts in the future.

= 0.9.2 =

* New action hook for developers: `minor_edits_post_status_minor_update($new_post, $old_post)` fired after minor updates (modified time not updated).
* New action hook for developers: `minor_edits_post_status_significant_update($new_post, $old_post)` fired for significant updates.

= 0.9 =

* Initial public release.

= 0.8 =

* Here be dragons.
