=== Whistles Styles ===

Tags: widget, shortcode, jquery, tabs, toggle, accordion
Requires at least: 3.6
Tested up to: 3.8
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Tabs, Toggles, Accordions

== Description ==

If you'd like to have several selectable styles for whistle groups then the Whistles Styles plugin might be for you. It helps you to organize differently styled tabs, sliders, portfolios, accordions, toggles, pills and all kinds of content pieces (aka posts) you need to group somehow. You can create pluggable output classes for your styles, which will then appear as additional types in the shortcode popup UI. You can remove types like toggles or accordions by simply not having styles for these types inside a custom styles directory.

Whistles Styles are styles you can choose from while you edit your posts. Even more: this plugin enables you to both attach these predefined styles to a choosen display type of bundled whistles and to create your own styles - or even create yet uninvented new display types. Adding styles is easy. Copy a CSS file, name it properly, so that the plugin will recognize it, then apply your desired changes and you're done. Creating new types, however, needs certainly some knowledge of PHP, HTML, CSS and targets rather web developers than end users - what might be changed in the future.

So if you want to have tabs not only at the top of grouped content, but by choice also at one of both sides or even at the bottom, if you want to let them scale responsively to small screens in different manners - then you should propably check out this plugin.


### Features

* A convenient "styles" dropdown field is added to the shortcode media button popup of the Whistles plugin.
* Very simple style examples to display types of whistle groups (tabs, accordions, toggles) in a different way.
* Possibility to add new styles by simply putting them into a custom folder. After copying a style there it appears magically in the "Styles" drop down field on the shortcode popup.
* A style may be a JS file only and can represent a changed behaviour like e.g. sliding vs. fading tab content.
* Control which styles are shown to end users by creating and using a custom "whi-styles" folder.
* Configuration is done by working with files. Desired changes have to be made in customized static CSS/ JS files. See FAQ for the small naming convention you have to follow. No extra configuration page and options.

== Installation ==

1. Make sure that the Whistles plugin is installed and activated.
2. Upload the `whistles-styles` folder to your WordPress plugins directory.
2. Activate the "Whistles Styles" plugin through the "Plugins" menu in WordPress.
3. Read the FAQ for how to make own styles available and/or hide types and styles.

== Frequently Asked Questions ==

### Is it possible to submit my self created style to the project?

Of course! I'd love to incorporate great styles to this plugin. Please leave a link to your style in the forum so that others and me may test it in advance.

### How do I modify or even disable certain styles?

Copy the style files you want to use from the plugin's "styles" folder to a custom "whi-styles" folder inside your content directory. This custom folder overrules the plugin's "styles" folder. So if it exists, the styles folder of the plugin will not be used. Apply your changes only to files inside this custom folder! This plugin will never touch it's content. Types for which no style file(s) exist will not be shown on the shortcode popup.

### How do I have to name my customized style files to get them work?

Both the JS and/or the CSS files have to be named like (tabs|accordion|toggle|NEWTYPE)-CUSTOM-STYLE-DESCRIPTOR.(min.css|css|min.js|js), e.g. "tabs-bottom.min.css" and - in the case you provide a JS file - "tabs-bottom.js". Inclusion of existing *.min.js/ *.min.css files is prefered. These files must be placed inside a "whi-styles" folder, which has to exist in your WordPress content folder. So each custom "style" consists of one or two files inside "whi-styles" and maybe some images. Create subfolders of your choice if you like to keep media files of custom styles separated.

### How do I create an own type for whistle groups?

You need a bit of PHP knowledge to do so. Copy e.g. the file wp-content/plugins/whistles/inc/class-whistles-and-toggles.php into your "whi-styles" folder below your WP_CONTENT_DIR. Name it "class-whistles-styles-NEWTYPE.php" and then edit the file. Most importantly change the line: "class Whistles_And_Toggles extends Whistles_And_Bells {" to "class Whistles_Styles_NEWTYPE extends Whistles_And_Bells {"
and delete the lines, where JS of the Whistles plugin is loaded (by wp_enqueue_script() - you do this by placing your JS style file in the same dir). finally change the HTML of the class method set_markup() as needed.

### Can you help me?

Use this plugin's forum to ask a question. I can't promise to help fast, but I'll do.


== Screenshots ==

1. Style choices on the `[whistles]` shortcode media button popup
2. Two tabbed groups with one having tabs on the left side and with another having them on the top.


== Changelog ==

### Version 0.2

* Change: name of cumstom styles directory changed from "whistles-styles" to "whi-styles"

### Version 0.1

* Initial release.
