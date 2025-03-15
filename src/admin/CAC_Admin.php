<?php

namespace Mager19\CustomAcfColumns\admin;

use Mager19\CustomAcfColumns\utils\GetCustomPostTypes;

class CAC_Admin
{
    /**
     * Construct function
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'CAC_Admin_Page'));
    }

    public function CAC_Admin_Page()
    {
        add_submenu_page(
            'tools.php',
            __('Custom ACF Columns', 'custom-acf-columns'),
            __('Custom ACF Columns', 'custom-acf-columns'),
            'manage_options',
            'custom-acf-columns',
            [$this, 'CAC_Admin_Page_Content'],
            30
        );
    }

    public function CAC_Admin_Page_Content()
    {
        echo 'Hello World -';
        echo '<br>';

        $cpts = GetCustomPostTypes::get_post_types();

        echo '<pre>' . var_export($cpts, true) . '</pre>';
    }
}
