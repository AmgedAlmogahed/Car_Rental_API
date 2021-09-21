<?php 

    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'car_rental');

    define('USER_CREATED', 101);
    define('USER_EXISTS', 102);
    define('USER_FAILURE', 103); 
    
    define('USER_AUTHENTICATED', 201);
    define('USER_NOT_FOUND', 202); 
    define('USER_PASSWORD_DO_NOT_MATCH', 203);

    define('PASSWORD_CHANGED', 301);
    define('PASSWORD_DO_NOT_MATCH', 302);
    define('PASSWORD_NOT_CHANGED', 303);

    define('CAR_CREATED', 401);
    define('CAR_EXISTS', 402);
    define('CAR_FAILURE', 403); 

    define('CUSTOMER_CREATED', 501);
    define('CUSTOMER_EXISTS', 502);
    define('CUSTOMER_FAILURE', 503); 

    // define('SERVICE_ADDED', 501);
    // define('CUSTOMER_EXISTS', 502);
    // define('CUSTOMER_FAILURE', 503); 