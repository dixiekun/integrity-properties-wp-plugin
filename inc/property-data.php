<?php
if (!defined('ABSPATH')) {
    exit;
}

class Integrity_Property_Data {
    public static function get_properties() {
        return array(
            'virginia' => array(
                'name' => 'The Enclave',
                'featured_image' => 'https://yourintegrityhome.com/wp-content/uploads/2024/03/Hero-The-Enclave-1.jpg',
                'community_label' => 'In The Heart of Fairfax, VA!',
                'price' => '$859,990',
                'address' => '4412 Fair Lakes Ct Fairfax, VA 22033',
                'excerpt' => 'The Enclave at Fair Lakes seamlessly blends contemporary living and luxury to create a premier townhouse community that is exactly what you\'ve been looking for in Fair Lakes...',
                'badge' => 'https://yourintegrityhome.com/wp-content/uploads/2023/10/Gala-2023-Badge.png',
                'state' => 'virginia'
            ),
            'maryland' => array(
                'name' => 'Potomac Overlook',
                'featured_image' => 'https://yourintegrityhome.com/wp-content/uploads/2024/03/Cedar-Hill-3-Hero-1.jpg',
                'community_label' => '2023 GALA Award Winner',
                'price' => '$754,990',
                'address' => '500 Triggerfish Drive National Harbor, MD 20745',
                'excerpt' => 'Exclusive gated community in a premier location at the heart of the National Harbor Waterfront District – only 3 blocks to shopping, dining, and the Potomac River.',
                'badge' => 'https://yourintegrityhome.com/wp-content/uploads/2023/10/Gala-2023-Badge.png',
                'state' => 'maryland'
            )
        );
    }

    public static function get_property($type) {
        $properties = self::get_properties();
        return isset($properties[$type]) ? $properties[$type] : null;
    }
    
    public static function get_properties_by_state($state = null) {
        $properties = self::get_properties();
        
        if ($state === null || $state === 'both') {
            return $properties;
        }
        
        $filtered = array();
        foreach ($properties as $key => $property) {
            if ($property['state'] === $state) {
                $filtered[$key] = $property;
            }
        }
        
        return $filtered;
    }
}