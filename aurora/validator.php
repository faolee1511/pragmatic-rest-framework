<?php
// Regular expressions constants
const REGEX_UUID = '/^[a-z0-9]*$/';
const REGEX_GENERIC = '/^[a-zA-Z0-9 ._%-]*$/';
const REGEX_ALPHA = '/^[a-zA-ZÑñ]*$/';
const REGEX_ALPHA_S = '/^[a-zA-Z Ññ]*$/';
const REGEX_NUMERIC = '/^[0-9]*$/';
const REGEX_NUMERIC_S = '/^[0-9 ]*$/';
const REGEX_DECIMAL = '/^[0-9.]*$/';
const REGEX_ALPHANUMERIC = '/^[a-zA-Z0-9Ññ]*$/';
const REGEX_ALPHANUMERIC_S = '/^[a-zA-Z0-9 Ññ]*$/';
const REGEX_USERNAME = '/^[a-zA-Z0-9]*$/';
const REGEX_PHONE_NUMBER = '/^[a-zA-Z0-9 ()-]*$/';
const REGEX_DATE = '#^((19|20)?[0-9]{2}[- /.](0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01]))*$#';
const REGEX_TIME = '/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/';
const REGEX_EMAIL = '/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/';
const REGEX_URI = '/^(http(s?):\/\/)?(((www\.)?+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+)|(\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b))(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/i';
const REGEX_VARIABLE_NAME = '/^[a-z0-9_]*$/';

/**
 * Validator File
 *
 * This file contains functions that handles data validations and formatting
 * Created by PhpStorm.
 * User: DevMode
 * Date: 4/9/14
 * Time: 10:41 AM
 */

/**
 * Validates for required values
 *
 * @parameter reference array $error container of error list that will be populated during validation
 * @parameter string $parameterName name of the parameter to be used for error reporting
 * @parameter string $value value of the parameter to be checked for empty value
 */
function val_required(
    array &$error,
    $parameterName,
    $value
) {
    if ((trim($value)) == '') {
        $error[] = array (
            'parameter' => "$parameterName",
            'valueSent' => "$value",
            'errorMessage' => 'Value required.',
        );
    }
}

/**
 * Validates if value is within expected range
 *
 * @parameter reference array $error container of error list that will be populated during validation
 * @parameter string $parameterName name of the parameter to be used for error reporting
 * @parameter int $value value of the parameter to be checked range validity
 * @parameter int $min the minimum value accepted
 * @parameter int $max the maximum value accepted
 */
function val_range(
    array &$error,
    $parameterName,
    $value,
    $min,
    $max
){
    if (!is_numeric($value)) {
        $error[] = array (
            'parameter' => "$parameterName",
            'valueSent' => "$value",
            'errorMessage' => 'Invalid value.',
            'minValue' => "$min",
            'maxValue' => "$max",
        );
    } else if (
        $value < $min
        ||
        $value > $max
    ) {
        $error[] = array (
            'parameter' => "$parameterName",
            'valueSent' => "$value",
            'errorMessage' => 'Out of range.',
            'minValue' => "$min",
            'maxValue' => "$max",
        );
    }
}

/**
 * Validates if value conforms to expected pattern
 *
 * @parameter reference array $error container of error list that will be populated during validation
 * @parameter string $parameterName name of the parameter to be used for error reporting
 * @parameter string $value value of the parameter to be checked pattern validity
 * @parameter string $regex the regex pattern to be used for checking
 * @parameter optional bool $checkEmpty controls if function should validate empty $value
 */

function val_regex(
    array &$error,
    $parameterName,
    $value,
    $regex,
    $checkEmpty = false
){
    if (!empty($regex)
        &&
        (
            $checkEmpty
            ||
            (
                strlen($value) > 0
                &&
                !$checkEmpty
            )
        )
    ) {
        if (!preg_match("$regex", $value)) {
            $error[] = array (
                'parameter' => "$parameterName",
                'valueSent' => "$value",
                'errorMessage' => 'Invalid format.',
                'regex' => "$regex",
            );
        }
    }
}

function val_access($module_id, $access_code){
    // \aurora\model\CurrentRequest::getTargetBranchId();
    // \aurora\model\CurrentRequest::getBranchId();
    // \aurora\model\CurrentRequest::getUserCredentialId();
    // Check if user has access to module

    //deliver_error_response(400, 40002, 'Your data contains invalid parameters.', $error);
    return true;
}