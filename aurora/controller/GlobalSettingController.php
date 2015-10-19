<?php
/**
 * Global Setting Controller
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/9/14
 * Time: 6:30 PM
 */

namespace aurora\controller;
use aurora\model;
use Slim\Slim;


class GlobalSettingController
    extends BaseControllerAbstract
{
    protected $model = '\aurora\model\GlobalSetting';

    // Overrides
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
        $obj->currency_symbol = str_to_hex($obj->currency_symbol);
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
        $obj->currency_symbol = str_to_hex($obj->currency_symbol);
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