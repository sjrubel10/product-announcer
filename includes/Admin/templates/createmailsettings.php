<?php

$cache_key = 'PA_product_announce_mail_Setting';
$form_data = wp_cache_get( $cache_key, 'PA_send_mail_settings' );

if( empty( $form_data ) && !is_array( $form_data ) ){
    $form_data = maybe_unserialize( get_option('PA_send_mail_settings') );
}
if( !empty( $form_data ) && is_array( $form_data ) && count( $form_data ) > 0 ){
    $email = $form_data['email'] ?? " ";
    $email_host = $form_data['email_host'] ?? " ";
    $fromname = $form_data['fromname'] ?? "";
    $appkey = $form_data['appkey'] ?? "";
    $subject = $form_data['subject'] ?? "";
    $body_message = $form_data['body_message'] ?? "";
}else{
    $email = '';
    $email_host = '';
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
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<div class="PA_ProductAnnouncer_container">
    <div class="mailSendNoOff">
        <span class="mailPermissionText" id="PA_mailPermissionText"><?php esc_attr( $text ); ?></span>
        <div class="checkbox-wrapper-8">
            <input type="checkbox" name="PA_mailSendChecked" class="tgl tgl-skewed PA_mailSendChecked" id="PA_mailSendChecked" <?php echo esc_attr( $mail_send_checked ) ? 'checked' : ''; ?>/>
            <label class="tgl-btn" data-tg-off="<?php esc_attr_e( 'OFF', 'product-announcer' ); ?>" data-tg-on="<?php esc_attr_e( 'ON', 'product-announcer' ); ?>" for="PA_mailSendChecked"></label>
        </div>
    </div>

    <h1>Email Settings</h1>
    <form method="post" action="" id="emailSettingsForm">
        <label for="email">From Email:</label>
        <input type="text" id="email" name="email" value="<?php echo esc_attr( $email ); ?>"><br>

        <label for="email_host">Email Host Name:</label>
        <input type="text" id="email_host" name="email_host" value="<?php echo esc_attr( $email_host ); ?>"><br>

        <label for="email">From Name:</label>
        <input type="text" id="fromname" name="fromname" value="<?php echo esc_attr( $fromname ); ?>"><br>

        <label for="appkey">App Key:</label>
        <input type="text" id="appkey" name="appkey" value="<?php echo esc_attr( $appkey ); ?>"><br>

        <h2>Email Content</h2>
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" value="<?php echo esc_attr( $subject ); ?>"><br>

        <label for="body_message">Body Message:</label><br>
        <textarea id="body_message" name="body_message"><?php echo esc_textarea( $body_message ); ?></textarea><br><br>

        <input type="submit" name="submit" value="Save Change">

    </form>

    <input type="text" id="PA_createNonce" name="PA_createNonce" value="<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>">

</div>


