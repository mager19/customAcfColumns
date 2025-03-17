<?php

namespace Mager19\CustomAcfColumns\utils;


class CAC_GetCustomPostTypes
{
    /**
     * Get custom post types
     *
     * @return array List of custom post types with their labels
     */
    public static function get_post_types()
    {
        // _builtin => false to get only custom post types
        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $post_types = get_post_types($args, 'objects');

        $list = array();

        // Check if post types are found
        if (!empty($post_types)) {
            // Loop through each post type and add to the list
            foreach ($post_types as $post_type) {
                $list[$post_type->name] = $post_type->label;
            }
        }
        return $list;
    }
}
