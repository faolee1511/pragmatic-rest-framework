<?php
/**
 * Api Credential Class
 * Created by PhpStorm.
 * User: Pao
 * Date: 6/2/14
 * Time: 1:43 PM
 */

namespace aurora\model;

class ApiCredential
    extends BaseUtilityAbstract
{
    public function getApiCredential($api_id) {
        if (empty($api_id)) {
            return null;
        }

        // SQL query
        $sql = "
            SELECT
                api_id,
                HEX(api_key) as api_key,
                server_id,
                consumer_name,
                consumer_type
            FROM
                system_api_credential
            WHERE
                api_id = :api_id
            AND
                status = 'ENABLED'
            LIMIT 1 ";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('api_id', $api_id);

        // Execute query
        return $this->fetch($stmt);
    }
} 