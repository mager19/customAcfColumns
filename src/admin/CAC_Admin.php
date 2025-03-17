<?php

/**
 * Class CAC_Admin
 * 
 * @since 1.0.0
 */

namespace Mager19\CustomAcfColumns\admin;

use Mager19\CustomAcfColumns\utils\CAC_GetCustomPostTypes;
use Mager19\CustomAcfColumns\utils\CAC_FieldDetector;
use Mager19\CustomAcfColumns\utils\CAC_Notice;

class CAC_Admin
{
    /**
     * Construct function
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'CAC_Admin_Page'));
        add_action('wp_ajax_get_fields', array($this, 'get_fields'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook)
    {
        // only load scripts on the custom acf columns page
        if ($hook != 'tools_page_custom-acf-columns') {
            return;
        }

        wp_enqueue_style(
            'global-css',
            plugin_dir_url(__FILE__) . '../assets/css/global.css',
            array(),
            '1.0.0',
            'all'
        );

        // Register the script
        wp_register_script(
            'admin-js',
            plugin_dir_url(__FILE__) . '../assets/js/admin.js',
            array(),
            '1.0.0',
            true
        );

        // add localized data to the script
        wp_localize_script(
            'admin-js',
            'cacLocalizedData',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'savedAcfField' => get_option('selected_acf'),
                'fieldNonce' => wp_create_nonce('get_fields_nonce'),
                'noFieldsFound' => __('No ACF Fields Found', 'custom-acf-columns'),
                'errorLoadingFields' => __('Error loading fields', 'custom-acf-columns'),
            )
        );

        // Enqueue the script
        wp_enqueue_script('admin-js');
    }

    /**
     * Add the Custom ACF Columns page
     */
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
        //Get all custom post types
        $cpts = CAC_GetCustomPostTypes::get_post_types();
        $this->processData();

?>
        <div class="wrap top-small">
            <h1><?php _e('Custom ACF Columns', 'custom-acf-columns'); ?></h1>
            <div class="content">
                <p><?php _e('Select a Custom Post Type and an ACF field to display in the columns.', 'custom-acf-columns'); ?></p>

                <?php
                echo get_option('selected_cpt');
                echo '-';
                echo get_option('selected_acf');
                ?>
            </div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                // saved acf field
                var savedAcfField = '<?php echo esc_js(get_option('selected_acf')); ?>';

                function loadFields(cpt) {
                    if (cpt) {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'get_fields',
                                cpt: cpt,
                                nonce: '<?php echo wp_create_nonce('get_fields_nonce'); ?>'
                            },
                            success: function(response) {
                                if (response.success) {
                                    var fields = response.data;
                                    var field_select = $('#field_select');
                                    field_select.empty();
                                    field_select.append('<option value="">Select a Field</option>');

                                    if (typeof fields === 'object' && fields !== null && fields.acf) {
                                        fields.acf.forEach(function(field) {
                                            // add selected attribute if the field is the saved one
                                            var selected = (field.id == savedAcfField) ? ' selected="selected"' : '';
                                            field_select.append('<option value="' + field.id + '"' + selected + '>' + field.label + '</option>');
                                        });
                                        $('#field_row').show();
                                        $('#message').hide();
                                    } else {

                                        $('#message').show().html('<p>No ACf Fields Found</p>');
                                        $('#field_row').hide();
                                    }
                                } else {
                                    $('#message').show().html('<p>' + response.data + '</p>');
                                    $('#field_row').hide();
                                }
                            }
                        });
                    } else {
                        $('#field_row').hide();
                    }
                }

                // Cuando cambia el CPT, carga los campos
                $('#cpt_select').change(function() {
                    loadFields($(this).val());
                });

                // Carga inicial de campos si hay un CPT seleccionado
                var initialCpt = $('#cpt_select').val();
                if (initialCpt) {
                    loadFields(initialCpt);
                }
            });
        </script>

<?php
        // Form
        if ($cpts) {
            require_once CAC_PLUGIN_DIR . 'src/components/CAC_Admin_Form.php';
        } else {
            echo _e('No Custom Post types Found', 'custom-acf-columns');
        }
    }


    public function get_fields()
    {
        check_ajax_referer('get_fields_nonce', 'nonce');

        if (!isset($_POST['cpt'])) {
            wp_send_json_error(' No Custom Post Type found');
        }

        $cpt = sanitize_text_field($_POST['cpt']);
        $fieldDetector = new CAC_FieldDetector($cpt);
        $fields = $fieldDetector->get_all_fields();


        if (empty($fields)) {
            $message = 'No fields found';
            CAC_Notice::display_notice($message, 'error');
            wp_send_json_error('No fields found');
        }

        wp_send_json_success($fields);
    }

    /**
     * Process form data
     * Private function
     */
    private function processData()
    {

        $type = 'success';
        // Process form data
        if (isset($_POST['submit']) && check_admin_referer('cac_save_options', 'cac_nonce')) {
            if (isset($_POST['cpt_select'])) {
                $selected_cpt = sanitize_text_field($_POST['cpt_select']);
                update_option('selected_cpt', $selected_cpt);
            }

            if (isset($_POST['field_select']) && !empty($_POST['field_select'])) {
                $selected_acf = sanitize_text_field($_POST['field_select']);
                update_option('selected_acf', $selected_acf);
                $message = '<p>Campo ACF seleccionado: ' . esc_html($selected_acf) . '</p>';
                // CAC_Notice::display_notice($message, $type);
            }
        }

        // Reset options
        if (isset($_POST['reset_options']) && check_admin_referer('cac_save_options', 'cac_nonce')) {
            delete_option('selected_cpt');
            delete_option('selected_acf');
            echo '<div class="notice notice-success"><p>Las opciones han sido reiniciadas correctamente.</p></div>';
        }
    }
}
