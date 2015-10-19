<?php
/**
 * Index File
 *
 * This file in the main entry point of our API
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 3/15/14
 * Time: 1:13 PM
 */

// Set timezone to UTC
date_default_timezone_set("UTC");

require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

require '/aurora/configuration.php';

require '/aurora/connect.php';

require '/aurora/utility.php';

require '/aurora/responder.php';

require '/aurora/validator.php';

// Create Slim application instance
$app = new Slim\Slim(
    array(
        'debug' => false,
    )
);

require '/aurora/security.php';

require '/aurora/anonymous_request.php';

\aurora\model\CurrentRequest::load(\aurora\model\CurrentRequest::getAccessToken());

require '/aurora/router/routes.php';

// Start Slim application
$app->run();
