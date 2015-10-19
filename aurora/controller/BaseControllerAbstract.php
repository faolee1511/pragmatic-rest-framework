<?php
/**
 * Abstract Base Controller Class
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 6/2/14
 * Time: 9:06 PM
 */

namespace aurora\controller;
use aurora\model;
use \Slim\Slim;

class BaseControllerAbstract {
    protected $model;

    function validateModel() {
        if (!class_exists($this->model)) {
            deliver_error_response(
                500,
                50003,
                'Invalid model',
                array(
                    'model' => $this->model,
                    'error' => 'Not existing'
                )
            );
        }
    }

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

        if (property_exists($obj, 'priority_level')) {
            $obj->priority_level = $obj->getNextPriorityLevel();
        }
        if (property_exists($obj, 'status')) {
            $obj->status = $obj::STATUS_ENABLED;
        }
        if (property_exists($obj, 'created_on')) {
            $obj->created_on = date('Y-m-d H:i:s.u');
        }
        if (property_exists($obj, 'created_by')) {
            $obj->created_by = model\CurrentRequest::getUserId();
        }
        if (property_exists($obj, 'modified_on')) {
            $obj->modified_on = '0000-00-00 00:00:00';
        }
        if (property_exists($obj, 'modified_by')) {
            $obj->modified_by = '';
        }

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

    function getAll() {
        $this->validateModel();

        // Get request parameters
        $parameters = (array) Slim::getInstance()->request()->params();

        // Create object instance
        $obj = new $this->model();

        val_access($obj::MODULE, 'READ');

        // Get data
        $data = $obj->getAll($parameters);

        // Get record count
        $total = $obj->getCount($parameters);

        // Add paging info to response if requested
        $ref = get_paging_reference($total, '/' . $obj::ROUTE);

        // Add module info
        $ref['module'] = $obj::MODULE;
        $ref['permission'] = 'READ';

        deliver_data_response(
            200,
            'Resources Found',
            $data,
            $ref
        );
    }

    function count() {
        $this->validateModel();

        // Get request parameters
        $parameters = (array) Slim::getInstance()->request()->params();

        // Create object instance
        $obj = new $this->model();

        val_access($obj::MODULE, 'READ');

        // Get record count
        $total = $obj->getCount($parameters);

        deliver_data_response(
            200,
            'Total Resources',
            '',
            array (
                'count' => $total,
                'module' => $obj::MODULE,
                'permission' => 'READ',
            )
        );
    }

    function get($id) {
        $this->validateModel();

        // Get request parameters
        $parameters = (array) Slim::getInstance()->request()->params();

        // Create object instance
        $obj = new $this->model();

        val_access($obj::MODULE, 'READ');

        // Get data
        $data = $obj->getById($id, $parameters);

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

    function postController($id) {
        $method = Slim::getInstance()->request()->params('_method');

        switch (strtoupper($method))
        {
            case 'PUT':
                $this->update($id);
                break;
            case 'PATCH':
                $this->patchUpdate($id);
                break;
            case 'DELETE':
                $this->delete($id);
                break;
            case '':
                $this->update($id);
                break;
            default:
                deliver_error_response(
                    404,
                    40401,
                    'Unsupported method',
                    array(
                        'method' => $method,
                        'error' => 'Not supported'
                    )
                );
                break;
        }
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

        if (property_exists($obj, 'modified_on')) {
            $obj->modified_on = date('Y-m-d H:i:s.u');
        }
        if (property_exists($obj, 'modified_by')) {
            $obj->modified_by = model\CurrentRequest::getUserId();
        }

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

        if (property_exists($obj, 'modified_on')) {
            $obj->modified_on = date('Y-m-d H:i:s.u');
        }
        if (property_exists($obj, 'modified_by')) {
            $obj->modified_by = model\CurrentRequest::getUserId();
        }

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

    function updateStatus($id) {
        $this->validateModel();

        // Get data from body
        $data = (array) json_decode(Slim::getInstance()->request()->getBody());

        // Create object instance
        $obj = new $this->model($id);

        val_access($obj::MODULE, 'WRITE');

        // Update object
        $obj->updateStatus($data);

        deliver_data_response(
            200,
            'Resource Status Updated',
            null,
            array(
                'module' => $obj::MODULE,
                'permission' => 'WRITE',
            )
        );
    }

    function setPriorityLevel($id) {
        $this->validateModel();

        // Get data from body
        $data = (array) json_decode(Slim::getInstance()->request()->getBody());

        // Create object instance
        $obj = new $this->model($id);

        val_access($obj::MODULE, 'WRITE');

        // Get priority level
        if (!array_key_exists('priority_level', $data)) {
            deliver_error_response('400', 40002, 'New priority level required.');
        }
        $new_priority_level = (int) $data['priority_level'];

        // Update object
        $rowsAffected = $obj->setPriorityLevel($new_priority_level);

        deliver_data_response(
            200,
            'Resource Status Updated',
            '' ,
            array(
                'rowsAffected' => $rowsAffected,
                'module' => $obj::MODULE,
                'permission' => 'WRITE',
            )
        );
    }

    function delete($id) {
        $this->validateModel();

        // Create object instance
        $obj = new $this->model($id);

        val_access($obj::MODULE, 'DELETE');

        // Update object
        $obj->delete();

        deliver_data_response(
            200,
            'Resource Removed',
            null,
            array(
                'module' => $obj::MODULE,
                'permission' => 'DELETE',
            )
        );
    }
}