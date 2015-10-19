<?php
/**
 * Responder File
 *
 * This file contains functions responsible for HTML response
 *  *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 4/9/14
 * Time: 10:42 AM
 */

/**
 * This function is responsible for resource delivery
 *
 * @parameter int $status_code html header response code
 * @parameter string $message html header response message
 * @parameter optional generic object $data
 * @parameter optional array $ref additional information to be added in the response body
 *
 * */
function deliver_data_response(
    $status_code,
    $message,
    $data = null,
    array $ref = null
) {
    // Use UTC time
    date_default_timezone_set("UTC");

    // Append response headers
    header("Content-Type: application/json");

    // Get parameters
    $suppressResponseCodes = (string) \Slim\Slim::getInstance()->request()->params('suppress_response_codes');
    if (strtolower(trim($suppressResponseCodes)) == 'true') {
        header($_SERVER["SERVER_PROTOCOL"] . " 200 $message");
    } else {
        header($_SERVER["SERVER_PROTOCOL"] . " $status_code $message");
    }

    // Build response array
    $response['status'] = "$status_code";
    $response['message'] = "$message";
    $response['createdAt'] = date("Y-m-d") . 'T' . date("H:i:s.000") . 'Z';

    // Check for additional information
    if (!empty($ref)) {
        foreach ($ref as $key => $value) {
            $response[$key] = "$value";
        }
    }

    // Add response data
    if (empty($data)) {
        $data = null;
    }
    $response['data'] = $data;

    // Send response in json format
    echo json_encode($response);
}

/**
 * This function is responsible for resource error delivery
 *
 * @parameter int $status_code html header response code
 * @parameter int $code specific custom error code
 * @parameter string $developer_message error message for developer use
 * @parameter optional array $detailed_error complete detailed list of errors
 *
 * */
function deliver_error_response(
    $status_code,
    $code,
    $developer_message,
    array $detailed_error = null
) {

    // Use UTC time
    date_default_timezone_set("UTC");

    //Get proper message
    $message = get_error_message_by_code($code);

    // Append response headers
    header("Content-Type: application/json");

    // Get parameters
    $suppressResponseCodes = (string) \Slim\Slim::getInstance()->request()->params('suppress_response_codes');
    if (strtolower(trim($suppressResponseCodes)) == 'true') {
        header($_SERVER["SERVER_PROTOCOL"] . " 200 $message");
    } else {
        header($_SERVER["SERVER_PROTOCOL"] . " $status_code $message");
    }

    // Build response array
    $response['status'] = "$status_code";
    $response['code'] = "$code";
    $response['message'] = "$message";
    $response['developerMessage'] = "$developer_message";
    $response['moreInfo'] = \aurora\model\CurrentRequest::getHostName() . '/docs/errors/#' . $code;

    // Check for detailed error
    if (!empty($detailed_error)) {
        $response['errorDetails'] = $detailed_error;
    }

    // Send response in json format
    echo json_encode($response);

    // Stop processing
    exit;
}

/**
 * Returns an error message based on error code
 *
 * @parameter int $code specific custom error code
 * */
function get_error_message_by_code($code) {
    switch ($code) {
        // 400 Bad Requests
        case '40001':
            return 'Validation Error';

        // 401 Unauthorized
        case '40101':
            return 'Unauthorized Request';

        case '40102':
            return 'Unauthorized Viewing Request';

        case '40103':
            return 'Unauthorized Modification Request';

        // 403 Forbidden
        case '40301':
            return '';

        // 404 Not found
        case '40401':
            return 'No Resource Found';

        // 406 Not found
        case '40601':
            return 'Request Not Acceptable';
        case '40602':
            return 'Request Origin Unknown';

        // 408 Request Time Out
        case '40801':
            return 'Request Time Out';

        // 500 Internal Server Error
        case '50001':
            return 'Database Connection Error';

        case '50002':
            return 'Invalid SQL Query';
    }
}