<?php
/**
 * Menu Group Category Controller
 * Created by PhpStorm.
 * User: DevMode
 * Date: 7/19/14
 * Time: 5:13 PM
 */

namespace aurora\controller;
use aurora\model;
use Slim\Slim;

class MenuGroupCategoryController
    extends BaseControllerAbstract
{
    protected $model = '\aurora\model\MenuGroupCategory';

    /**
     * Overrides
     */

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
        $obj->priority_level = $obj->getNextPriorityLevel('menu_group_id');
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
        $rowsAffected = $obj->setPriorityLevel($new_priority_level, 'menu_group_id');

        deliver_data_response(
            200,
            'Priority Level Updated',
            '' ,
            array(
                'rowsAffected' => $rowsAffected,
                'module' => $obj::MODULE,
                'permission' => 'WRITE',
            )
        );
    }
} 