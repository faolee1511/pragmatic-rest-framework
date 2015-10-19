<?php
/**
 * Role Access Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 5/31/14
 * Time: 2:19 PM
 */

namespace aurora\model;

class RoleAccess
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'role_access_link';
    const PRIMARY_KEY = 'role_access_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'role_accesses';
    const MODULE = 'role';

    // Class properties
    public $branch_id = '';
    public $role_access_id = '';
    public $role_id = '';
    public $module_id = '';
    public $access_code = '';
    public $target_branch_id = '';
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
            'role_access_id' => array(
                'scaffold' => false,
            ),
            'role_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Role',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'module_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\ModuleAccess',
                    'requesting_branch' => $this->branch_id,
                ),
                'custom' => [array (
                    'method' => 'isUniqueModule',
                    'parameter' => array (
                        $this->role_id,
                        $this->module_id,
                        $this->target_branch_id,
                    ),
                    'error_message' => 'Module already existing for this target branch and role.',
                )],
                'visible' => true,
                'shared' => true,
            ),
            'access_code' => array(
                'required' => true,
                'custom' => [array (
                    'method' => 'isExistingAccessCode',
                    'parameter' => array (
                        $this->module_id,
                        $this->access_code,
                    ),
                    'error_message' => 'Foreign key is not accessible to the requesting branch.',
                )],
            ),
            'target_branch_id' => array(
                'foreign_key' => array (
                    'class' => '\aurora\model\Branch',
                    'requesting_branch' => $this->branch_id,
                ),
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
        $self = new RoleAccess();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new RoleAccess();
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
                    Role::getSharedFields('r'),
                    Branch::getSharedFields('tb')
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
                    INNER JOIN
                        role r
                            ON
                                r.role_id = $table_alias.role_id
                    LEFT JOIN
                        branch tb
                            ON
                                tb.branch_id = $table_alias.target_branch_id
                    LEFT JOIN
                        user u1
                            ON
                                u1.user_id = $table_alias.created_by
                    LEFT JOIN
                        user u2
                            ON
                                u2.user_id = $table_alias.created_by";

            //  WHERE
            case 'filter':
                return
                    "
                    $table_alias.branch_id = '" . CurrentRequest::getTargetBranchId() . "'
                    ";

            //  SEARCH
            case 'search':
                return array(
                    'r.role_name',
                );

            //  ORDER
            case 'order':
                return array(
                    'r.role_name' => 'ASC',
                    "$table_alias.module_id" => 'ASC',
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
     * Public functions
     */

    public function isUniqueModule(
        $role_id,
        $module_id,
        $target_branch_id
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
                $table_alias.branch_id = :branch_id
            AND
                $table_alias.$primary_key != :$primary_key
            AND
                $table_alias.role_id = :role_id
            AND
                $table_alias.module_id = :module_id
            AND
                $table_alias.target_branch_id = :target_branch_id
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('role_id', $role_id);
        $stmt->bindParam('module_id', $module_id);
        $stmt->bindParam('target_branch_id', $target_branch_id);
        $stmt->bindParam('branch_id', $this->branch_id);

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return empty($data);
    }

    public function isExistingAccessCode(
        $module_id,
        $access_code
    ) {
        $obj = new Module();

        $data = $obj->getAll();

        $module_obj = array();

        foreach($data as $module_group) {
            $module_list = $module_group['modules'];
            if (array_key_exists($module_id, $module_list)) {
                $module_obj = $module_list[$module_id];
                break;
            }
        }

        if (empty($module_obj)) {
            return false;
        }
        return array_key_exists($access_code, $module_obj['access']);
    }

    /**
     * Overrides
     */

} 