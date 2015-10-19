<?php
/**
 * Anonymous Request File
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 6/8/14
 * Time: 11:50 AM
 */

// Returns list of valid branches
if (strpos($app->request()->getResourceUri(), '/branches/list') === 0) {
    if (!$app->request->isGet()) {
        deliver_error_response(404, 40401, 'METHOD is not supported.');
    }
    $unknown_branch = new aurora\model\Branch();
    $data = $unknown_branch->GetBranchesByServerId(\aurora\model\CurrentRequest::getServerId());

    // Get record count
    $total = count($data);

    // Add paging info to response if requested
    $ref = get_paging_reference($total, 'branches');

    deliver_data_response(200, 'Resources Found', $data, $ref);
    exit;
}

// Returns list of users
if (strpos($app->request()->getResourceUri(), '/user_credentials/list') === 0) {
    if (!$app->request->isGet()) {
        deliver_error_response(404, 40401, 'METHOD is not supported.');
    }
    $credential_obj = new aurora\model\UserCredential();
    $data = $credential_obj->getCredentialsByServerId(\aurora\model\CurrentRequest::getServerId());

    // Get record count
    $total = count($data);

    // Add paging info to response if requested
    $ref = get_paging_reference($total, 'user_credentials');

    deliver_data_response(200, 'Resources Found', $data, $ref);
    exit;
}

// Trigger login if requested
if (strpos($app->request()->getResourceUri(), '/user_credentials/login') === 0) {
    if (!$app->request->isPost()) {
        deliver_error_response(404, 40401, 'METHOD is not supported.');
    }

    // Create object instance
    $obj = new \aurora\model\UserCredential;

    // Get data
    $data = $obj->login();

    deliver_data_response(200, 'Login Successful', $data);
    exit;
}