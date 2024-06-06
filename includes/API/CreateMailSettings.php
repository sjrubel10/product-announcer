<?php

namespace Product\Announcer\API;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class CreateMailSettings extends WP_REST_Controller{
    function __construct(){
        $this->namespace = 'createSettings/v1';
        $this->rest_base = 'mail-settings';
    }

    public function register_routes(){
        register_rest_route(
            $this->namespace,
            '/create_mail_setting',
            [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'create_send_mail_settings'],
                    'permission_callback' => [$this, 'get_item_permissions_check'],
                    'args' => [$this->get_collection_params()],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/is_mail_send',
            [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'is_mail_send_check'],
                    'permission_callback' => [$this, 'get_item_permissions_check'],
                    'args' => [$this->get_collection_params()],
                ],
            ]
        );
    }

    public function is_mail_send_check( $request ){
        $nonce = $request->get_header('X-WP-Nonce');
        if (!wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_Error('invalid_nonce', 'Invalid nonce.', array('status' => 403));
        }
        $form_data = $request->get_json_params();
        unset($form_data['nonce']);
        $options_name = 'PA_mailSendChecked';
        $is_done = update_option( $options_name, sanitize_text_field( $form_data['PA_mailSendChecked'] ) );
        // Return success response
        if( $is_done ) {
            $message = 'You Give The Permission To Send Mail To The Users.';
        }else{
            $message = 'You Do Not Want To Send Mail.';
        }

        return $message;
    }
    public function create_send_mail_settings( $request ){
        $nonce = sanitize_text_field( $request->get_header('X-WP-Nonce') );
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_Error( 'invalid_nonce', 'Invalid nonce.', array( 'status' => 403 ) );
        }
        $form_data = $request->get_json_params();
        unset( $form_data['nonce'] );

        $sender_email = sanitize_email( $form_data['email'] );
        $email_host = sanitize_text_field( $form_data['email_host'] );
        $sender_name = sanitize_text_field( $form_data['fromname'] );
        $app_key = sanitize_text_field( $form_data['appkey'] );
        $email_subject = sanitize_text_field( $form_data['subject'] );
        $email_body = sanitize_text_field( $form_data['body_message'] );
        $settings_data = array(
                            'email' => $sender_email,
                            'email_host' => $email_host,
                            'fromname' => $sender_name,
                            'appkey' => $app_key,
                            'subject' => $email_subject,
                            'body_message' => $email_body,
                        );
        $options_name = 'PA_send_mail_settings';
        $is_done = update_option( $options_name, maybe_serialize( $settings_data ) );
        if( $is_done ){
            $cache_key = 'PA_product_announce_mail_Setting';
            wp_cache_set( $cache_key, $form_data, 'PA_send_mail_settings' );
            $message = 'Settings saved successfully.';
        }else{
            $message = 'Something Went Wrong!';
        }

        return rest_ensure_response($message );
    }

    public function get_item_permissions_check( $request ){
        if( current_user_can( 'manage_options' ) ){
            return true;
        }

        return false;
    }
}