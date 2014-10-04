=== SSL Grins ===
Tags: clickable, smilies, comments, bbpress, wpgrins
Contributors: alexkingorg, ronalfy, Ipstenu
Requires at least: 3.7
Tested up to: 4.0
Stable tag: 5.2.3
Donate link: https://store.halfelf.org/donate/
License: GPL2

SSL Grins provides smilies for your comment areas and is compatible with SSL Administration.

== Description ==

SSL Grins will provide clickable smilies for both the post form in the admin interface and the comments form of your blog. SSL Grins is a fork of WP Grins that allows for use on a site using [Administration over SSL](http://codex.wordpress.org/Administration_Over_SSL) or not, auto-detecting which is why and displaying either way.

As of version 4.0, SSL Grins also works on bbPress (the plugin) and has an option to turn it on or off as desired.

Tested on Single Site and MultiSite.

* [Plugin Site](http://halfelf.org/plugins/wp-grins-ssl/)
* [Donate](https://store.halfelf.org/donate/)

== Installation ==

1. Install and activate the plugin.
2. Visit your <em>Discussion Settings</em> page.
3. Look for 'Smilies' and check boxes as desired. (Comments are activated by default)

== Changelog ==

= 5.2.3 =
* 2014 Oct 4, by Ipstenu
* Adjusting check for [New Smilies](https://github.com/avryl/new-smileys)' new smilies to cover for people who leave the folder named 'new-smilies-master' - THANKS GITHUB!

=5.2.2 =
* 2014 May 15, by Ipstenu
* Better checks, less jank, proper hiding
* New smilies hides "uneasy" as well due to JS being a turd

= 5.2.1 =
* 2014 Apr 22, by Ipstenu
* Adjusting check for [New Smilies](https://github.com/avryl/new-smileys)' new smilies.
* Cleaned janky hide, since it's only for New Smilies

= 5.2 = 
* 2014 Mar 25, by Ipstenu
* Fixed comment default on to actually be, oh, on.
* Added in check for 'new' smilies (If you're using [New Smilies](https://github.com/avryl/new-smileys) or, in the future, Jetpack or core, hard to say )
* Hides specific new smilies ... kind of. It's janky.
* Dropping support for old WP.

= 5.1.1 =
* 2013 Dec 9, by Ipstenu
* Tweak to bbPress call because JJJ hates me (not really)
* Force prevent any code from running on login pages.
* Proper Internationalization

= 5.1 =
* 2013 May 14, by Ipstenu
* Small conflict with one of my plugins
* Major conflict with font-emoticons

= 5.0 =
* 2013 May 13, by Ipstenu
* Internationalization
* Proper singletons and checking
* Better handling of options
* Link to settings on plugins page

= 4.4 = 
* 2013 January 20, by Ipstenu
* Allowing for filters so you can change the images.

= 4.3 = 
* 2012 June 18, by Ipstenu
* bbPress 2.1's Fancy Editor borks this.

= 4.2.1 =
* 2012 June 02, by Ipstenu
* Typos. A-freaking-gain

= 4.2 =
* 2012 June 02, by Ipstenu
* bbPress refining (testing with 2.1 as well since that's a thing)

= 4.1 =
* 2012 May 07, by Ipstenu
* bbPress is an option.

= 4.0 =
* 27 April 2012 by Ipstenu
* Major bump. Why? Oh this little thing I like to call bbPress. Fully supported.

= 3.1 =
* 17 April 2012 by Ipstenu
* Trying to fix why it decides, magically, not to show up. Sometimes. Should work. Works on 3.4...

= 3.0 =
* 22 February 2012 by Ipstenu
* Reverted back to Alex's use of Prototype because something went funky cold medina with ajax.

= 2.2 =
* 1 October 2011 by Ipstenu
* Minification for faster everythinging.

= 2.1 =
* 22 September 2011 by Ipstenu
* Removing PHP 4 support (WP doesn't).
* Cleaning out unused aspects from removal of admin panel.

= 2.0 =
* 21 September 2011 by Ipstenu
* Fixed issue with SSL adminstration causing the icons not to show up.
* Removed admin panel. It's 2011. If your theme doesn't have wp_head, you have other issues.
* Consolidated stylesheets (dropping support for IE 6).
* Renamed files and pulled into one directory.
* Compressed files.

= 1.1 = 
* 04 November 2009 by Ronalfy
* Fixed bug where grins would show up in the comments panel.
* Re-did the JavaScript to it's completely separate and only runs when necessary.
* Added admin panel option to manual insert smilies on a page or a post.

= 1.0 = 
* 25 October 2009 by Ronalfy
* First release.  Yay!

== Frequently Asked Questions ==

= Why don't the smilies show up in my comments form? =
Your theme must include the `wp_head` call and the comments field in your theme must have an id of `comment`.  This version of the plugin does not support manual insertion, because, frankly, no decent WordPress theme is lacking `wp_head` anymore. If yours is, get a new theme. I'm not supporting bad code (unless it's mine).

= I have wp_head and they still don't show up =

If you're using Google Pagespeed, be careful how much you concatonate JS and CSS. It's complicated.

= Why did this start with version 2? =
Because the previous fork-source was 1.0, and this is really just an extension of all that work.  Wanted to keep Ronafly's credits up in there!

= Will you support BuddyPress? =
Not at this time as I'm not using it enough to make it sustainable.

= This isn't showing up on bbPress! =

If you're using the bbPress Fancy Editor, it won't work. I have not yet debugged this.