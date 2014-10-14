=== BJ Lazy Load ===
Contributors: bjornjohansen
Donate link: http://www.kiva.org/
Tags: images, iframes, lazy loading, jquery, javascript, optimize, performance, bandwidth, responsive design, hidpi, retina
Author URI: http://twitter.com/bjornjohansen
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 0.7.5

Lazy loading makes your site load faster and saves bandwidth. Uses jQuery and degrades gracefully for non-js users. Works with both images and iframes.

== Description ==
Lazy loading makes your site load faster and saves bandwidth.

This plugin replaces all your post images, post thumbnails, gravatar images and content iframes with a placeholder and loads the content as it gets close to enter the browser window when the visitor scrolls the page. Also works with text widgets.

You can also lazy load other images and iframes in your theme, by using a simple function.

Non-javascript visitors gets the original element in noscript.

= Size optimized images =
* Automaticly serve scaled down images in responsive designs
* Automaticly serve hiDPI images for hiDPI screens (like Apples Retina Display)

Please let me know if you have any issues. Fastest way to get a response is by Twitter: http://twitter.com/bjornjohansen

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

= Which browsers are supported? =
The included JavaScript is tested in Firefox 2+, Safari 3+, Opera 9+, Chrome 5+, Internet Explorer 6+

= I'm using a CDN. Will this plugin interfere? =
Lazy loading works just fine. The images will still load from your CDN.

As of version 0.6.0, serving responsive and hiDPI images will not work if you're using a CDN. Pull zones will be supported in the near future.

= The plugin doesn't work/doesn't replace my images =
Probably, your theme does not call wp_footer(). Edit the plugin settings to load in wp_head() instead.

= How can I verify that the plugin is working? =
Check your HTML source or see the magic at work in Web Inspector, FireBug or similar.

== Changelog ==

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



