<?php
//echo 'Hello World';

$cache_key = 'PA_product_announce_mail_Setting';
$form_data = wp_cache_get( $cache_key, 'PA_send_mail_settings' );
if( empty( $form_data ) && !is_array( $form_data ) ){
    $form_data = get_option('PA_send_mail_settings');
}

//error_log( print_r( ['$form_data' => $form_data], true ) );
if( !empty( $form_data ) && is_array( $form_data ) && count( $form_data ) > 0 ){
    $email = $form_data['email'] ?? " ";
    $fromname = $form_data['fromname'] ?? "";
    $appkey = $form_data['appkey'] ?? "";
    $subject = $form_data['subject'] ?? "";
    $body_message = $form_data['body_message'] ?? "";
}else{
    $email = '';
    $appkey = '';
    $subject = '';
    $body_message = '';
    $fromname = '';
}

$mail_send_checked = get_option( 'PA_mailSendChecked' );
if( $mail_send_checked ){
    $text = 'Your Mail Send Permission Is On';
}else{
    $text = 'If you want to send mail to the user, please click the checkbox to grant permission.';
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

    /*Check box style*/
    .mailSendNoOff{
        /*background-color: #7b805d;*/
        padding: 10px;
        border: 1px solid #e1d6d6;
        border-radius: 5px;
        margin: 5px 5px 15px 5px;
    }
    .checkbox-wrapper-8 .tgl {
        display: none;
    }
    .checkbox-wrapper-8 .tgl,
    .checkbox-wrapper-8 .tgl:after,
    .checkbox-wrapper-8 .tgl:before,
    .checkbox-wrapper-8 .tgl *,
    .checkbox-wrapper-8 .tgl *:after,
    .checkbox-wrapper-8 .tgl *:before,
    .checkbox-wrapper-8 .tgl + .tgl-btn {
        box-sizing: border-box;
    }
    .checkbox-wrapper-8 .tgl::-moz-selection,
    .checkbox-wrapper-8 .tgl:after::-moz-selection,
    .checkbox-wrapper-8 .tgl:before::-moz-selection,
    .checkbox-wrapper-8 .tgl *::-moz-selection,
    .checkbox-wrapper-8 .tgl *:after::-moz-selection,
    .checkbox-wrapper-8 .tgl *:before::-moz-selection,
    .checkbox-wrapper-8 .tgl + .tgl-btn::-moz-selection,
    .checkbox-wrapper-8 .tgl::selection,
    .checkbox-wrapper-8 .tgl:after::selection,
    .checkbox-wrapper-8 .tgl:before::selection,
    .checkbox-wrapper-8 .tgl *::selection,
    .checkbox-wrapper-8 .tgl *:after::selection,
    .checkbox-wrapper-8 .tgl *:before::selection,
    .checkbox-wrapper-8 .tgl + .tgl-btn::selection {
        background: none;
    }
    .checkbox-wrapper-8 .tgl + .tgl-btn {
        outline: 0;
        display: block;
        width: 4em;
        height: 2em;
        position: relative;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .checkbox-wrapper-8 .tgl + .tgl-btn:after,
    .checkbox-wrapper-8 .tgl + .tgl-btn:before {
        position: relative;
        display: block;
        content: "";
        width: 50%;
        height: 100%;
    }
    .checkbox-wrapper-8 .tgl + .tgl-btn:after {
        left: 0;
    }
    .checkbox-wrapper-8 .tgl + .tgl-btn:before {
        display: none;
    }
    .checkbox-wrapper-8 .tgl:checked + .tgl-btn:after {
        left: 50%;
    }

    .checkbox-wrapper-8 .tgl-skewed + .tgl-btn {
        overflow: hidden;
        transform: skew(-10deg);
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        transition: all 0.2s ease;
        font-family: sans-serif;
        background: #888;
    }
    .checkbox-wrapper-8 .tgl-skewed + .tgl-btn:after,
    .checkbox-wrapper-8 .tgl-skewed + .tgl-btn:before {
        transform: skew(10deg);
        display: inline-block;
        transition: all 0.2s ease;
        width: 100%;
        text-align: center;
        position: absolute;
        line-height: 2em;
        font-weight: bold;
        color: #fff;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
    }
    .checkbox-wrapper-8 .tgl-skewed + .tgl-btn:after {
        left: 100%;
        content: attr(data-tg-on);
    }
    .checkbox-wrapper-8 .tgl-skewed + .tgl-btn:before {
        left: 0;
        content: attr(data-tg-off);
    }
    .checkbox-wrapper-8 .tgl-skewed + .tgl-btn:active {
        background: #888;
    }
    .checkbox-wrapper-8 .tgl-skewed + .tgl-btn:active:before {
        left: -10%;
    }
    .checkbox-wrapper-8 .tgl-skewed:checked + .tgl-btn {
        background: #86d993;
    }
    .checkbox-wrapper-8 .tgl-skewed:checked + .tgl-btn:before {
        left: -100%;
    }
    .checkbox-wrapper-8 .tgl-skewed:checked + .tgl-btn:after {
        left: 0;
    }
    .checkbox-wrapper-8 .tgl-skewed:checked + .tgl-btn:active:after {
        left: 10%;
    }
    .checkbox-wrapper-8{
        margin: auto;
        height: 45px;
        width: 80px;
        padding-top: 3px;
        font-size: 20px;
    }
    .mailPermissionText{
        padding: 5px;
        font-size: 15px;
    }
    .mailSendNoOff{
        text-align: center;
    }
</style>

<div class="container">
    <div class="mailSendNoOff">
        <span class="mailPermissionText" id="PA_mailPermissionText"><?php esc_attr_e( $text, 'product-announcer' ); ?></span>
        <div class="checkbox-wrapper-8">
            <input type="checkbox" name="PA_mailSendChecked" class="tgl tgl-skewed PA_mailSendChecked" id="PA_mailSendChecked" <?php echo esc_attr( $mail_send_checked ) ? 'checked' : ''; ?>/>
            <label class="tgl-btn" data-tg-off="<?php esc_attr_e( 'OFF', 'product-announcer' ); ?>" data-tg-on="<?php esc_attr_e( 'ON', 'product-announcer' ); ?>" for="PA_mailSendChecked"></label>
        </div>
    </div>

    <h1>Email Settings</h1>
    <form method="post" action="" id="emailSettingsForm">
        <label for="email">From Email:</label>
        <input type="text" id="email" name="email" value="<?php echo esc_attr($email); ?>"><br>

        <label for="email">From Name:</label>
        <input type="text" id="fromname" name="fromname" value="<?php echo esc_attr($fromname); ?>"><br>

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

        function set_settings_data( formData, type, path ){
            jQuery.ajax({
                type: type,
                url: path,
                contentType: 'application/json',
                headers: {
                    'X-WP-Nonce': formData.nonce
                },
                data: JSON.stringify(formData),
                success: function(response) {
                    alert(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        jQuery("#PA_mailSendChecked").click(function(){
            let value = 0 ;
            let is_checked = jQuery('input[name="PA_mailSendChecked"]:checked').serialize();
            if( is_checked === 'PA_mailSendChecked=on' ){
                 value = 1;
            }
            let isMailSendData = {}
            isMailSendData.nonce = '<?php echo wp_create_nonce( 'wp_rest' ); ?>';
            isMailSendData.PA_mailSendChecked = value;

            let path ='<?php echo esc_url_raw( rest_url( 'createSettings/v1/is_mail_send' ) ); ?>';
            let type = 'POST';
            set_settings_data( isMailSendData, type , path );
            console.log( isMailSendData );
        });

        jQuery('#emailSettingsForm').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Gather input field values into an object fromname
            var formData = {
                'email': jQuery('#email').val(),
                'fromname': jQuery('#fromname').val(),
                'appkey': jQuery('#appkey').val(),
                'subject': jQuery('#subject').val(),
                'body_message': jQuery('#body_message').val()
            };

            // Add nonce to form data
            formData.nonce = '<?php echo wp_create_nonce( 'wp_rest' ); ?>';

            //url: '<?php //echo esc_url_raw( rest_url( path ) ); ?>//'
            let path ='<?php echo esc_url_raw( rest_url( 'createSettings/v1/create_mail_setting' ) ); ?>';
            let type = 'POST';
            set_settings_data( formData, type , path );
            // Send the data to the custom REST API endpoint

        });
    });


</script>



