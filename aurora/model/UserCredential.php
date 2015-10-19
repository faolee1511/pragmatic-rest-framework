<?php
/**
 * User Credential Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 5/24/14
 * Time: 10:34 AM
 */

namespace aurora\model;
use Slim\Slim;

class UserCredential
    extends DbContextAbstract
{
// Class constants
    const TABLE_NAME = 'user_credential';
    const PRIMARY_KEY = 'user_credential_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'user_credentials';
    const MODULE = 'user';

    // Class properties
    public $branch_id = '';
    public $user_id = '';
    public $user_credential_id = '';
    public $username;
    public $hashed_password;
    public $password_salt;
    public $pin_code;
    public $last_activity;
    public $status;
    public $created_on;
    public $created_by;
    public $modified_on;
    public $modified_by;

    /**
     * Class constructor & destructor
     */

    public function __construct($id = '') {
        parent::__construct();

        // Start class constructor
        $this->branch_id = CurrentRequest::getTargetBranchId();

        // Pre load
        $this->load($id);
    }

    public function __destruct() {
        parent::__destruct();

        // Start sub class destructor
    }

    /**
     * Model & Property Settings
     */

    protected function getPropertySettings() {
        $property_settings = array(
            'user_id' => array(
                'required' => true,
                'custom' => [
                    array (
                        'method' => 'isUnique',
                        'parameter' => array (
                            'user_id',
                            $this->user_id,
                        ),
                        'error_message' => 'User already has credentials.',
                    ),
                ],
                'foreign_key' => array (
                    'class' => '\aurora\model\User',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'user_credential_id' => array(
                'scaffold' => false,
            ),
            'username' => array(
                'required' => true,
                'format' => REGEX_USERNAME,
                'custom' => [
                    array (
                        'method' => 'isUnique',
                        'parameter' => array (
                            'username',
                            $this->username,
                        ),
                        'error_message' => 'Username name must be unique.',
                    ),
                ],
            ),
            'hashed_password' => array(
                'required' => true,
                'visible' => false,
            ),
            'password_salt' => array(
                'scaffold' => false,
                'visible' => false,
            ),
            'pin_code' => array(
                'required' => true,
                'range' => array (1000, 9999),
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'pin_code',
                        $this->pin_code,
                    ),
                    'error_message' => 'Pin code must be unique.',
                )],
                'shared' => false,
            ),
            'last_activity' => array(
                'scaffold' => false,
            ),
            'status' => array(
                'scaffold' => false,
                'shared' => true,
            ),
            'created_on' => array(
                'scaffold' => false,
                'shared' => false,
            ),
            'created_by' => array(
                'scaffold' => false,
                'shared' => false,
            ),
            'modified_on' => array(
                'scaffold' => false,
                'shared' => false,
            ),
            'modified_by' => array(
                'scaffold' => false,
                'shared' => false,
            ),
        );

        // Append enum format
        $this->addEnumFormatValidator($this::TABLE_NAME, $property_settings);

        return $property_settings;
    }

    public static function getSharedFields($alias) {
        $self = new UserCredential();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new UserCredential();
        $table_name = $self::TABLE_NAME;
        $table_alias = $self::TABLE_ALIAS;

        switch ($query_section) {
            //  SELECT
            case 'fields':
                return array_merge(
                    $self->getModelProperties($table_alias),
                    array(
                        'created_by' => "CONCAT(u1.first_name, ' ', u1.last_name)",
                        'modified_by' => "CONCAT(u2.first_name, ' ', u2.last_name)",
                    ),
                    Branch::getSharedFields('b'),
                    User::getSharedFields('u')
                );

            //  FROM
            case 'body':
                return "
                    FROM
                        $table_name $table_alias
                    INNER JOIN
                        branch b
                            ON
                                b.branch_id = $table_alias.branch_id
                            AND
                                b.status = 'ENABLED'
                    LEFT JOIN
                        user u
                            ON
                                u.user_id = $table_alias.user_id
                    LEFT JOIN
                        user u1
                            ON
                                u1.user_id = $table_alias.created_by
                    LEFT JOIN
                        user u2
                            ON
                                u2.user_id = $table_alias.modified_by";

            //  WHERE
            case 'filter':
                return
                    "(
                        $table_alias.branch_id = '" . CurrentRequest::getTargetBranchId() . "'
                    AND
                        b.parent_branch_id = '" . CurrentRequest::getTargetParentBranchId() . "'
                    )";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.username",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.username" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array (
            'roles' => array (
                'model' => '\aurora\model\UserRole',
                'key' => 'uc_user_credential_id',
            )
        );
    }

    /**
     * Public Functions
    */

    public function login($parameters = array()) {
        $table_alias = $this::TABLE_ALIAS;

        // Get parameters
        if (empty($parameters)) {
            $properties = (array) json_decode(Slim::getInstance()->request()->getBody());
        }

        // Read parameters
        $branch_id = (string) get_value_from_parameter($properties, 'branch', '');
        $username = (string) get_value_from_parameter($properties, 'username', '');
        $password = (string) get_value_from_parameter($properties, 'password', '');

        // SQL query
        $sql = "
            SELECT
                $table_alias.password_salt password_salt,
                $table_alias.hashed_password hashed_password,
            "
            . $this->convertToSqlColumns($this::getQuery('fields'))
            . $this::getQuery('body')
            . "
            WHERE
                $table_alias.username = :username
            AND
                $table_alias.branch_id = :branch_id
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('username', $username);
        $stmt->bindParam('branch_id', $branch_id);

        // Execute query
        $data = $this->fetch($stmt);

        if (empty($data)) {
            deliver_data_response(200, 'Login Failed', $data);
            exit;
        }

        $hashed_password = crypt($password,'$5$' . $data->password_salt);

        if ($hashed_password != $data->hashed_password) {
            $data = null;
        } else {
            unset($data->password_salt);
            unset($data->hashed_password);
        }

        if (empty($data)) {
            deliver_data_response(200, 'Login Failed', $data);
            exit;
        }

        $access = $this->getAccessToken($data->u_user_id);

        if (array_key_exists('access_token', $access)) {
            $data->access_token = $access['access_token'];
        }
        if (array_key_exists('token_expiry', $access)) {
            $data->token_expiry = $access['token_expiry'];
        }

        // Add child data
        foreach ($this::getChildDataSettings() as $code => $model) {
            $this->getChildData($data, $parameters, $code);
        }

        return $data;
    }

    public function getAccessToken($user_id) {
         // SQL query
        $sql = "
            SELECT
                uat.access_token,
                uat.token_expiry
            FROM
                user_access_token uat
            INNER JOIN
                user u
                    ON
                        u.user_id = uat.user_id
                    AND
                        u.branch_id = :branch_id
            INNER JOIN
                branch b
                    ON
                        b.branch_id = u.branch_id
                    AND
                        b.status = 'ENABLED'
            WHERE
                uat.user_id = :user_id
            AND
                uat.token_expiry > :today
            LIMIT 1";

        // Update fields for parameter binding
        $today  = date('Y-m-d H:i:s.u');

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('user_id', $user_id);
        $stmt->bindParam('today', $today);
        $stmt->bindParam('branch_id', CurrentRequest::getTargetBranchId());

        // Execute query
        $data = $this->fetch($stmt);

        if (empty($data)) {
            return $this->createAccessToken($user_id);
        } else {
            $access = array(
                'access_token' => $data->access_token,
                'token_expiry' => $data->token_expiry,
            );

            return $access;
        }
    }

    public function getAccessByToken($access_token) {
        // SQL query
        $sql = "
            SELECT
                ral.target_branch_id,
                ral.module_id,
                ral.access_code
            FROM
                user_access_token uat
            INNER JOIN
                user u
                    ON
                        u.user_id = uat.user_id
            INNER JOIN
                user_credential uc
                    ON
                        uc.user_id = u.user_id
            INNER JOIN
                user_role_link url
                    ON
                        url.user_credential_id = uc.user_credential_id
            INNER JOIN
                role_access_link ral
                    ON
                        ral.role_id = url.role_id
            INNER JOIN
                branch b
                    ON
                        b.branch_id = u.branch_id
                    AND
                        b.status = 'ENABLED'
            WHERE
                access_token = :access_token
            AND
                token_expiry > :today
            LIMIT 1";

        // Update fields for parameter binding
        $today  = date('Y-m-d H:i:s.u');

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('access_token', $access_token);
        $stmt->bindParam('today', $today);

        // Execute query
        return $this->fetch($stmt);
    }

    public function getUserByToken($access_token) {
        // SQL query
        $sql = "
            SELECT
                u.user_id,
                uc.user_credential_id,
                b.branch_id,
                b.parent_branch_id
            FROM
                user_access_token uat
            INNER JOIN
                user u
                    ON
                        u.user_id = uat.user_id
            INNER JOIN
                user_credential uc
                    ON
                        uc.user_id = u.user_id
            INNER JOIN
                branch b
                    ON
                        b.branch_id = u.branch_id
                    AND
                        b.status = 'ENABLED'
            WHERE
                access_token = :access_token
            AND
                token_expiry > :today
            LIMIT 1";

        // Update fields for parameter binding
        $today  = date('Y-m-d H:i:s.u');

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('access_token', $access_token);
        $stmt->bindParam('today', $today);

        // Execute query
        $data = $this->fetch($stmt);

        if (empty($data)) {
            deliver_error_response(401, 40101, 'Invalid access token.');
        }

        return $data;
    }

    public function getCredentialsByServerId($server_id) {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;

        // Get parameters
        $parameters = (array) \Slim\Slim::getInstance()->request()->params();

        // Read parameters
        $per_page = (int) get_value_from_parameter($parameters, 'per_page', 0);
        $page = (int) get_value_from_parameter($parameters, 'page', 0);
        $keywords = (string) get_value_from_parameter($parameters, 'keywords', '');
        $fields = (string) get_value_from_parameter($parameters, 'fields', '');
        $sort_by = (string) get_value_from_parameter($parameters, 'sort_by', '');

        // Make sure id is always included
        if (!empty($fields)) {
            $fields = "$primary_key," . $fields;
        }

        // SQL query
        $sql = "
            SELECT "
            . $this->convertToSqlColumns($this::getQuery('fields'), $fields)
            . $this::getQuery('body')
            . "
            WHERE
                b.server_id = :server_id "
            . $this->convertToSQLTextSearch($this::getQuery('search'), $keywords, 'AND')
            . $this->convertToCustomSearch($this::getQuery('fields'), $parameters, 'AND')
            . $this->convertToSqlOrder($this::getQuery('fields'), $this::getQuery('order'), $sort_by)
            . $this->convertToSqlPaging($per_page, $page);

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('server_id', $server_id);

        // Execute query
        $data = $this->fetchAll($stmt);

        // Add child data
        foreach ($this::getChildDataSettings() as $code => $model) {
            $this->getChildData($data, $parameters, $code);
        }

        return $data;
    }

    /**
     * Private Functions
    */

    private function createAccessToken($user_id) {
        $access_token = create_uuid(CurrentRequest::getUserId());
        $created_on = date('Y-m-d H:i:s.u');
        $token_expiry = date('Y-m-d H:i:s.u', mktime(date("H"), date("i") + SESSION_LENGTH, date("s"), date("m"), date("d"), date("Y")));

        // Get field list from schema
        $schema = $this->getSchema('user_access_token');
        $sql_fields = array ();

        foreach ($schema as $field_name => $field_data) {
            $sql_fields[] = ":$field_name";
        }

        $sql =
            "INSERT
            INTO
                user_access_token
            VALUES
            ("
            . implode(', ', $sql_fields) . "
            )";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('user_id', $user_id);
        $stmt->bindParam('access_token', $access_token);
        $stmt->bindParam('token_expiry', $token_expiry);
        $stmt->bindParam('created_on', $created_on);

        // Execute query
        $this->execute($stmt);

        $access = array(
            'access_token' => $access_token,
            'token_expiry' => $token_expiry,
        );

        if ($stmt->rowCount() > 0) {
            return $access;
        } else {
            return null;
        }
    }

    /**
     * Overrides
     */
} 