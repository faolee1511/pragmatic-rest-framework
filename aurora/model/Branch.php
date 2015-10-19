<?php
/**
 * Branch Model & Collection
 *
 * This class is the branch model.
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 3/16/14
 * Time: 6:35 PM
 */

namespace aurora\model;

class Branch
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'branch';
    const PRIMARY_KEY = 'branch_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'branches';
    const MODULE = 'branch';

    // Class properties
    public $branch_id = '';
    public $registration_key;
    public $server_id = '';
    public $host_address;
    public $parent_branch_id;
    public $branch_code;
    public $branch_name;
    public $address;
    public $city;
    public $province;
    public $zip_code;
    public $country;
    public $email;
    public $phone;
    public $phone2;
    public $fax;
    public $website;
    public $general_manager;
    public $photo_url;
    public $status;
    public $created_on;
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

    protected function getPropertySettings() {
        $property_settings = array(
            'branch_id' => array(
                'scaffold' => false,
            ),
            'parent_branch_id' => array(
                'scaffold' => false,
                'foreign_key' => array (
                    'class' => '\aurora\model\Branch',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'host_address' => array(
                'required' => true,
                'lower_case' => true,
                'format' => REGEX_URI,
                'shared' => false,
            ),
            'branch_code' => array(
                'required' => true,
                'strip_tags' => true,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'branch_code',
                        $this->branch_code,
                    ),
                    'error_message' => 'Branch code must be unique.',
                )],
            ),
            'branch_name' => array(
                'required' => true,
                'strip_tags' => true,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'branch_name',
                        $this->branch_name,
                    ),
                    'error_message' => 'Branch name must be unique.',
                )],
            ),
            'address' => array(
                'strip_tags' => true,
                'shared' => false,
            ),
            'city' => array(
                'strip_tags' => true,
                'shared' => false,
            ),
            'province' => array(
                'strip_tags' => true,
                'shared' => false,
            ),
            'zip_code' => array(
                'strip_tags' => true,
                'shared' => false,
            ),
            'country' => array(
                'strip_tags' => true,
                'shared' => false,
            ),
            'email' => array(
                'format' => REGEX_EMAIL,
                'shared' => false,
            ),
            'phone' => array(
                'format' => REGEX_PHONE_NUMBER,
                'shared' => false,
            ),
            'phone2' => array(
                'format' => REGEX_PHONE_NUMBER,
                'shared' => false,
            ),
            'fax' => array(
                'format' => REGEX_PHONE_NUMBER,
                'shared' => false,
            ),
            'website' => array(
                'format' => REGEX_URI,
                'shared' => false,
            ),
            'general_manager' => array(
                'format' => REGEX_ALPHANUMERIC_S,
                'shared' => false,
            ),
            'photo_url' => array(
                'format' => REGEX_URI,
            ),
            'status' => array(
                'scaffold' => false,
                'shared' => true,
            ),
            'created_on' => array(
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
        $self = new Branch();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new Branch();
        $table_name = $self::TABLE_NAME;
        $table_alias = $self::TABLE_ALIAS;

        switch ($query_section) {
            //  SELECT
            case 'fields':
                return array_merge(
                    $self->getModelProperties($table_alias),
                    array(
                        'modified_by' => "CONCAT(u.first_name, ' ', u.last_name)",
                    ),
                    Branch::getSharedFields('pb')
                );

            //  FROM
            case 'body':
                return "
                    FROM
                        $table_name $table_alias
                    LEFT JOIN
                        branch pb
                            ON
                                pb.branch_id = $table_alias.parent_branch_id
                    LEFT JOIN
                        user u
                            ON
                                u.user_id = $table_alias.modified_by";

            //  WHERE
            case 'filter':
                return
                    "(
                            $table_alias.status = 'ENABLED'
                        AND
                            (
                                $table_alias.parent_branch_id = '" . CurrentRequest::getTargetBranchId() . "'
                            OR
                                $table_alias.parent_branch_id = '" . CurrentRequest::getTargetParentBranchId() . "'
                            OR
                                $table_alias.branch_id = '" . CurrentRequest::getTargetParentBranchId() . "'
                            OR
                                $table_alias.branch_id = '" . CurrentRequest::getTargetBranchId() . "'
                            )
                    )";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.branch_name",
                    "$table_alias.branch_code",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.branch_name" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array (
            'child' => array (
                'model' => '\aurora\model\Branch',
                'key' => 'parent_branch_id'
            ),
            'vars' => array (
                'model' => '\aurora\model\BranchVariable',
                'key' => 'b_branch_id'
            ),
            'areas' => array (
                'model' => '\aurora\model\Area',
                'key' => 'b_branch_id'
            ),
            'locations' => array (
                'model' => '\aurora\model\Location',
                'key' => 'b_branch_id'
            ),
            'departments' => array (
                'model' => '\aurora\model\Department',
                'key' => 'b_branch_id'
            ),
            'shifts' => array (
                'model' => '\aurora\model\Shift',
                'key' => 'b_branch_id'
            ),
            'users' => array (
                'model' => '\aurora\model\User',
                'key' => 'b_branch_id'
            ),
            'operating_times' => array (
                'model' => '\aurora\model\OperatingTime',
                'key' => 'b_branch_id'
            ),
            'printers' => array (
                'model' => '\aurora\model\Printer',
                'key' => 'b_branch_id'
            ),
        );
    }

    /**
     * Public Functions
    */

    public function GetBranchesByServerId($server_id) {
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
                base_table.server_id = :server_id
            AND
                base_table.status = 'ENABLED'
            "
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
        $this->getChildData($data, $parameters, 'child');

        return $data;
    }

    /**
     * Overrides
    */

    protected function isUnique(
        $field,
        $value
    ) {
        // Reassign statics for readability
        $table_name = $this::TABLE_NAME;
        $table_alias = $this::TABLE_ALIAS;
        $primary_key = $this::PRIMARY_KEY;

        // SQL query
        $sql ="
            SELECT
                $table_alias.$primary_key
            FROM
                $table_name $table_alias
            WHERE
                $table_alias.$primary_key != :$primary_key
            AND
                $table_alias.$field = :value
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('value', $value);

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return empty($data);
    }

    public function getAccessibility(
        $id,
        $requesting_branch
    ) {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;
        $table_name = $this::TABLE_NAME;

        // SQL query
        $sql ="
            SELECT
                base_table.$primary_key
            FROM
                $table_name base_table
            WHERE
                base_table.$primary_key = :$primary_key
            AND
                base_table.status = 'ENABLED'
            AND
                (
                    base_table.branch_id = :requesting_branch
                OR
                    base_table.parent_branch_id = :requesting_branch
                OR
                    base_table.parent_branch_id = :parent_branch_id
                OR
                    base_table.branch_id = :parent_branch_id
                )
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $id);
        $stmt->bindParam('requesting_branch', $requesting_branch);
        $stmt->bindParam('parent_branch_id', CurrentRequest::getTargetParentBranchId());

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return !empty($data);
    }

    public function create() {
        return null;
    }

    public function updateStatus() {
        return null;
    }

    public function delete() {
        return null;
    }
}