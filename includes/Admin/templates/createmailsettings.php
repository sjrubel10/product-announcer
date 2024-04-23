<?php
//echo 'Hello World';
$form_data = get_option('PA_send_mail_settings');
//error_log( print_r( ['$form_data' => $form_data], true ) );
if( !empty( $form_data ) && is_array( $form_data ) && count( $form_data ) > 0 ){
    $email = $form_data['email'];
    $appkey = $form_data['appkey'];
    $subject = $form_data['subject'];
    $body_message = $form_data['body_message'];
}else{
    $email = '';
    $appkey = '';
    $subject = '';
    $body_message = '';
}
// Access individual values from the array



?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    h1, h2 {
        margin-top: 0;
    }
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    textarea {
        height: 150px;
    }
    input[type="submit"] {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>
<div class="container">
    <h1>Email Settings</h1>
    <form method="post" action="" id="emailSettingsForm">
        <label for="email">From Email:</label>
        <input type="text" id="email" name="email" value="<?php echo esc_attr($email); ?>"><br>

        <label for="appkey">App Key:</label>
        <input type="text" id="appkey" name="appkey" value="<?php echo esc_attr($appkey); ?>"><br>

        <h2>Email Content</h2>
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" value="<?php echo esc_attr($subject); ?>"><br>

        <label for="body_message">Body Message:</label><br>
        <textarea id="body_message" name="body_message"><?php echo esc_textarea($body_message); ?></textarea><br><br>

        <input type="submit" name="submit" value="Save Change">
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    jQuery(document).ready(function() {
        jQuery('#emailSettingsForm').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Gather input field values into an object
            var formData = {
                'email': jQuery('#email').val(),
                'appkey': jQuery('#appkey').val(),
                'subject': jQuery('#subject').val(),
                'body_message': jQuery('#body_message').val()
            };

            // Add nonce to form data
            formData.nonce = '<?php echo wp_create_nonce( 'wp_rest' ); ?>';

            // Send the data to the custom REST API endpoint
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo esc_url_raw( rest_url( 'createSettings/v1/create_mail_setting' ) ); ?>',
                contentType: 'application/json',
                headers: {
                    'X-WP-Nonce': formData.nonce
                },
                data: JSON.stringify(formData),
                success: function(response) {
                    // Handle success response
                    alert(response);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
    });


</script>



