<?php

namespace Product\Announcer;

use Product\Announcer\Admin\Menu;
use Product\Announcer\Admin\SendMail;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class
     */
    function __construct() {
        new Menu();
        new SendMail();
    }
}
