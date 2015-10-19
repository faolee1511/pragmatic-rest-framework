<?php
/**
 * Created by PhpStorm.
 * User: Pao
 * Date: 6/2/14
 * Time: 5:51 PM
 */

namespace aurora\model;

class CurrentRequest {

    public function __construct($token) {
        // Pre load

        $this->load($token);
    }

    public static function load($token) {
        if (empty($token)) {
            deliver_error_response(406, 40601, 'Access token not found.');
        }

        // Create object instance
        $access_obj = new UserCredential;

        // Get data
        $access_data = $access_obj->getUserByToken($token);

        if (empty($access_data)) {
            deliver_error_response(406, 40601, 'Invalid access token.');
        }

        // Get branch id of requesting branch
        $GLOBALS['access_token'] = $token;
        $GLOBALS['user_id'] = $access_data->user_id;
        $GLOBALS['branch_id'] = $access_data->branch_id;
        $GLOBALS['target_branch_id'] = $access_data->branch_id;

        $header_array = (array) getallheaders();

        $app = \Slim\Slim::getInstance()->request();

        // Get custom target branch
        $target_branch = $header_array['Target-Branch'];

        if (!empty($app->params('target_branch'))) {
            $target_branch = $app->params('target_branch');
        }

        // Get requesting branch details
        $base_obj = new Branch($GLOBALS['branch_id']);

        $GLOBALS['parent_branch_id'] = $base_obj->parent_branch_id;
        $GLOBALS['host_address'] = $base_obj->host_address;

        // Apply details to proxy branch
        if (empty($target_branch)) {
            $GLOBALS['target_branch_id'] = $base_obj->branch_id;
            $GLOBALS['target_parent_branch_id'] = $base_obj->parent_branch_id;
            $GLOBALS['target_host_address'] = $base_obj->host_address;
        } else {
            $target_obj = new Branch($target_branch);

            if (!empty($target_obj)) {
                $GLOBALS['target_branch_id'] = $target_obj->branch_id;
                $GLOBALS['target_parent_branch_id'] = $target_obj->parent_branch_id;
                $GLOBALS['target_host_address'] = $target_obj->host_address;
            }
        }
    }

    public static function setApiId($value) {
        $GLOBALS['api_id'] = $value;
    }

    public static function getApiId() {
        return $GLOBALS['api_id'];
    }

    public static function setApiKey($value) {
        $GLOBALS['api_key'] = $value;
    }

    public static function getApiKey() {
        return $GLOBALS['api_key'];
    }

    public static function setServerId($value) {
        $GLOBALS['server_id'] = $value;
    }

    public static function getServerId() {
        return $GLOBALS['server_id'];
    }

    public static function setDefaultHostAddress($value) {
        $GLOBALS['default_host_address'] = $value;
    }

    public static function getDefaultHostAddress() {
        return $GLOBALS['default_host_address'];
    }

    public static function setAccessToken($value) {
        $GLOBALS['access_token'] = $value;
    }

    public static function getAccessToken() {
        return $GLOBALS['access_token'];
    }

    public static function setUserId($value) {
        $GLOBALS['user_id'] = $value;
    }

    public static function getUserId() {
        return $GLOBALS['user_id'];
    }

    public static function setUserCredentialId($value) {
        $GLOBALS['user_credential_id'] = $value;
    }

    public static function getUserCredentialId() {
        return $GLOBALS['user_credential_id'];
    }

    public static function setBranchId($value) {
        $GLOBALS['branch_id'] = $value;
    }

    public static function getBranchId() {
        return $GLOBALS['branch_id'];
    }

    public static function setParentBranchId($value) {
        $GLOBALS['parent_branch_id'] = $value;
    }

    public static function getParentBranchId() {
        return $GLOBALS['parent_branch_id'];
    }

    public static function setHostAddress($value) {
        $GLOBALS['host_address'] = $value;
    }

    public static function getHostAddress() {
        return $GLOBALS['host_address'];
    }

    public static function setTargetBranchId($value) {
        $GLOBALS['target_branch_id'] = $value;
    }

    public static function getTargetBranchId() {
        return $GLOBALS['target_branch_id'];
    }

    public static function setTargetParentBranchId($value) {
        $GLOBALS['target_parent_branch_id'] = $value;
    }

    public static function getTargetParentBranchId() {
        return $GLOBALS['target_parent_branch_id'];
    }

    public static function setTargetHostAddress($value) {
        $GLOBALS['target_host_address'] = $value;
    }

    public static function getTargetHostAddress() {
        return $GLOBALS['target_host_address'];
    }

    public static function getHostName() {
        return $GLOBALS['host_name'];
    }

    public static function getModuleAccessConfiguration() {
        $resource_location = '/modules';

        return array(
            'access' => array(
                'group_name' => 'Access',
                'modules' => array(

                ),
            ),
            'data' => array(
                'group_name' => 'Data',
                'modules' => array(
                    'branch' => array(
                        'href' => CurrentRequest::getHostName() . "$resource_location/branch",
                        'module_id' => 'branch',
                        'name' => 'Branch',
                        'access' => array(
                            'READ' => 'Read',
                            'READ+WRITE' => 'Write',
                            'READ+WRITE+DELETE' => 'Delete',
                        ),
                    ),
                    'role' => array(
                        'href' => CurrentRequest::getHostName() . "$resource_location/role",
                        'module_id' => 'role',
                        'name' => 'Role',
                        'access' => array(
                            'READ' => 'Read',
                            'READ+WRITE' => 'Write',
                            'READ+WRITE+DELETE' => 'Delete',
                        ),
                    ),
                    'department' => array(
                        'href' => CurrentRequest::getHostName() . "$resource_location/department",
                        'module_id' => 'department',
                        'name' => 'Department',
                        'access' => array(
                            'READ' => 'Read',
                            'READ+WRITE' => 'Write',
                            'READ+WRITE+DELETE' => 'Delete',
                        ),
                    ),
                    'shift' => array(
                        'href' => CurrentRequest::getHostName() . "$resource_location/shift",
                        'module_id' => 'shift',
                        'name' => 'Shift',
                        'access' => array(
                            'READ' => 'Read',
                            'READ+WRITE' => 'Write',
                            'READ+WRITE+DELETE' => 'Delete',
                        ),
                    ),
                    'user' => array(
                        'href' => CurrentRequest::getHostName() . "$resource_location/user",
                        'module_id' => 'user',
                        'name' => 'User',
                        'access' => array(
                            'READ' => 'Read',
                            'READ+WRITE' => 'Write',
                            'READ+WRITE+DELETE' => 'Delete',
                        ),
                    ),
                    'role_permission' => array(
                        'href' => CurrentRequest::getHostName() . "$resource_location/role_permission",
                        'module_id' => 'role_permission',
                        'name' => 'Role Permission',
                        'access' => array(
                            'READ' => 'Read',
                            'READ+WRITE' => 'Write',
                            'READ+WRITE+DELETE' => 'Delete',
                        ),
                    ),
                    'item' => array(
                        'href' => CurrentRequest::getHostName() . "$resource_location/item",
                        'module_id' => 'item',
                        'name' => 'Item',
                        'access' => array(
                            'READ' => 'Read',
                            'READ+WRITE' => 'Write',
                            'READ+WRITE+DELETE' => 'Delete',
                        ),
                    ),
                ),
            ),
        );
    }
} 