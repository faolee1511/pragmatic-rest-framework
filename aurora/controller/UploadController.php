<?php
/**
 * Upload Controller
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 10/20/14
 * Time: 4:55 PM
 */

namespace aurora\controller;

class UploadController {
    public function upload(){
        if (!isset($_FILES['uploads'])) {
            deliver_error_response('400', 40001, 'No files uploaded.');
        }

        $imgs = array();
        $default_folder = 'uploaded_files';
        $files = $_FILES['uploads'];

        $cnt = count($files['name']);
        for($i = 0 ; $i < $cnt ; $i++) {
            if ($files['error'][$i] === 0) {
                $name = create_uuid();
                if (move_uploaded_file($files['tmp_name'][$i], "$default_folder/" . $name) === true) {
                    $imgs[] = array(
                        'url' => $default_folder . $name,
                        'name' => $files['name'][$i]
                    );
                }
            }
        }

        $img_ctr = count($imgs);
        if ($img_ctr == 0) {
            deliver_error_response('400', 40001, 'No files uploaded.');
        }

        // File upload successful

        deliver_data_response(
            200,
            'Files Uploaded',
            $imgs,
            array(
                'module' => 'FILE_UPLOAD',
                'permission' => 'WRITE',
            )
        );
    }
} 