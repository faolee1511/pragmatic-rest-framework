<?php
/**
 * User Role Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 5/10/14
 * Time: 2:48 AM
 */

namespace aurora\model;

class Role
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'role';
    const PRIMARY_KEY = 'role_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'roles';
    const MODULE = 'role';

    // Class properties
    public $branch_id = '';
    public $role_id = '';
    public $role_name;
    public $is_public;
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
            'role_id' => array(
                'scaffold' => false,
            ),
            'role_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'role_name',
                        $this->role_name,
                    ),
                    'error_message' => 'User role name must be unique.',
                )],
            ),
            'is_public' => array(
                'required' => true,
                'upper_case' => true,
                'shared' => false,
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
        $self = new Role();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new Role();
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
                    Branch::getSharedFields('b')
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
                return
                    "(
                        $table_alias.branch_id = '" . CurrentRequest::getTargetBranchId() . "'
                    OR
                        (
                            $table_alias.branch_id = '" . CurrentRequest::getTargetParentBranchId() . "'
                        AND
                            $table_alias.is_public = 'Y'
                        )
                    )";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.role_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.role_name" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array (
            'access' => array (
                'model' => '\aurora\model\RoleAccess',
                'key' => 'r_role_id',
            ),
            'users' => array (
                'model' => '\aurora\model\UserRole',
                'key' => 'r_role_id',
            ),
        );
    }

    /**
     * Overrides
     */

    public function delete() {
        parent::delete();
        $obj = new RoleAccess();
        $obj->deleteBatch(array('branch_id' => $this->branch_id, 'role_id' => $this->role_id));
    }
} 