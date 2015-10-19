<?php
/**
 * Module Controller
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 5/10/14
 * Time: 1:47 AM
 */

namespace aurora\controller;
use aurora\model;

class ModuleController
    extends BaseControllerAbstract
{
    protected $model = '\aurora\model\Module';

    /**
     * Overrides
    */

    function getAll() {
        $this->validateModel();

        // Create object instance
        $obj = new $this->model();

        val_access($obj::MODULE, 'READ');

        // Get data
        $data = $obj->getAll();

        // Get record count
        $total = 0;
        foreach($data as $value) {
            $total += count($value['modules']);
        }

        deliver_data_response(
            200,
            'Resources Found',
            $data,
            array(
                'totalCount' => $total,
                'module' => $obj::MODULE,
                'permission' => 'READ',
            )
        );
    }

    function count() {
        $this->validateModel();

        // Create object instance
        $obj = new $this->model();

        val_access($obj::MODULE, 'READ');

        // Get record count
        $total = $obj->getCount();

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

        // Create object instance
        $obj = new $this->model();

        val_access($obj::MODULE, 'READ');

        // Get data
        $data = $obj->getById($id);

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

} 