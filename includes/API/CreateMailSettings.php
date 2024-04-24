<?php

namespace Product\Announcer\API;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class CreateMailSettings extends WP_REST_Controller
{
    function __construct()
    {
        $this->namespace = 'createSettings/v1';
        $this->rest_base = 'mail-settings';
    }

    public function register_routes()
    {
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

    public function is_mail_send_check( $request )
    {
        $nonce = $request->get_header('X-WP-Nonce');
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_Error('invalid_nonce', 'Invalid nonce.', array('status' => 403));
        }
        $form_data = $request->get_json_params();
        unset($form_data['nonce']);

        $options_name = 'PA_mailSendChecked';
        $is_done = update_option( $options_name, $form_data['PA_mailSendChecked'] );
        // Return success response
        if( $form_data['PA_mailSendChecked'] ) {
            $message = 'You Give The Permission To Send Mail To The Users.';
        }else{
            $message = 'You Do Not Want To Send Mail.';
        }

        return $message;
    }
    public function create_send_mail_settings( $request ){

        $nonce = $request->get_header('X-WP-Nonce');
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_Error( 'invalid_nonce', 'Invalid nonce.', array( 'status' => 403 ) );
        }
        // Get the form data from the request body
        $form_data = $request->get_json_params();
        unset($form_data['nonce']);
        $options_name = 'PA_send_mail_settings';
        $is_done = update_option( $options_name, $form_data );

        // Return success response
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