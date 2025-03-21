=== Integrity Properties ===
Contributors: paulDilinger
Tags: property listings, real estate, gutenberg blocks, properties
Requires at least: 5.9
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Custom Gutenberg blocks for displaying property listings for Integrity Homes.

== Description ==

A custom WordPress plugin for displaying property listings with state-based filtering.

Features:
* Display property listings with a clean, modern design
* Filter properties by state (Virginia, Maryland, or both)
* Customizable display options (show/hide badge, price, excerpt, address)
* Responsive design for all screen sizes
* Gutenberg blocks and shortcodes for flexible implementation

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/integrity-properties` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the new blocks in the Gutenberg editor by searching for "Property"

== Frequently Asked Questions ==

= How do I add new properties? =

Properties are currently managed through the property-data.php file. In future versions, properties will be manageable through the WordPress admin.

= Can I customize the colors? =

Yes, the plugin uses Tailwind CSS with custom color variables. You can override these in your theme's stylesheet or in the tailwind.config.js file.

== Usage ==

= Shortcode Usage =

Basic Usage:

Display Virginia property only:
`[integrity_property state="virginia"]`

Display Maryland property only:
`[integrity_property state="maryland"]`

Display both properties:
`[integrity_property]`
or
`[integrity_property state="both"]`

Additional Options:

Control which elements are displayed:
`[integrity_property state="virginia" show_badge="yes" show_price="yes" show_excerpt="yes" show_address="yes"]`

Available parameters:
* `state`: Filter by state (`virginia`, `maryland`, or `both`) - default: `both`
* `show_badge`: Show/hide the award badge (`yes` or `no`) - default: `yes`
* `show_price`: Show/hide the price (`yes` or `no`) - default: `yes`
* `show_excerpt`: Show/hide the property description (`yes` or `no`) - default: `yes`
* `show_address`: Show/hide the address (`yes` or `no`) - default: `yes`

= Display Modes =

* Single Property Display: When showing only Virginia or Maryland properties, the layout will display the image on the left and content on the right (on desktop) and stack vertically on mobile.
* Dual Property Display: When showing both properties, they will be displayed side by side on larger screens and stack vertically on smaller screens.

== Development ==

= Building the Plugin =

`npm run build`

= Styling =

The plugin uses Tailwind CSS for styling. Main style files:
* `src/index.css`: Contains Tailwind directives and custom components

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release

== License ==

This plugin is proprietary and intended for use by Integrity Properties only.