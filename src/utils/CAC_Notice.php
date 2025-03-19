<?php

/**
 * Class CAC_Notice
 * 
 * @since 1.0.0
 * @params string $message
 * @params string $type
 *  notice-error, notice-warning, notice-success, notice-info
 */

namespace Mager19\CustomAcfColumns\utils;

class CAC_Notice
{
    /**
     * Construct function
     */
    public function __construct()
    {
        add_action('admin_notices', array($this, 'display_notice'));
    }

    /**
     * Display notice
     */
    public static function display_notice($message, $type = 'error')
    {
        $screen = get_current_screen();

        if (!$screen) {
            return;
        }

        if ($screen->id && $screen->id !== 'tools_page_custom-acf-columns') {
            return;
        }

        echo '<div class="notice notice-' . $type . ' is-dismissible">
                <p>' . $message . '</p>
            </div>';
    }
}
