<?php
/**
 * Branch Controler
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 5/14/14
 * Time: 12:54 AM
 */

namespace aurora\controller;
use Slim\Slim;

class BranchController
    extends BaseControllerAbstract
{
    protected $model = 'aurora\model\Branch';

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

} 