=== SSL Grins ===
Tags: clickable, smilies, comments, bbpress, wpgrins
Contributors: alexkingorg, ronalfy, Ipstenu
Requires at least: 3.7
Tested up to: 4.9
Stable tag: 5.3.2
Donate link: https://ko-fi.com/A236CENl/
License: GPL2

SSL Grins provides smilies for your comment areas and is compatible with SSL Administration.

== Description ==

SSL Grins will provide clickable smilies for both the post form in the admin interface and the comments form of your blog. SSL Grins is a fork of WP Grins that allows for use on a site using [Administration over SSL](http://codex.wordpress.org/Administration_Over_SSL) or not, auto-detecting which is why and displaying either way.

As of version 4.0, SSL Grins also works on bbPress (the plugin) and has an option to turn it on or off as desired.

Tested on Single Site and MultiSite.

* [Plugin Site](http://halfelf.org/plugins/wp-grins-ssl/)
* [Donate](https://ko-fi.com/A236CENl/)

== Installation ==

1. Install and activate the plugin.
2. Visit your <em>Discussion Settings</em> page.
3. Look for 'Smilies' and check boxes as desired. (Comments are activated by default)

== Changelog ==

= 5.3.2 =
* 2015 March, by Ipstenu
* Translation fixes

= 5.3.1 =
* 2015 May 1, by Ipstenu
* Fixing vertical align with images that aren't emoji (because they don't have a smile yet)

= 5.3 =
* 2015 Apr 8, by Ipstenu
* Compatibility with Emoji in WP 4.2+

== Frequently Asked Questions ==

= Why don't the smilies show up in my comments form? =
Your theme must include the `wp_head` call and the comments field in your theme must have an id of `comment`.  This version of the plugin does not support manual insertion, because, frankly, no decent WordPress theme is lacking `wp_head` anymore. If yours is, get a new theme. I'm not supporting bad code (unless it's mine).

= I have wp_head and they still don't show up =

If you're using Google Pagespeed, be careful how much you concatenate JS and CSS. It's complicated.

= Some of my smilies look 'weird' =

Are you on WordPress 4.2 or higher? You're probably using emoji. There are four smilies that (for some reason) don't have emoji and default to images. This isn't our fault, it's Twitter. See [Twemoji issue #59](https://github.com/twitter/twemoji/issues/59) for more information, but eventually this will be fixed in WP core itself. At that time, this plugin should magically fix itself.

= Why did this start with version 2? =
Because the previous fork-source was 1.0, and this is really just an extension of all that work.  Wanted to keep Ronafly's credits up in there!

= Will you support BuddyPress? =
Not at this time as I'm not using it enough to make it sustainable.

= This isn't showing up on bbPress! =

If you're using the bbPress Fancy Editor, it won't work. I have not yet debugged this.

= Why are some smilies missing? =

If you mean some of the 'new .com smilies,' then yes, they are. The following are 'hidden': "bear", "wordpress", "martini", "developer", "whiterussian", "burrito", "facepalm", "kitten", "uneasy"

And those aren't the codes. Try <code>(w)</code> or <code>=^-^=</code> and see what happens. These are easter eggs.

= Does this support emoji? =

Yes. Full WordPress 4.2 support. It's built to only show the default smilies, though otherwise the line would be too long.
