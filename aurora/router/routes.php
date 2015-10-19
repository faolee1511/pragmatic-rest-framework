<?php
/**
 * Routes File
 *
 * Contains all the router handling for Slim
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 4/10/14
 * Time: 5:25 PM
 */

//Make URI lowercase to avoid case sensitivity
$app->hook('slim.before.router', function () use ($app) {
    $app->environment['PATH_INFO'] = rtrim(strtolower($app->environment['PATH_INFO']), '/');
});

// Include router files
require "error_routes.php";
require "route_settings.php";
require "route_composer.php";