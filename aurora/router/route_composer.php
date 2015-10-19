<?php
/**
 * Route Composer
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 7/20/14
 * Time: 9:33 PM
 */

$controller_path = '\aurora\controller';
$model_path = '\aurora\model';

/**
 * Dynamic Routing Generation Procedure
*/
foreach ($route_settings as $key => $value) {
    // Compose controller and model paths
    $controller_obj_path = $controller_path . "\\$key" . 'Controller';
    $model_obj_path = $model_path . "\\$key";

    if (!class_exists($controller_obj_path)
        ||
        !class_exists($model_obj_path)
    ) {
        continue;
    }

    // Create instance of controller
    $controller_obj = new $controller_obj_path();
    // Create instance of model
    $model_obj = new $model_obj_path();
    // Get resource route
    $base_uri = '/' .  $model_obj::ROUTE;

    // Custom Routes
    if (array_key_exists('custom_get', $value)
        &&
        !empty($value['custom_get'])
    ) {
        foreach($value['custom_get'] as $method => $custom_route) {
            $app->get("$custom_route", "$controller_obj_path:$method");
        }
    }

    // POST Requests

    // Create
    if (array_key_exists('create', $value)
        &&
        $value['create']
    ) {
        $app->post("$base_uri(:parameters)", "$controller_obj_path:add");
    }

    // Priority Level Update
    if (array_key_exists('update_priority', $value)
        &&
        $value['update_priority']
    ) {
        $app->post("$base_uri/:id/priority_level", "$controller_obj_path:setPriorityLevel");
    }

    // Status Update
    if (array_key_exists('update_status', $value)
        &&
        $value['update_status']
    ) {
        $app->post("$base_uri/:id/status", "$controller_obj_path:updateStatus");
    }

    // POST Custom Methods
    if (array_key_exists('post_method', $value)
        &&
        $value['post_method']
    ) {
        $app->post("$base_uri/:id(/?:parameters)", "$controller_obj_path:postController");
    }

    // GET Requests

    // Get All
    if (array_key_exists('get_all', $value)
        &&
        $value['get_all']
    ) {
        $app->get("$base_uri(:parameters)", "$controller_obj_path:getAll");
    }

    // Count
    if (array_key_exists('count', $value)
        &&
        $value['count']
    ) {
        $app->get("$base_uri/count(:parameters)", "$controller_obj_path:count");
    }

    // Get By Id
    if (array_key_exists('get_by_id', $value)
        &&
        $value['get_by_id']
    ) {
        $app->get("$base_uri/:id(/?:parameters)", "$controller_obj_path:get");
    }

    // PUT Requests

    // Update
    if (array_key_exists('update', $value)
        &&
        $value['update']
    ) {
        $app->put("$base_uri/:id", "$controller_obj_path:update");
    }

    // PATCH Requests

    // Update
    if (array_key_exists('patch', $value)
        &&
        $value['patch']
    ) {
        $app->patch("$base_uri/:id", "$controller_obj_path:patchUpdate");
    }

    // DELETE Requests

    // Update
    if (array_key_exists('delete', $value)
        &&
        $value['delete']
    ) {
        $app->delete("$base_uri/:id", "$controller_obj_path:delete");
    }
}

/**
 * File Uploading Route
 *
 * This function handles the routing for image file uploading
*/

$app->post('/files', $controller_path . "\\UploadController::upload");