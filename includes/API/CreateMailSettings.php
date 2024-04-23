<?php

namespace Product\Announcer\API;

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
    }

    public function create_send_mail_settings( $request ){
        error_log( print_r( ['$request'=>$request->JSON], true ) );
    }

    public function get_item_permissions_check( $request ){
        if( current_user_can( 'manage_options' ) ){
            return true;
        }
        return false;
    }
}