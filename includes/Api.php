<?php
namespace Product\Announcer;

use Product\Announcer\API\CreateMailSettings;
class Api
{
    function __construct(){
        add_action( 'rest_api_init', [$this, 'register_api']);
    }

    public function register_api(){
        $tasktodo = new CreateMailSettings();
        $tasktodo->register_routes();
    }

}