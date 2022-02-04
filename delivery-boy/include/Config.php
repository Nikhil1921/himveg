<?php
/**
 * Database configuration
 */
switch ($_SERVER['HTTP_HOST']) {
    case 'himveg.in':
        define('DB_USERNAME', 'himveh8x_himveg');
        define('DB_PASSWORD', 'Densetek@2018');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'himveh8x_himveg');
        break;
    
    default:
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'himveg');
        break;
}

/**
 * MSG91 configuration
 */
define('MSG91_AUTH_KEY', "116948AXMNupd75768d17b");
// sender id should 6 character long
define('MSG91_SENDER_ID', 'EZYRDE');

define('USER_CREATED_SUCCESSFULLY', 0);
define('USER_CREATE_FAILED', 1);
define('USER_ALREADY_EXISTED', 2);
?>