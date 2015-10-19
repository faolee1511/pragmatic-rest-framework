<?php
/**
 * Configuration File
 *
 * This file contains all the global api settings and configuration
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 3/15/14
 * Time: 10:29 PM
 */

// Database Configuration

/**
 * Specify the database address
 *
 * NOTE: Use 127.0.0.1 instead of localhost if possible
*/
const DB_HOST = "127.0.0.1";

/**
 * Specify the username used to access your database
 */
const DB_USER = "root";

/**
 * Specify the password used to access your database
 */
const DB_PASS = "root";

/**
 * Specify the database name
 */
const DB_NAME = "aurora";

// API Configuration

/**
 * Length of user session in minutes after logging in
 *  */
const SESSION_LENGTH = 1400;

// Security Configuration

/**
 * Set ENABLE_CUSTOM_SECURITY to false to disable API custom security
 *
 * API custom security checks for digital signature validity
 * from client requests
 *  */
const ENABLE_CUSTOM_SECURITY = false;

/**
 * Set ENABLED_REQUEST_TIME_LIMIT to false to disable request timeout check
 *
 * This is ignored if ENABLE_CUSTOM_SECURITY is false
 * */
const ENABLE_REQUEST_TIME_LIMIT = true;

/**
 * Time allowance in seconds for a request before timeout
 *
 * This is ignored if ENABLED_REQUEST_TIME_LIMIT is false
 * or ENABLE_CUSTOM_SECURITY = false
 *  * */
const REQUEST_TIMEOUT = 1200;

