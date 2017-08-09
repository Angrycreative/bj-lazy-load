=== BJ Lazy Load ===
Contributors: bjornjohansen, arontornberg, angrycreative
Donate link: http://www.kiva.org/
Tags: images, iframes, lazy loading, javascript, optimize, performance, bandwidth
Author URI: http://twitter.com/bjornjohansen
Requires at least: 3.5
Tested up to: 4.9
Stable tag: 1.0.9

Lazy loading for images and iframes makes your site load faster and saves bandwidth. Uses no external JS libraries and degrades gracefully for non-js users.

== Description ==
Lazy loading makes your site load faster and saves bandwidth.

This plugin replaces all your post images, post thumbnails, gravatar images and content iframes with a placeholder and loads the content as it gets close to enter the browser window when the visitor scrolls the page. Also works with text widgets.

Since it works with iframes, it also covers embedded videoes from YouTube, Vimeo etc.

You can also lazy load other images and iframes in your theme, by using a simple filter.

Non-javascript visitors gets the original element in noscript.

Compatible with the <a href="https://wordpress.org/plugins/ricg-responsive-images/">RICG Responsive Images</a> plugin for responsive images.

Please let me know if you have any issues. Fastest way to get a response is by Twitter: https://twitter.com/bjornjohansen

= Translations =
* Russian (ru_RU) by Elvisrk
* Hebrew (he_IL) by Imri Sagive
* Polish (pl_PL) by Maciej Gryniuk
* Norwegian Bokmål (nb_NO) by Bjørn Johansen

<a href="https://github.com/bjornjohansen/bj-lazy-load">Development happens at GitHub</a>. Pull requests are very welcome.

== Installation ==
1. Download and unzip plugin
2. Upload the 'bj-lazy-load' folder to the '/wp-content/plugins/' directory,
3. Activate the plugin through the 'Plugins' menu in WordPress.

== Optional usage ==
If you have images output in custom templates or want to lazy load other images in your theme, you may pass the HTML through a filter:

`<?php
$img_html = '<img src="myimage.jpg" alt="">';
$img_html = apply_filters( 'bj_lazy_load_html', $img_html );
echo $img_html;
?>`

Note for developers: The filter has a priority of 10.


== Frequently Asked Questions ==

= Whoa, this plugin is using JavaScript. What about visitors without JS? =
No worries. They get the original element in a noscript element. No Lazy Loading for them, though.

= I'm using a CDN. Will this plugin interfere? =
Lazy loading works just fine. The images will still load from your CDN.

= How can I verify that the plugin is working? =
Check your HTML source or see the magic at work in Web Inspector, FireBug or similar.

== Changelog ==

= Version 1.0.9 =
* Do not generate tiny image size if unless low-res preview image is used
* Default placeholder image transparency fix
* Lazy load image sizes attribute to avoid w3c validation error
* Change activation order of src and srcset to avoid loading both original and responsive version of image (by Lucian Florian)

= Version 1.0.8 =
* Skip classes regex fix
* Amp compatibility (by mustafauysal)

= Version 1.0.7 =
* Update sponsored by Bonnier Tidskrifter
* Fixed issue with srcset not lazy loading (by krispy1298)
* Compatibility with BadgeOS plugin (by rohitmanglik)
* WP's theme style for submit button. (by odie2)
* Images will now get the lazy-loaded class onload
* Added optional rudimentary LQIP solution
* Added working regex for selecting elements to skip

= Version 1.0.6 =
* Fixed bug when multiple iframes was on the same line of HTML code

= Version 1.0.5 =
* Added Polish language (by Maciej Gryniuk).
* Remove leftover console.log() from debugging -- oh, crap ... I know :-(

= Version 1.0.4 =
* We do not touch the feeds

= Version 1.0.3 =
* Fixed issue where some images wouldn’t be loaded on load under certain conditions.
* Small performance improvement

= Version 1.0.2 =
* PHP 5.2 compatibility again

= Version 1.0.1 =
* Fixes issue with missing placeholder

= Version 1.0 =
* Internal rewrite. Code cleanup.
* Supports 3rd party filters
* Supports most infinite scroll (or content lazy loading) plugins (all that triggers post-load)
* No more TimThumb
* No more dependant on 3rd party JS, not even jQuery
* Removed custom responsice/hidpi image handling in favour of compatibility with the RICG Responsive Images plugin
* Added translations to Hebrew (he_IL) by Imri Sagive

= Version 0.7.5 =
* Also applies to text widgets (Thanks to Sigurður Guðbrandsson)

= Version 0.7.4 =
* Skips lazy loading of data-URIs

= Version 0.7.3 =
* Works with newlines in the HTML string for the IMG element

= Version 0.7.2 =
* Re-minified the combined js file for better compability with faulty minifiers

= Version 0.7.1 =
* Proper encoding of non-ASCII characters in filenames when using responsive or hiDPI images (thanks @testsiteoop)

= Version 0.7.0 =
* Added meta box to all public post types to exclude BJ Lazy Load for individual posts/pages
* Placeholder image is replaced with a really short data-uri (thanks @jruizcantero)
* Added a proper WordPress filter method for arbitrary HTML filtering with: apply_filters( 'bj_lazy_load_html', $html )
* Updated scbFramework to release 58 (no more strict warnings in admin)
* Updated jQuery.sonar to latest version (as of 2013-11-13)
* Added POT file (Go translate!)
* Added translation to Norwegian Bokmål (nb_NO)
* Added translation to Russian (ru_RU) by Elvisrk

= Version 0.6.10 =
* Responsive and HiDPI images works with MultiSite subfolders
* Lazy loading is disabled on Opera Mini
* Removed leftin print_filters_for() function (sry)
* User definable threshold

= Version 0.6.9 =
* Bugfix: Single quotes for the class attribute is now handled (thanks @kReEsTaL)
* Bugfix: Removed strict error notice (thanks syndrael)

= Version 0.6.8 =
* Bugfix: sonar.js wouldn't load properly when SCRIPT_DEBUG was set to true (thanks @techawakening)

= Version 0.6.7 =
* Combined JS files for faster loading
* Bugfix for when viewport is resized – now triggering scroll event (thanks @kReEsTaL)

= Version 0.6.6 =
* Option to disable BJ Lazy Load for MobilePress

= Version 0.6.5 =
* Iframe lazy loading is now compatible with Gravity Forms' ajax forms.

= Version 0.6.4 =
* Disable when viewing printable page from WP-Print

= Version 0.6.3 =
* Detects WPTouch Pro as well

= Version 0.6.2 =
* Bugfix: Remove notice of undefined constant when SCRIPT_DEBUG isn't defined

= Version 0.6.1 =
* Bugfix: The infinite_scroll option wasn't initialized

= Version 0.6.0 =
* Optionally serving size optimized images for responsive design/adaptive layout
* Optionally serving hiDPI images (retina support)
* Option to disable BJ Lazy Load for WPTouch
* Fixed issue with infinite scroll (must be enabled on options screen)
* Upgraded jQuery.sonar to latest version

= Version 0.5.4 =
* Possible to skip lazy loading of certain images with specified classnames
* Made the placeholder image override an option setting in wp-admin

= Version 0.5.3 =
* Added filter: bj_lazy_load_placeholder_url - override placeholder image (should be an option setting in the future)

= Version 0.5.2 =
* Added the fadeIn effect

= Version 0.5.1 =
* Lowered jQuery version dependency
* New options: More granular control on what content to lazy load

= Version 0.5.0 =
* Complete rewrite
* Replaced JAIL with jQuery.sonar to accomodate for iframe lazy loading
* Added lazy loading for iframes
* The manual filter code now works as it should, lazy loading all images instead of just the first.

= Version 0.4.0 =
* Upgraded JAIL to version 0.9.9, fixing some bugs. Note: data-href is now renamed data-src.

= Version 0.3.3 =
* Replaced an anonymous function call causing error in PHP < 5.3

= Version 0.3.2 =
* The wp_head caller selector was added to the option page

= Version 0.3.1 =
* Also with d.sturm's fix (thanks)

= Version 0.3.0 =
* Added BJLL::filter() so you can lazy load any images in your theme
* Added the option to load in wp_head() instead (suboptimal, but some themes actually don't call wp_footer())
* Correctly removed the lazy loader from feeds

= Version 0.2.5 =
* Fixes Unicode-issue with filenames

= Version 0.2.4 =
* Now (more) compliant to the WP coding style guidelines.
* All strings localized
* Translations get loaded
* POT file included (send me your translations)
* Norwegian translation included

= Version 0.2.3 =
* Now using DOMDocument for better HTML parsing. Old regexp parsing as fallback if DOMDocument is not available.

= Version 0.2.2 =
* Added CSS. No longer need for hiding .no-js .lazy
* Added options whether to include JS and CSS or not

= Version 0.2.1 =
* Added options: Timeout, effect, speed, event, offset, ignoreHiddenImages
* Combining the two JS files for faster loading
* Renamed the plugin file from bj-lazyload.php to bj-lazy-load.php to better fit with the plugin name

= Version 0.2 =
* Added options panel in admin
* Added option to lazy load post thumbnails
* Skipped the lazy loading in feeds

= Version 0.1 =
* Released 2011-12-05
* It works (or at least it does for me)

== Upgrade Notice ==

= 0.7.5 =
Also applies to text widgets

= 0.7.2 =
Improved minification compability

= 0.6.10 =
Works with Opera Mini

= 0.6.9 =
Bugfix release

= 0.6.8 =
Bugfix: Works with SCRIPT_DEBUG

= 0.6.7 =
Faster loading and bugfix

= 0.6.6 =
MobilePress compatible

= 0.6.5 =
Improved compability with Gravity Forms

= 0.6.4 =
Disable when viewing printable page from WP-Print

= 0.6.0 =
Image size adaption. Infinite scroll & WPTouch fixes.

= 0.5.4 =
Custom placeholder. Skip selected images.

= 0.5.2 =
Added fadeIn effect

= 0.5.0 =
Lazy load images and iframes. Complete rewrite.

= 0.4.0 =
New JAIL version.

= 0.3.2 =
Lazy load any image in your theme. Load in head.

= 0.3.1 =
Lazy load any image in your theme. Load in head.

= 0.3.0 =
Lazy load any image in your theme

= 0.2.5 =
Now works with Unicode filenames

= 0.2.4 =
Better localization

= 0.2.3 =
Improved image replacement

= 0.2.2 =
More options and improved non-JS display.

= 0.2.1 =
More options and faster loading.

= 0.2 =
Lazy load post thumbnails too and stays out of your feeds.



