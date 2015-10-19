<?php
/**
 * Utility File
 *
 * This file contains the functions used for general purposes
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 4/6/14
 * Time: 6:47 PM
 */

/**
 * Returns a Universally Unique Identification
 *
 * @parameter optional string $salt custom string to be used for Id generation for added complexity
 * @parameter optional bool $win_registry_format determines if UUID will be formatted like Windows Registry Ids with dashes
*/
function create_uuid(
    $salt = '',
    $win_registry_format = false
) {
    static $uuid = '';

    // Create random string to be used as salt for complexity
    $uid = uniqid("", true);

    // Use user provided data as additional salt for complexity
    $data = $salt;

    // Use server variables as additional salt for complexity
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['LOCAL_ADDR'];
    //$data .= $_SERVER['LOCAL_PORT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];

    // Hash data and convert to lower case
    $hash = strtolower(hash('ripemd128', $uid . $uuid . md5($data)));

    // Check if request wants registry format before returning hashed value
    if ($win_registry_format) {
        $uuid =
            substr($hash,  0,  8) .
            '-' .
            substr($hash,  8,  4) .
            '-' .
            substr($hash, 12,  4) .
            '-' .
            substr($hash, 16,  4) .
            '-' .
            substr($hash, 20, 12);
    } else {
        $uuid = $hash;
    }

    return $uuid;
}

/**
 * Create a paging reference array
 *
 * @parameter reference array $ref will contain the paging reference
 * @parameter int $per_page number of records per page
 * @parameter int $page current page
 * @parameter int $total total records
 * @parameter string $request_uri the current URI to append to the reference
 * @parameter string $additional_parameters more details to append to the reference
*/
function get_paging_reference(
    $total,
    $request_uri
) {
    // Get parameters
    $parameters = (array) \Slim\Slim::getInstance()->request()->params();

    $per_page = (int) get_value_from_parameter($parameters, 'per_page', 0);
    $page = (int) get_value_from_parameter($parameters, 'page', 0);

    if ($per_page < 1
        ||
        $total  < 1
    ) {
        return array (
            'totalCount' => $total,
        );
    }

    $temp_parameters = array_remove_keys($parameters , array (
        'q',
        'per_page',
        'page',
    ));

    $additional_parameters = array();

    foreach ($temp_parameters as $key => $value) {
        $additional_parameters[] = "$key=$value";
    }

    $additional_parameters = implode('&', $additional_parameters);

    $previous_page = '#';
    $next_page = '#';

    if ($page <= 0) {
        $page = 1;
    }

    if (!empty(trim($additional_parameters))) {
        $additional_parameters =  stripslashes("&$additional_parameters");
    }
    // Add resource link to next and previous page if possible
    if ($page > 1
        &&
        $page <= ceil($total / $per_page)
    ) {
        $previous_page = \aurora\model\CurrentRequest::getHostName() . "$request_uri?per_page=$per_page&page=" . ($page - 1) . "$additional_parameters";
    }
    if ($page < ceil($total / $per_page)) {
        $next_page = \aurora\model\CurrentRequest::getHostName() . "$request_uri?per_page=$per_page&page=" . ($page + 1) . "$additional_parameters";
    }

    return array(
        'page' => "$page",
        'per_page' => "$per_page",
        'first' => \aurora\model\CurrentRequest::getHostName() . "$request_uri?per_page=$per_page&page=1$additional_parameters",
        'previous' => $previous_page,
        'next' => $next_page,
        'last' => \aurora\model\CurrentRequest::getHostName() . "$request_uri?per_page=$per_page&page=" . ceil($total / $per_page) . "$additional_parameters",
        'totalCount' => $total,
    );

}

/**
 * Get value of variable from parameter array
 *
 * @parameter array $parameters list of values
 * @parameter string $variable_name key to search in array
 * @parameter var $default_value a variable default value
*/
function get_value_from_parameter(
    array $parameters,
    $variable_name,
    $default_value
) {
    if (array_key_exists($variable_name, $parameters)) {
        return $parameters[$variable_name];
    } else {
        return $default_value;
    }
}

/**
 * Swaps two variables
 *
 * @parameter variable $var1 first value
 * @parameter variable $var2 second value
*/
function swap_values(&$var1, &$var2) {
    $temp = $var1;
    $var1 = $var2;
    $var2 = $temp;
}

/**
 * Converts a string to hexadecimal value
 *
 * @parameter string $string
*/
function str_to_hex($string) {
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strtoupper($hex);
}

/**
 * Converts a hexadecimal to string value
 *
 * @parameter string $hex
 */
function hex_to_str($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

/**
 * Remove one or more keys from array
 *
 * @parameter array $array base array
 * @parameter array $keys list of all keys to remove
*/
function array_remove_keys(
    array $array,
    array $keys
) {
    // If array is empty or not an array at all, don't bother
    // doing anything else.
    if(empty($array)
        ||
        !is_array($array)
    ) {
        return $array;
    }

    // array_diff_key() expected an associative array.
    $assocKeys = array();
    foreach($keys as $key) {
        $assocKeys[$key] = true;
    }

    return array_diff_key($array, $assocKeys);
}