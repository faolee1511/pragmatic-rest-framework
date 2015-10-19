<?php
/**
 * Module Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 5/10/14
 * Time: 1:17 AM
 */

namespace aurora\model;

class Module
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'custom_module';
    const PRIMARY_KEY = 'module_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'modules';
    const MODULE = 'module';

    // Class properties
    public $branch_id = '';
    public $module_id = '';
    public $name;
    public $description;
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
        return array(
            'module_id' => array(
                'scaffold' => false,
            ),
            'name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'name',
                        $this->name,
                    ),
                    'error_message' => 'Module name must be unique.',
                )],
            ),
            'description' => array(
                'strip_tags' => true,
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
    }

    public static function getSharedFields($alias) {
        $self = new Module();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new Module();
        $table_name = $self::TABLE_NAME;
        $table_alias = $self::TABLE_ALIAS;
        $route = $self::ROUTE;

        switch ($query_section) {
            //  SELECT
            case 'fields':
                return array_merge(
                    $self->getModelProperties($table_alias, '', true)
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
                        user u1
                            ON
                                u1.user_id = $table_alias.created_by
                    LEFT JOIN
                        user u2
                            ON
                                u2.user_id = $table_alias.modified_by";

            //  WHERE
            case 'filter':
                return "
                    $table_alias.branch_id = '" . CurrentRequest::getTargetBranchId() . "'
                    ";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.name",
                    "$table_alias.description",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.name" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array ();
    }

    /**
     * Overrides
     */

    public function create() {
        return null;
    }

    public function update($patch = false) {
        return null;
    }

    public function updateStatus() {
        return null;
    }

    public function delete() {
        return null;
    }

    public function getAll($parameters = array()) {
        // SQL query
        $sql = "
            SELECT "
            . $this->convertToSqlColumns($this::getQuery('fields'))
            . $this::getQuery('body')
            . "
            WHERE "
            . $this::getQuery('filter')
            . $this->convertToSqlOrder($this::getQuery('fields'), $this::getQuery('order'));

        // Start connection
        $stmt = $this->prepare($sql);

        // Execute query
        $data = $this->fetchAll($stmt);
        foreach($data as $key => $value) {
            // Get module_id to be used to create a new array later
            $new_id = $value->module_id;

            // Remove original data object
            unset($data[$key]);

            $access_obj = new ModuleAccess();

            $access_data = $access_obj->getAllChildren(null, 'module_id', "'$value->module_id'");

            foreach($access_data as $access_key => $access_value) {
                $access_data[$access_value->access_code] = $access_value->access_name;
                unset($access_data[$access_key]);
            }

            // Create an access property
            $value->access = (array) $access_data;

            // Create new array
            $data[$new_id] = (array) $value;
        }

        $data = (array) $data;

        $data_core = CurrentRequest::getModuleAccessConfiguration();

        $data_custom = array(
            'group_name' => 'Custom Modules',
            'modules' => $data,
        );

        $data_core['custom'] = $data_custom;

        return $data_core;
    }

    public function getCount($parameters = array()) {
        $data = $this->getAll();

        // Get record count
        $total = 0;
        foreach($data as $value) {
            $total += count($value['modules']);
        }

        return $total;
    }

    public function getById(
        $id,
        $parameters = array()
    ) {
        $data = $this->getAll();

        $search_data = null;

        foreach($data as $value) {

            $module_list = $value['modules'];

            if (array_key_exists($id, $module_list)) {
                $search_data = $module_list[$id];
            }
        }

        // Check and return data
        if (empty($search_data)) {
            deliver_error_response(404, 40401, 'Invalid resource or resource is not accessible to your branch.');
        } else {
             return $search_data;
        }
    }

    public function getAccessibility(
        $id,
        $requesting_branch
    ) {
        $data = $this->getAll();

        $has_access = false;

        foreach($data as $value) {

            $module_list = $value['modules'];

            if (array_key_exists($id, $module_list)) {
                $has_access = true;
            }
        }

        // Check and return data
        return $has_access;
    }
} 