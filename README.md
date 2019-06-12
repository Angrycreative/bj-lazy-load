# BJ Lazy Load [![Average time to resolve an issue][issue-resolution-shield-img]][issue-resolution-shield] ![WordPress Plugin Downloads][wp-monthly-dl-shield-img] ![WordPress Plugin Active Installs][wp-active-inst-shield-img] ![WordPress Plugin Rating][wp-rating-shield-img]

BJ Lazy Load is a WordPress plugin that improves site loading times by lazy loading images and iframes.
The plugin is available through the [plugin repository on wordpress.org](https://wordpress.org/plugins/bj-lazy-load/).

Lazy loading makes your site load faster and saves bandwidth.

This plugin replaces all your post images, post thumbnails, gravatar images and content iframes with a placeholder and loads the content as it gets close to enter the browser window when the visitor scrolls the page. Also works with text widgets.

Since it works with iframes, it also covers embedded videoes from YouTube, Vimeo etc.

You can also lazy load other images and iframes in your theme, by using a simple filter.

Non-javascript visitors gets the original element in noscript.


## Project setup
You will need [npm](https://www.npmjs.com/get-npm) to build the plugin.

```sh
git clone --recurse-submodules git+ssh://git@github.com/Angrycreative/bj-lazy-load.git
cd bj-lazy-load
npm i
gulp
```


## Linting the code

PHP linting requires [PHP Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer#installation) to be installed.

```sh
# lint everything
npm run lint

# lint only PHP or only JavaScript
npm run lint:php
npm run lint:js
```

[issue-resolution-shield]: http://isitmaintained.com/project/Angrycreative/bj-lazy-load
[issue-resolution-shield-img]: http://isitmaintained.com/badge/resolution/Angrycreative/bj-lazy-load.svg
[wp-monthly-dl-shield-img]: https://img.shields.io/wordpress/plugin/dm/bj-lazy-load.svg?label=downloads
[wp-active-inst-shield-img]: https://img.shields.io/wordpress/plugin/installs/bj-lazy-load.svg
[wp-rating-shield-img]: https://img.shields.io/wordpress/plugin/rating/bj-lazy-load.svg
