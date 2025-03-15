<?php

/**
 * Plugin Name: Custom ACF Columns 
 * Plugin URI: https://mager19.live
 * Description: Administrador de columnas personalizadas para Custom Post Types y sus campos personalizados.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.4
 * Author: mager19
 * Author URI: https://mager19.live
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-columns-manager
 * Domain Path: /languages
 *
 * @package CustomColumnsManager
 */

// Evitar acceso directo al archivo
if (!defined('ABSPATH')) {
    exit;
}
require_once __DIR__ . '/vendor/autoload.php';

use Mager19\CustomAcfColumns\admin\CAC_Admin;


if (!class_exists('CustomACFColumns')) {
    class CustomACFColumns
    {

        public function __construct()
        {
            $this->define_constants();
        }


        // Define constants_a
        public function define_constants()
        {
            define('CAC_VERSION', '1.0.1');
            define('CAC_PLUGIN_DIR', plugin_dir_path(__FILE__));
            define('CAC_PLUGIN_URL', plugin_dir_url(__FILE__));
        }

        public function activate() {}

        public function deactivate() {}

        public function uninstall() {}
    }
}

if (class_exists('CustomACFColumns')) {
    $customACFColumns = new CustomACFColumns();
    register_activation_hook(__FILE__, [$customACFColumns, 'activate']);
    register_deactivation_hook(__FILE__, [$customACFColumns, 'deactivate']);
    register_uninstall_hook(__FILE__, [$customACFColumns, 'uninstall']);

    if (is_admin()) {
        new CAC_Admin();
    }
}
