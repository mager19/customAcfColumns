<?php

/**
 * Class FieldDetector
 * 
 * @since 1.0.0
 */

namespace Mager19\CustomAcfColumns\utils;

if (!defined('ABSPATH')) {
    exit;
}


if (!class_exists('CAC_FieldDetector')) {
    class CAC_FieldDetector
    {

        private $post_type;

        private $fields_cache = [];

        public function __construct($post_type)
        {
            $this->post_type = $post_type;
        }

        public function get_field_cache()
        {
            return $this->fields_cache;
        }

        public function get_all_fields()
        {
            if (!empty($this->fields_cache)) {
                return $this->fields_cache;
            }

            $fields = array(
                'acf' => $this->get_acf_fields(),
            );

            $fields = array_filter($fields);

            $this->fields_cache = $fields;
            return $fields;
        }

        private function get_acf_fields()
        {
            if (!function_exists('acf_get_field_groups')) {
                return array();
            }

            $acf_fields = array();

            $field_groups = acf_get_field_groups(array(
                'post_type' => $this->post_type
            ));

            foreach ($field_groups as $field_group) {
                $fields = acf_get_fields($field_group);

                if (!empty($fields)) {
                    foreach ($fields as $field) {
                        $acf_fields[] = array(
                            'id' => $field['name'],
                            'label' => $field['label'],
                            'type' => $field['type'],
                            'source' => 'acf',
                        );
                    }
                }
            }

            return $acf_fields;
        }
    }
}
