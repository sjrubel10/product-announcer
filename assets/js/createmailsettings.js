jQuery(document).ready(function() {

    var getNonce = jQuery("#PA_createNonce").val();
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
        isMailSendData.nonce = getNonce;
        isMailSendData.PA_mailSendChecked = value;

        let path ='http://localhost:8888/ctx-test/wp-json/createSettings/v1/is_mail_send';
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

        formData.nonce = getNonce;
        alert( formData.nonce );
        let path ='http://localhost:8888/ctx-test/wp-json/createSettings/v1/create_mail_setting';
        let type = 'POST';
        set_settings_data( formData, type , path );
        // Send the data to the custom REST API endpoint

    });
});