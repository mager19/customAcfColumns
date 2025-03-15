<?php

namespace Mager19\CustomAcfColumns\utils;


class GetCustomPostTypes
{
    public static function get_post_types()
    {
        echo 'Hello from GetPostTypes';

        // _builtin => false to get only custom post types
        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $post_types = get_post_types($args, 'objects');

        $list = array();

        foreach ($post_types as $post_type) {
            $list[$post_type->name] = $post_type->label;
        }

        return $list;
    }
}
