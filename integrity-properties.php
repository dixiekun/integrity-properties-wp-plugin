<?php
/**
 * Plugin Name: Integrity Properties
 * Description: Custom Gutenberg blocks for property listings
 * Version: 1.0.0
 * Author: Paul Dilinger
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('INTEGRITY_PROPERTIES_PATH', plugin_dir_path(__FILE__));
define('INTEGRITY_PROPERTIES_URL', plugin_dir_url(__FILE__));

// Property data store
require_once INTEGRITY_PROPERTIES_PATH . 'inc/property-data.php';

// Block initialization
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

// Enqueue assets and localize script
function integrity_properties_assets() {
    // Dashicons for the location icon
    wp_enqueue_style('dashicons');
    
    // Only load editor assets in admin
    if (is_admin()) {
        // Register and localize the script
        wp_register_script(
            'integrity-properties-editor',
            INTEGRITY_PROPERTIES_URL . 'build/index.js',
            array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'),
            filemtime(INTEGRITY_PROPERTIES_PATH . 'build/index.js'),
            true
        );
        
        wp_localize_script(
            'integrity-properties-editor',
            'integrityProperties',
            Integrity_Property_Data::get_properties()
        );
        
        wp_enqueue_script('integrity-properties-editor');
        
        // Editor styles
        if (file_exists(INTEGRITY_PROPERTIES_PATH . 'build/index.css')) {
            wp_enqueue_style(
                'integrity-properties-editor-style',
                INTEGRITY_PROPERTIES_URL . 'build/index.css',
                array(),
                filemtime(INTEGRITY_PROPERTIES_PATH . 'build/index.css')
            );
        }
    }
    
    // Frontend styles (for both admin and frontend)
    if (file_exists(INTEGRITY_PROPERTIES_PATH . 'build/style-index.css')) {
        wp_enqueue_style(
            'integrity-properties-style',
            INTEGRITY_PROPERTIES_URL . 'build/style-index.css',
            array(),
            filemtime(INTEGRITY_PROPERTIES_PATH . 'build/style-index.css')
        );
    }
}
add_action('enqueue_block_assets', 'integrity_properties_assets');

// Render callback for property card
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
    <div class="property-card">
        <div class="property-card__image-container">
            <div class="property-card__featured-img-mask">
                <img src="<?php echo esc_url($property['featured_image']); ?>" class="property-card__featured-img" alt="<?php echo esc_attr($property['name']); ?>">
            </div>
            <div class="property-card__label"><?php echo esc_html($property['community_label']); ?></div>
            <?php if ($attributes['showBadge']): ?>
                <img class="property-card__badge" src="<?php echo esc_url($property['badge']); ?>" alt="Award Badge">
            <?php endif; ?>
        </div>
        
        <?php if ($attributes['showPrice']): ?>
            <div class="property-card__price">
                Priced from: <?php echo esc_html($property['price']); ?>
            </div>
        <?php endif; ?>

        <h3 class="property-card__title"><?php echo esc_html($property['name']); ?></h3>

        <?php if ($attributes['showExcerpt']): ?>
            <div class="property-card__excerpt">
                <?php echo esc_html($property['excerpt']); ?>
            </div>
        <?php endif; ?>

        <?php if ($attributes['showAddress']): ?>
            <div class="property-card__address">
                <span class="dashicons dashicons-location"></span>
                <?php echo esc_html($property['address']); ?>
            </div>
        <?php endif; ?>

        <a class="property-card__button" 
           href="<?php echo $attributes['propertyType'] === 'maryland' 
                ? 'https://yourintegrityhome.com/maryland/the-monument/' 
                : 'https://yourintegrityhome.com/virginia/enclave/'; ?>" 
           target="_blank" 
           >
            View Community
        </a>
    </div>
    <?php
    return ob_get_clean();
}

// Render callback for property section
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
        'class' => 'integrity-property-section'
    ));
    
    ob_start();
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <div class="integrity-property-section__header">
            <?php if (!empty($attributes['title'])): ?>
                <h2 class="integrity-property-section__title"><?php echo esc_html($attributes['title']); ?></h2>
            <?php endif; ?>
            <?php if (!empty($attributes['description'])): ?>
                <p class="integrity-property-section__description"><?php echo esc_html($attributes['description']); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="integrity-property-section__content">
            <!-- Virginia Property Card -->
            <div class="property-card">
                <div class="property-card__image-container">
                    <div class="property-card__featured-img-mask">
                        <img src="<?php echo esc_url($virginia['featured_image']); ?>" class="property-card__featured-img" alt="<?php echo esc_attr($virginia['name']); ?>">
                    </div>
                    <div class="property-card__label"><?php echo esc_html($virginia['community_label']); ?></div>
                    <img class="property-card__badge" src="<?php echo esc_url($virginia['badge']); ?>" alt="Award Badge">
                </div>
                
                <div class="property-card__price">
                    Priced from: <?php echo esc_html($virginia['price']); ?>
                </div>
                
                <h3 class="property-card__title"><?php echo esc_html($virginia['name']); ?></h3>
                
                <div class="property-card__excerpt">
                    <?php echo esc_html($virginia['excerpt']); ?>
                </div>
                
                <div class="property-card__address">
                    <span class="dashicons dashicons-location"></span>
                    <?php echo esc_html($virginia['address']); ?>
                </div>
                
                <a class="property-card__button" href="https://yourintegrityhome.com/virginia/enclave/" target="_blank">
                    View Community
                </a>
            </div>
            
            <!-- Maryland Property Card -->
            <div class="property-card">
                <div class="property-card__image-container">
                    <div class="property-card__featured-img-mask">
                        <img src="<?php echo esc_url($maryland['featured_image']); ?>" class="property-card__featured-img" alt="<?php echo esc_attr($maryland['name']); ?>">
                    </div>
                    <div class="property-card__label"><?php echo esc_html($maryland['community_label']); ?></div>
                    <img class="property-card__badge" src="<?php echo esc_url($maryland['badge']); ?>" alt="Award Badge">
                </div>
                
                <div class="property-card__price">
                    Priced from: <?php echo esc_html($maryland['price']); ?>
                </div>
                
                <h3 class="property-card__title"><?php echo esc_html($maryland['name']); ?></h3>
                
                <div class="property-card__excerpt">
                    <?php echo esc_html($maryland['excerpt']); ?>
                </div>
                
                <div class="property-card__address">
                    <span class="dashicons dashicons-location"></span>
                    <?php echo esc_html($maryland['address']); ?>
                </div>
                
                <a class="property-card__button" href="https://yourintegrityhome.com/maryland/the-monument/" target="_blank">
                    View Community
                </a>
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