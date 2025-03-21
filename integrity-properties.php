<?php
/**
 * Plugin Name: Integrity Properties
 * Description: Custom Gutenberg blocks and shortcodes for property listings
 * Version: 1.1.0
 * Author: Paul Dilinger
 * Text Domain: integrity-properties
 * 
 * @package IntegrityProperties
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('INTEGRITY_PROPERTIES_PATH', plugin_dir_path(__FILE__));
define('INTEGRITY_PROPERTIES_URL', plugin_dir_url(__FILE__));

// Property data store
require_once INTEGRITY_PROPERTIES_PATH . 'inc/property-data.php';

/**
 * Initialize the plugin and register blocks
 *
 * @return void
 */
function integrity_properties_init() {
    // Register the property card block
    register_block_type(INTEGRITY_PROPERTIES_PATH . 'blocks/property-card', array(
        'render_callback' => 'integrity_render_property_card'
    ));
    
    // Register the property section block
    register_block_type(INTEGRITY_PROPERTIES_PATH . 'blocks/property-section', array(
        'render_callback' => 'integrity_render_property_section'
    ));
}
add_action('init', 'integrity_properties_init');

/**
 * Register all shortcodes
 *
 * @return void
 */
function integrity_register_shortcodes() {
    add_shortcode('integrity_property', 'integrity_property_shortcode');
}
add_action('init', 'integrity_register_shortcodes');

/**
 * Property shortcode function
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function integrity_property_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(array(
        'state' => 'both',
        'show_badge' => 'yes',
        'show_price' => 'yes',
        'show_excerpt' => 'yes',
        'show_address' => 'yes'
    ), $atts, 'integrity_property');
    
    // Convert string values to boolean
    $show_badge = $atts['show_badge'] === 'yes';
    $show_price = $atts['show_price'] === 'yes';
    $show_excerpt = $atts['show_excerpt'] === 'yes';
    $show_address = $atts['show_address'] === 'yes';
    
    // Get property based on state
    $state = strtolower($atts['state']);
    
    // If state is 'both', show both properties side by side
    if ($state === 'both') {
        $properties = array(
            'virginia' => Integrity_Property_Data::get_property('virginia'),
            'maryland' => Integrity_Property_Data::get_property('maryland')
        );
        
        if (!$properties['virginia'] || !$properties['maryland']) {
            return '';
        }
        
        ob_start();
        ?>
        <div class="w-full max-w-[1024px] mx-auto my-24 p-12 bg-primary-50 rounded-2xl shadow-md">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-4">A Word From Our Communities</h2>
            <p class="text-lg leading-relaxed text-gray-600 text-center mb-8 max-w-[800px] mx-auto">Integrity Homes takes pride in the commitment to creating not just houses, but beautiful spaces that are thoughtfully designed to enhance your lifestyle.</p>
            
            <div class="flex flex-wrap justify-center -mx-4">
                <?php foreach ($properties as $property_type => $property) : ?>
                    <?php 
                    // Set the community URL based on property type
                    $community_url = $property_type === 'maryland' 
                        ? 'https://yourintegrityhome.com/maryland/the-monument/' 
                        : 'https://yourintegrityhome.com/virginia/enclave/';
                    ?>
                    <div class="w-full md:w-1/2 xl:w-1/2 p-4">
                        <div class="group relative overflow-hidden rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
                            <div class="relative overflow-hidden rounded-t-lg">
                                <img src="<?php echo esc_url($property['featured_image']); ?>" class="w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="<?php echo esc_attr($property['name']); ?>">
                                <div class="absolute bottom-4 left-4 bg-black bg-opacity-60 text-white px-4 py-2 rounded-lg">
                                    <?php echo esc_html($property['community_label']); ?>
                                </div>
                                <?php if ($show_badge && !empty($property['badge'])): ?>
                                    <img decoding="async" class="absolute top-4 right-4 w-24 h-24" src="<?php echo esc_url($property['badge']); ?>" alt="Award Badge">
                                <?php endif; ?>
                            </div>
                            <div class="bg-white p-6 rounded-b-lg">
                                <?php if ($show_price): ?>
                                    <div class="text-xl font-semibold text-primary mb-2">
                                        Priced from: <?php echo esc_html($property['price']); ?>
                                    </div>
                                <?php endif; ?>

                                <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo esc_html($property['name']); ?></h3>

                                <?php if ($show_excerpt): ?>
                                    <div class="text-sm text-gray-600 mb-4">
                                        <?php echo esc_html($property['excerpt']); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($show_address): ?>
                                    <div class="flex items-center text-sm text-gray-500 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-primary-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                        </svg>

                                        <?php echo esc_html($property['address']); ?>
                                    </div>
                                <?php endif; ?>

                                <a class="inline-block w-full px-4 py-3 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center mt-4" href="<?php echo esc_url($community_url); ?>" target="_blank" rel="noopener">
                                    View Community
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    } else {
        // For single state display, get the specific property
        $property_type = $state === 'virginia' ? 'virginia' : 'maryland';
        $property = Integrity_Property_Data::get_property($property_type);
        
        if (!$property) {
            return '';
        }
        
        // Set the community URL based on property type
        $community_url = $property_type === 'maryland' 
            ? 'https://yourintegrityhome.com/maryland/the-monument/' 
            : 'https://yourintegrityhome.com/virginia/enclave/';
        
        ob_start();
        ?>
        <div class="w-full max-w-[1024px] mx-auto my-24 p-12 bg-primary-50 rounded-2xl shadow-md">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-4">A Word From Our Communities</h2>
            <p class="text-lg leading-relaxed text-gray-600 text-center mb-8 max-w-[800px] mx-auto">Integrity Homes takes pride in the commitment to creating not just houses, but beautiful spaces that are thoughtfully designed to enhance your lifestyle.</p>
            
            <div class="w-full @container/card">
                <div class="@xl:grid @xl:grid-cols-2 group relative overflow-hidden rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
                    <div class="relative overflow-hidden">
                        <img src="<?php echo esc_url($property['featured_image']); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" alt="<?php echo esc_attr($property['name']); ?>">
                        <div class="absolute bottom-4 left-4 bg-black bg-opacity-60 text-white px-4 py-2 rounded-lg">
                            <?php echo esc_html($property['community_label']); ?>
                        </div>
                        <?php if ($show_badge && !empty($property['badge'])): ?>
                            <img decoding="async" class="absolute top-4 right-4 w-24 h-24" src="<?php echo esc_url($property['badge']); ?>" alt="Award Badge">
                        <?php endif; ?>
                    </div>
                    <div class="bg-white p-6 rounded-b-lg">
                        <?php if ($show_price): ?>
                            <div class="text-xl font-semibold text-primary mb-2">
                                Priced from: <?php echo esc_html($property['price']); ?>
                            </div>
                        <?php endif; ?>

                        <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo esc_html($property['name']); ?></h3>

                        <?php if ($show_excerpt): ?>
                            <div class="text-sm text-gray-600 mb-4">
                                <?php echo esc_html($property['excerpt']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_address): ?>
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-primary-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>

                                <?php echo esc_html($property['address']); ?>
                            </div>
                        <?php endif; ?>

                        <a class="inline-block w-full px-4 py-3 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center mt-4" href="<?php echo esc_url($community_url); ?>" target="_blank" rel="noopener">
                            View Community
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

/**
 * Shortcode template for single property.
 *
 * @param array $property Property data array.
 * @return string HTML output.
 */
function integrity_property_shortcode_template($property) {
    $has_badge = !empty($property['badge']);
    
    ob_start();
    ?>
    <div class="max-w-md mx-auto group overflow-hidden rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-lg bg-white">
        <?php if (!empty($property['featured_image'])) : ?>
            <div class="relative overflow-hidden">
                <img 
                    src="<?php echo esc_url($property['featured_image']); ?>" 
                    alt="<?php echo esc_attr($property['name']); ?>" 
                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"
                >
                <h3 class="m-0 p-2 absolute bottom-0 w-full text-white font-bold text-xl bg-gradient-to-t from-black/70 to-transparent">
                    <?php echo esc_html($property['name']); ?>
                </h3>
                <?php if ($has_badge) : ?>
                    <img decoding="async" class="absolute top-4 right-4 w-24 h-24" src="<?php echo esc_url($property['badge']); ?>" alt="Award Badge">
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="p-4">
            <div class="mb-2 text-sm font-semibold text-secondary">
                <?php echo esc_html($property['community_label']); ?>
            </div>
            
            <div class="flex justify-between items-center mb-2">
                <div class="text-xl font-bold text-primary">
                    <?php echo esc_html($property['price']); ?>
                </div>
            </div>
            
            <div class="flex items-center mb-4 text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 flex-shrink-0 text-primary">
                <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                </svg>
                <span class="ml-1 text-sm">
                    <?php echo esc_html($property['address']); ?>
                </span>
            </div>
            
            <div class="text-sm text-gray-700 mb-4">
                <?php echo wp_kses_post($property['excerpt']); ?>
            </div>
            
            <div class="mt-4 flex">
                <a href="<?php echo esc_url(home_url('/')); ?>property/<?php echo sanitize_title($property['state']); ?>" class="inline-block w-full px-4 py-3 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center mt-4">
                    View Property
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Shortcode template for dual properties.
 *
 * @param array $properties Array of property data.
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function integrity_properties_section_template($properties, $atts) {
    $has_heading = !empty($atts['heading']);
    $has_description = !empty($atts['description']);
    
    ob_start();
    ?>
    <div class="w-full bg-primary-50 py-10 px-4 rounded-lg">
        <div class="max-w-6xl mx-auto">
            <?php if ($has_heading) : ?>
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-4"><?php echo esc_html($atts['heading']); ?></h2>
            <?php endif; ?>
            
            <?php if ($has_description) : ?>
                <div class="text-gray-600 text-center mx-auto max-w-2xl mb-8 text-sm"><?php echo wp_kses_post($atts['description']); ?></div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach ($properties as $property) : ?>
                    <div class="max-w-md mx-auto group overflow-hidden rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-lg bg-white">
                        <?php if (!empty($property['featured_image'])) : ?>
                            <div class="relative overflow-hidden">
                                <img 
                                    src="<?php echo esc_url($property['featured_image']); ?>" 
                                    alt="<?php echo esc_attr($property['name']); ?>" 
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"
                                >
                                <div class="absolute bottom-4 left-4 bg-black bg-opacity-60 text-white px-4 py-2 rounded-lg">
                                    <?php echo esc_html($property['community_label']); ?>
                                </div>
                                <?php if (!empty($property['badge'])) : ?>
                                    <img decoding="async" class="absolute top-4 right-4 w-24 h-24" src="<?php echo esc_url($property['badge']); ?>" alt="Award Badge">
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-4">
                            <div class="mb-2 text-sm font-semibold text-secondary">
                                <?php echo esc_html($property['community_label']); ?>
                            </div>
                            
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-xl font-bold text-primary">
                                    <?php echo esc_html($property['price']); ?>
                                </div>
                            </div>
                            
                            <div class="flex items-center mb-4 text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 flex-shrink-0 text-primary">
                                <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1 text-sm">
                                    <?php echo esc_html($property['address']); ?>
                                </span>
                            </div>
                            
                            <div class="text-sm text-gray-700 mb-4">
                                <?php echo wp_kses_post($property['excerpt']); ?>
                            </div>
                            
                            <div class="mt-4 flex">
                                <a href="<?php echo esc_url(home_url('/')); ?>property/<?php echo sanitize_title($property['state']); ?>" class="inline-block w-full px-4 py-3 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center mt-4">
                                    View Property
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Enqueue plugin CSS/JS.
 */
function integrity_properties_enqueue_assets() {
    // Enqueue the main Tailwind CSS
    wp_enqueue_style(
        'integrity-properties-style',
        plugin_dir_url(__FILE__) . 'build/index.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'build/index.css')
    );
    
    // Admin scripts
    if (is_admin()) {
        // Register the block editor script
        wp_register_script(
            'integrity-properties-editor-script',
            plugin_dir_url(__FILE__) . 'build/index.js',
            ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'],
            filemtime(plugin_dir_path(__FILE__) . 'build/index.js'),
            true
        );

        // Localize the script with property data
        wp_localize_script(
            'integrity-properties-editor-script',
            'integrityPropertiesData',
            [
                'properties' => Integrity_Property_Data::get_properties(),
            ]
        );

        // Enqueue the script
        wp_enqueue_script('integrity-properties-editor-script');
    }
}
add_action('wp_enqueue_scripts', 'integrity_properties_enqueue_assets');
add_action('admin_enqueue_scripts', 'integrity_properties_enqueue_assets');

/**
 * Render callback for property card
 *
 * @param array $attributes Block attributes
 * @return string HTML output
 */
function integrity_render_property_card($attributes) {
    // Default attributes if not set
    $attributes = wp_parse_args($attributes, array(
        'propertyType' => 'virginia',
        'showBadge' => true,
        'showPrice' => true,
        'showExcerpt' => true,
        'showAddress' => true
    ));
    
    $property = Integrity_Property_Data::get_property($attributes['propertyType']);
    if (!$property) {
        return '';
    }

    ob_start();
    ?>
    <div class="w-full max-w-[400px] mx-auto my-24 p-12 bg-primary-50 rounded-2xl shadow-md">
        <div class="group relative overflow-hidden rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
            <div class="relative overflow-hidden rounded-t-lg">
                <img src="<?php echo esc_url($property['featured_image']); ?>" class="w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="<?php echo esc_attr($property['name']); ?>">
                <div class="absolute bottom-4 left-4 bg-black bg-opacity-60 text-white px-4 py-2 rounded-lg">
                    <?php echo esc_html($property['community_label']); ?>
                </div>
                <?php if ($attributes['showBadge'] && !empty($property['badge'])): ?>
                    <img decoding="async" class="absolute top-4 right-4 w-24 h-24" src="<?php echo esc_url($property['badge']); ?>" alt="Award Badge">
                <?php endif; ?>
            </div>
            <div class="bg-white p-6 rounded-b-lg">
                <?php if ($attributes['showPrice']): ?>
                    <div class="text-xl font-semibold text-primary mb-2">
                        Priced from: <?php echo esc_html($property['price']); ?>
                    </div>
                <?php endif; ?>

                <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo esc_html($property['name']); ?></h3>

                <?php if ($attributes['showExcerpt']): ?>
                    <div class="text-sm text-gray-600 mb-4">
                        <?php echo esc_html($property['excerpt']); ?>
                    </div>
                <?php endif; ?>

                <?php if ($attributes['showAddress']): ?>
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-primary-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>

                        <?php echo esc_html($property['address']); ?>
                    </div>
                <?php endif; ?>

                <a class="inline-block w-full px-4 py-3 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center mt-4" 
                   href="<?php echo $attributes['propertyType'] === 'maryland' 
                        ? 'https://yourintegrityhome.com/maryland/the-monument/' 
                        : 'https://yourintegrityhome.com/virginia/enclave/'; ?>" 
                   target="_blank" 
                   rel="noopener">
                    View Community
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render callback for property section
 *
 * @param array $attributes Block attributes
 * @return string HTML output
 */
function integrity_render_property_section($attributes) {
    // Default attributes if not set
    $attributes = wp_parse_args($attributes, array(
        'title' => 'Our Communities',
        'description' => 'Integrity Homes takes pride in the commitment to creating not just houses, but beautiful spaces that are thoughtfully designed to enhance your lifestyle.'
    ));
    
    // Get property data
    $virginia = Integrity_Property_Data::get_property('virginia');
    $maryland = Integrity_Property_Data::get_property('maryland');
    
    // If properties not found, return early
    if (!$virginia || !$maryland) {
        return '';
    }
    
    $wrapper_attributes = get_block_wrapper_attributes(array(
        'class' => 'w-full max-w-[1024px] mx-auto my-24 p-12 bg-primary-50 rounded-2xl shadow-md'
    ));
    
    ob_start();
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <div class="text-center mb-8">
            <?php if (!empty($attributes['title'])): ?>
                <h2 class="text-4xl font-bold text-center text-gray-800 mb-4"><?php echo esc_html($attributes['title']); ?></h2>
            <?php endif; ?>
            <?php if (!empty($attributes['description'])): ?>
                <p class="text-lg leading-relaxed text-gray-600 mb-8 max-w-[800px] mx-auto"><?php echo esc_html($attributes['description']); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="flex flex-wrap justify-center -mx-4">
            <!-- Virginia Property Card -->
            <div class="w-full md:w-1/2 xl:w-1/2 p-4">
                <div class="group relative overflow-hidden rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
                    <div class="relative overflow-hidden rounded-t-lg">
                        <img src="<?php echo esc_url($virginia['featured_image']); ?>" class="w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="<?php echo esc_attr($virginia['name']); ?>">
                        <div class="absolute bottom-4 left-4 bg-black bg-opacity-60 text-white px-4 py-2 rounded-lg">
                            <?php echo esc_html($virginia['community_label']); ?>
                        </div>
                        <img decoding="async" class="absolute top-4 right-4 w-24 h-24" src="<?php echo esc_url($virginia['badge']); ?>" alt="Award Badge">
                    </div>
                    <div class="bg-white p-6 rounded-b-lg">
                        <div class="text-xl font-semibold text-primary mb-2">
                            Priced from: <?php echo esc_html($virginia['price']); ?>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo esc_html($virginia['name']); ?></h3>
                        
                        <div class="text-sm text-gray-600 mb-4">
                            <?php echo esc_html($virginia['excerpt']); ?>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-primary-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>

                            <?php echo esc_html($virginia['address']); ?>
                        </div>
                        
                        <a class="inline-block w-full px-4 py-3 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center mt-4" href="https://yourintegrityhome.com/virginia/enclave/" target="_blank" rel="noopener">
                            View Community
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Maryland Property Card -->
            <div class="w-full md:w-1/2 xl:w-1/2 p-4">
                <div class="group relative overflow-hidden rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-lg">
                    <div class="relative overflow-hidden rounded-t-lg">
                        <img src="<?php echo esc_url($maryland['featured_image']); ?>" class="w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="<?php echo esc_attr($maryland['name']); ?>">
                        <div class="absolute bottom-4 left-4 bg-black bg-opacity-60 text-white px-4 py-2 rounded-lg">
                            <?php echo esc_html($maryland['community_label']); ?>
                        </div>
                        <img decoding="async" class="absolute top-4 right-4 w-24 h-24" src="<?php echo esc_url($maryland['badge']); ?>" alt="Award Badge">
                    </div>
                    <div class="bg-white p-6 rounded-b-lg">
                        <div class="text-xl font-semibold text-primary mb-2">
                            Priced from: <?php echo esc_html($maryland['price']); ?>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo esc_html($maryland['name']); ?></h3>
                        
                        <div class="text-sm text-gray-600 mb-4">
                            <?php echo esc_html($maryland['excerpt']); ?>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-primary-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>

                            <?php echo esc_html($maryland['address']); ?>
                        </div>
                        
                        <a class="inline-block w-full px-4 py-3 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center mt-4" href="https://yourintegrityhome.com/maryland/the-monument/" target="_blank" rel="noopener">
                            View Community
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Remove the old enqueue functions and their actions if they exist
if (function_exists('integrity_properties_editor_assets')) {
    remove_action('enqueue_block_editor_assets', 'integrity_properties_editor_assets');
}
if (function_exists('integrity_properties_script_data')) {
    remove_action('enqueue_block_editor_assets', 'integrity_properties_script_data');
}