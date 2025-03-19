<?php

/**
 * Clase CAC_Columns_Manager
 * 
 * Manage all options about columns
 * 
 * @package CustomAcfColumns
 * @since 1.0.0
 */

namespace Mager19\CustomAcfColumns\admin;

if (!defined('ABSPATH')) {
    exit;
}

class CAC_Columns_Manager
{
    /**
     * The selected custom post type
     *
     * @var string
     */
    private $post_type;

    /**
     * The selected ACF field
     *
     * @var string
     */
    private $acf_field;

    public function __construct($post_type, $acf_field)
    {
        $this->post_type = $post_type;
        $this->acf_field = $acf_field;

        add_filter("manage_{$this->post_type}_posts_columns", array($this, 'add_custom_column'));
        add_action("manage_{$this->post_type}_posts_custom_column", array($this, 'display_custom_column'), 10, 2);

        // sorteable column
        add_filter("manage_edit-{$this->post_type}_sortable_columns", array($this, 'make_custom_column_sortable'));
    }

    /**
     * Add custom admin to cpt
     *
     * @param array $columns Array with current columns
     * @return array Columnas modified
     */
    public function add_custom_column($columns)
    {
        // Obtener el label del campo ACF para usarlo como título de la columna
        $field = get_field_object($this->acf_field);
        $column_title = $field ? $field['label'] : $this->acf_field;

        $columns['acf_custom_column'] = ucfirst($column_title);

        $new_columns = array();

        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;

            // insert before title column
            if ($key === 'title') {

                $field = get_field_object($this->acf_field);
                $column_title = $field ? $field['label'] : $this->acf_field;

                $new_columns['acf_custom_column'] = ucfirst($column_title);
            }
        }

        return $new_columns;
    }

    /** 
     * Show info of acf field
     * 
     * @param string post_id
     * @param string column name
     */

    public function display_custom_column($column_name, $post_id)
    {

        if ($column_name === 'acf_custom_column') {
            $value = get_field($this->acf_field, $post_id);
            if ($value) {
                echo esc_html($value);
            } else {
                echo '—'; // When the value es empty
            }
        }
    }

    /**
     * make column sorteable
     *
     * @param array $columns
     * @return array
     */
    public function make_custom_column_sortable($columns)
    {
        $columns['acf_custom_column'] = 'acf_custom_column';
        return $columns;
    }

    /**
     * Manage order column
     *
     * @param WP_Query $query
     */
    public function custom_column_ordering($query)
    {
        if (!is_admin() || $query->get('post_type') !== $this->post_type) {
            return;
        }

        // if acf field is num
        $field = get_field_object($this->acf_field);
        if ($field && in_array($field['type'], ['number', 'range'])) {
            // Order by num and them title
            $query->set('orderby', array(
                'meta_value_num' => $query->get('order'),
                'title' => 'ASC'
            ));
        } else {
            // Order by value and them title
            $query->set('orderby', array(
                'meta_value' => $query->get('order'),
                'title' => 'ASC'
            ));
        }
    }
}
