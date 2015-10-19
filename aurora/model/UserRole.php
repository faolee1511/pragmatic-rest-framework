<?php
/**
 * User Role Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 5/31/14
 * Time: 11:46 AM
 */

namespace aurora\model;

class UserRole
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'user_role_link';
    const PRIMARY_KEY = 'user_role_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'user_roles';
    const MODULE = 'role';

    // Class properties
    public $branch_id = '';
    public $user_role_id = '';
    public $user_credential_id = '';
    public $role_id = '';
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
            'user_role_id' => array(
                'scaffold' => false,
            ),
            'user_credential_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\UserCredential',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'role_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Role',
                    'requesting_branch' => $this->branch_id,
                ),
                'custom' => [array (
                    'method' => 'isUniqueRole',
                    'parameter' => array (
                        $this->user_credential_id,
                        $this->role_id,
                    ),
                    'error_message' => 'Role already existing in credential.',
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

        // Append enum format
        $this->addEnumFormatValidator($this::TABLE_NAME, $property_settings);

        return $property_settings;
    }

    public static function getSharedFields($alias) {
        $self = new UserRole();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new UserRole();
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
                    UserCredential::getSharedFields('uc'),
                    Role::getSharedFields('r')
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
                        user_credential uc
                            ON
                                uc.user_credential_id = $table_alias.user_credential_id
                    INNER JOIN
                        role r
                            ON
                                r.role_id = $table_alias.role_id
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
                    "
                    $table_alias.branch_id = '" . CurrentRequest::getTargetBranchId() . "'
                    ";

            //  SEARCH
            case 'search':
                return array(
                    'r.role_name',
                    'uc.username',
                    'uc.pin_code',
                );

            //  ORDER
            case 'order':
                return array(
                    'uc.username' => 'ASC',
                    'r.role_name' => 'ASC',
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

    public function isUniqueRole(
        $user_credential_id,
        $role_id
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
                $table_alias.user_credential_id = :user_credential_id
            AND
                $table_alias.role_id = :role_id
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('user_credential_id', $user_credential_id);
        $stmt->bindParam('role_id', $role_id);
        $stmt->bindParam('branch_id', $this->branch_id);

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return empty($data);
    }

    /**
     * Overrides
     */

} 