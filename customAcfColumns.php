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

define('CCM_VERSION', '1.0.0');
define('CCM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CCM_PLUGIN_URL', plugin_dir_url(__FILE__));
