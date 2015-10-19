<?php
/**
 * Created by PhpStorm.
 * User: Pao
 * Date: 5/22/14
 * Time: 10:16 PM
 */

namespace aurora\model;

class ModuleAccess
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'custom_module_access';
    const PRIMARY_KEY = 'access_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'module_access';
    const MODULE = 'module';

    // Class properties
    public $branch_id = '';
    public $module_id = '';
    public $access_id = '';
    public $access_name;
    public $access_code;
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
                'foreign_key' => array (
                    'class' => '\aurora\model\Module',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'access_id' => array(
                'scaffold' => false,
            ),
            'access_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'access_name',
                        $this->access_name,
                    ),
                    'error_message' => 'Access name must be unique.',
                )],
            ),
            'access_code' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'access_code',
                        $this->access_code,
                    ),
                    'error_message' => 'Access code must be unique.',
                )],
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
        $self = new ModuleAccess();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new ModuleAccess();
        $table_name = $self::TABLE_NAME;
        $table_alias = $self::TABLE_ALIAS;

        switch ($query_section) {
            //  SELECT
            case 'fields':
                return array_merge(
                    $self->getModelProperties($table_alias)
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
                    "$table_alias.access_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.module_id" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array();
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

    public function getAccessibility(
        $id,
        $requesting_branch
    ) {
        return true;
    }
} 