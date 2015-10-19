<?php
/**
 * Connect File
 *
 * This file contains the database connection procedure and handling
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 3/15/14
 * Time: 2:01 PM
 */

function get_db_connection() {
    try {
        // Create new PDO connection
        $dbh = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

        // Specify connection attributes
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Return connection object
        return $dbh;
    } catch(\PDOException $e) {
        deliver_error_response(500, 50001, $e->getMessage());
    }
}
