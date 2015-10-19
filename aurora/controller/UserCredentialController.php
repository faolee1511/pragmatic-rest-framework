<?php
/**
 * User Credential Controller
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 5/24/14
 * Time: 11:26 AM
 */

namespace aurora\controller;
use aurora\model;
use \Slim\Slim;

class UserCredentialController
    extends BaseControllerAbstract
{
    protected $model = '\aurora\model\UserCredential';

    public function login() {
        // Create object instance
        $obj = new $this->model();

        // Get data
        $data = $obj->login();

        deliver_data_response(200, 'Login Successful', $data);
        exit;
    }

    function getToken() {
        $this->validateModel();

        // Create object instance
        $obj = new $this->model();

        val_access($obj::MODULE, 'READ');

        // Get data
        $data = $obj->getAccessByToken(model\CurrentRequest::getAccessToken());

        deliver_data_response(
            200,
            'Resource Found',
            $data,
            array(
                'module' => $obj::MODULE,
                'permission' => 'READ',
            )
        );
    }

    // Overrides

    function add() {
        $this->validateModel();

        // Get data from body
        $properties = (array) json_decode(Slim::getInstance()->request()->getBody());

        // Create object instance
        $obj = new $this->model();

        val_access($obj::MODULE, 'WRITE');

        // Check if all parameters are sent
        $obj->validateParameterCompleteness($properties);

        // Map submitted values to properties
        $obj->mapProperties($properties);

        // Custom values
        $obj->{$obj::PRIMARY_KEY} = create_uuid(model\CurrentRequest::getUserId());
        $obj->password_salt = uniqid();
        $obj->hashed_password = crypt($obj->hashed_password,'$5$' . $obj->password_salt );
        $obj->last_activity = '0000-00-00 00:00:00';
        $obj->status = $obj::STATUS_ENABLED;
        $obj->created_on = date('Y-m-d H:i:s.u');
        $obj->created_by = model\CurrentRequest::getUserId();
        $obj->modified_on = '0000-00-00 00:00:00';
        $obj->modified_by = '';

        // Create object
        $obj->create();

        deliver_data_response(
            201,
            'Resource Created',
            null,
            array(
                'location' => model\CurrentRequest::getHostName() . '/' . $obj::ROUTE . '/' . $obj->{$obj::PRIMARY_KEY},
                'id' => $obj->{$obj::PRIMARY_KEY},
                'module' => $obj::MODULE,
                'permission' => 'WRITE',
            )
        );
    }

    function update($id) {
        $this->validateModel();

        // Get data from body
        $properties = (array) json_decode(Slim::getInstance()->request()->getBody());

        // Create object instance
        $obj = new $this->model($id);

        val_access($obj::MODULE, 'WRITE');

        // Check if all parameters are sent
        $obj->validateParameterCompleteness($properties);

        // Map submitted values to properties
        $obj->mapProperties($properties);

        // Custom values
        $obj->password_salt = uniqid();
        $obj->hashed_password = crypt($obj->hashed_password,'$5$' . $obj->password_salt);
        $obj->modified_on = date('Y-m-d H:i:s.u');
        $obj->modified_by = model\CurrentRequest::getUserId();

        // Update object
        $obj->update();

        deliver_data_response(
            200,
            'Resource Updated',
            null,
            array(
                'module' => $obj::MODULE,
                'permission' => 'WRITE',
            )
        );
    }

    function patchUpdate($id) {
        $this->validateModel();

        // Get data from body
        $properties = (array) json_decode(Slim::getInstance()->request()->getBody());

        // Create object instance
        $obj = new $this->model($id);

        val_access($obj::MODULE, 'WRITE');

        // Map submitted values to properties
        $map_count = $obj->mapProperties($properties);

        // Custom values
        if (!empty($obj->hashed_password)) {
            $obj->password_salt = uniqid();
            $obj->hashed_password = crypt($obj->hashed_password,'$5$' . $obj->password_salt);
        }
        $obj->modified_on = date('Y-m-d H:i:s.u');
        $obj->modified_by = model\CurrentRequest::getUserId();

        // Update object
        $obj->update();

        deliver_data_response(
            200,
            'Resource Patched',
            null,
            array(
                'totalAppliedPatched' => $map_count,
                'module' => $obj::MODULE,
                'permission' => 'WRITE',
            )
        );
    }
} 