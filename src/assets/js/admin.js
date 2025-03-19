document.addEventListener('DOMContentLoaded', function () {
    // Saved ACF field
    var savedAcfField = cacLocalizedData.savedAcfField;

    function loadFields(cpt) {
        if (cpt) {
            // Create FormData object for the POST data
            const formData = new FormData();
            formData.append('action', 'get_fields');
            formData.append('cpt', cpt);
            formData.append('nonce', cacLocalizedData.fieldNonce);

            // Create and send the fetch request
            fetch(ajaxurl, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        const fields = response.data;
                        const fieldSelect = document.getElementById('field_select');
                        const fieldRow = document.getElementById('field_row');
                        const messageDiv = document.getElementById('message');

                        // Clear the select element
                        fieldSelect.innerHTML = '';

                        // Add the default option
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Select a Field';
                        fieldSelect.appendChild(defaultOption);

                        if (typeof fields === 'object' && fields !== null && fields.acf) {
                            fields.acf.forEach(function (field) {
                                // Create new option element
                                const option = document.createElement('option');
                                option.value = field.id;
                                option.textContent = field.label;

                                // Add selected attribute if the field is the saved one
                                if (field.id == savedAcfField) {
                                    option.selected = true;
                                }

                                // Add the option to the select element
                                fieldSelect.appendChild(option);
                            });

                            // Show field row and hide message
                            fieldRow.style.display = '';
                            messageDiv.style.display = 'none';
                        } else {
                            // Show message and hide field row
                            messageDiv.innerHTML = '<p>No ACF Fields Found</p>';
                            messageDiv.style.display = '';
                            fieldRow.style.display = 'none';
                        }
                    } else {
                        // Show error message
                        const messageDiv = document.getElementById('message');
                        messageDiv.innerHTML = '<p>' + response.data + '</p>';
                        messageDiv.style.display = '';
                        document.getElementById('field_row').style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('message').innerHTML = '';
                });
        } else {
            document.getElementById('field_row').style.display = 'none';
        }
    }

    // Event listener for CPT select change
    const cptSelect = document.getElementById('cpt_select');
    cptSelect.addEventListener('change', function () {
        loadFields(this.value);
    });

    // Initial load of fields if there's a CPT selected
    const initialCpt = cptSelect.value;
    if (initialCpt) {
        loadFields(initialCpt);
    }
});