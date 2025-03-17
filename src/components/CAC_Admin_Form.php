<form method="post" action="">
    <?php wp_nonce_field('cac_save_options', 'cac_nonce'); ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="cpt_select">
                    <?php _e('Select Custom Post Type:', 'custom-acf-columns'); ?>
                </label>
            </th>
            <td>
                <select name="cpt_select" id="cpt_select">
                    <option value="">
                        <?php _e('Select a CPT', 'custom-acf-columns'); ?>
                    </option>
                    <?php
                    $saved_cpt = get_option('selected_cpt');
                    foreach ($cpts as $cpt_name => $cpt_label): ?>
                        <option value="<?php echo esc_attr($cpt_name); ?>" <?php selected($saved_cpt, $cpt_name); ?>>
                            <?php echo esc_html($cpt_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr id="field_row" style="display: none;">
            <th scope="row">
                <label for="field_select">Select Field:</label>
            </th>
            <td>
                <select name="field_select" id="field_select">
                    <option value="">Seleccione un Field</option>
                </select>
            </td>
        </tr>
    </table>
    <?php //submit_button('Guardar'); 
    ?>
    <div class="submit-buttons top-small">
        <?php submit_button('Guardar', 'primary', 'submit', false); ?>
        <?php submit_button('Resetear', 'secondary', 'reset_options', false); ?>
    </div>
</form>