<?php
/**
 * Error Routes
 *
 * This file manages all route error
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 4/10/14
 * Time: 5:06 PM
 */

// Error 404s
$app->notFound(function () use ($app)
{
    deliver_error_response(404, 40401, 'URI or METHOD is not supported.');
});

//Error 500s
$app->error(function (\Exception $e) use ($app)
{
    deliver_error_response(500, 50003, $e->getMessage());
});


// Handles cross branch data modification
if ($app->request()->getMethod() != 'GET'
    &&
    $GLOBALS['target_host_address'] != $GLOBALS['host_address']
) {
    deliver_error_response(403, 40301, 'You are not allowed to modify a remotely hosted branch.');
}