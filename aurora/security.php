<?php
/**
 * Security File
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 6/2/14
 * Time: 5:37 PM
 */

// Get current server address
$GLOBALS['host_name'] = 'http://' . $_SERVER['HTTP_HOST'];

/*// Check options request
$app->options("/.*?", function(){
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');
    header('Access-Control-Allow-Headers: api-id, accept, request-date, target-branch, request-signature, request-url, content-type');
    header('Access-Control-Max-Age: 3600');
    deliver_data_response(200, 'OK');
});
*/

// Send headers without body if request method is OPTIONS
if ($app->request->isOptions()) {
    exit;
}

// Get all request header
$header_array = (array) getallheaders();

$accept = explode(',', $header_array['Accept']);
//$content_type = $header_array['Content-Type'];

// Check for request headers for validity
$accept_flag = false;
foreach ($accept as $key => $value) {
    if (strpos($value, 'application/json') === 0
        ||
        strpos($value, '*/*') === 0
    ) {
        $accept_flag = true;
    }
}

// Request does not accept json response
if (!$accept_flag) {
    deliver_error_response(406, 40601, 'Request does not accept application/json format.');
}

// Get required variables
$api_id = '12345678901234'; //$header_array['Api-Id'];
$access_token = '46c802f2d9b5d7d5a4b59500932f0bde'; //$header_array['Access-Token'];
$request_date = $header_array['Request-Date'];
$request_url = $header_array['Request-Url'];
$signature = $header_array['Request-Signature'];

if (empty($api_id)) {
    deliver_error_response(406, 40602, 'API identification not found.');
}

// Get Api data based on Api-Id
$api_obj = new aurora\model\ApiCredential();

$api_data = $api_obj->getApiCredential($api_id);

if (empty($api_data)) {
    deliver_error_response(406, 40602, 'Invalid API identification.');
}

/**
 * Custom Authentication Procedure
 *
 * Validate signature hash before processing any request
*/


if (ENABLE_CUSTOM_SECURITY) {
    // Check validity of signature
    if (md5($request_url . $request_date . $api_id . $api_data->api_key) === $signature) {
        // Convert server and client date & time to epoch format
        $server_date = date('U');
        $client_date = date('U', strtotime($request_date));

        // Get time difference
        $time_difference = $server_date - $client_date;

        // Throw request timeout error if time difference exceeds allowed request time
        if (ENABLE_REQUEST_TIME_LIMIT
            &&
            abs($time_difference) > REQUEST_TIMEOUT
        ) {
            deliver_error_response(408, 40801, 'Your request exceeds the allowed request time.');
        }
    }
    else {
        deliver_error_response(401, 40101, 'Unrecognized signature.');
    }
}


\aurora\model\CurrentRequest::setApiId($api_id);
\aurora\model\CurrentRequest::setApiKey($api_data->api_key);
\aurora\model\CurrentRequest::setServerId($api_data->server_id);
\aurora\model\CurrentRequest::setDefaultHostAddress($api_data->default_host_address);
\aurora\model\CurrentRequest::setAccessToken($access_token);