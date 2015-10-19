<?php
/**
 * User Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 5/17/14
 * Time: 12:38 PM
 */

namespace aurora\model;

class User
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'user';
    const PRIMARY_KEY = 'user_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'users';
    const MODULE = 'user';

    // Class properties
    public $branch_id = '';
    public $user_id = '';
    public $department_id = '';
    public $shift_id = '';
    public $designation;
    public $employee_id;
    public $first_name;
    public $last_name;
    public $address;
    public $city;
    public $province;
    public $zip_code;
    public $email;
    public $phone;
    public $phone2;
    public $remarks;
    public $photo_url;
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

        // Get current user
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
                'scaffold' => false,
            ),
            'department_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Department',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'shift_id' => array(
                'foreign_key' => array (
                    'class' => '\aurora\model\Shift',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'designation' => array(
                'required' => true,
                'strip_tags' => true,
            ),
            'employee_id' => array(
                'strip_tags' => true,
            ),
            'first_name' => array(
                'required' => true,
                'format' => REGEX_ALPHANUMERIC_S,
            ),
            'last_name' => array(
                'required' => true,
                'format' => REGEX_ALPHANUMERIC_S,
                'custom' => [array (
                    'method' => 'isUniqueFullName',
                    'parameter' => array (
                        $this->first_name,
                        $this->last_name,
                    ),
                    'error_message' => 'Full name must be unique.',
                )],
            ),
            'address' => array(
                'strip_tags' => true,
            ),
            'city' => array(
                'strip_tags' => true,
            ),
            'province' => array(
                'strip_tags' => true,
            ),
            'zip_code' => array(
                'strip_tags' => true,
            ),
            'email' => array(
                'format' => REGEX_EMAIL,
            ),
            'phone' => array(
                'format' => REGEX_PHONE_NUMBER,
            ),
            'phone2' => array(
                'format' => REGEX_PHONE_NUMBER,
            ),
            'remarks' => array(
                'strip_tags' => true,
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
        $self = new User();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new User();
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
                    Department::getSharedFields('d'),
                    Shift::getSharedFields('s')
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
                        department d
                            ON
                                d.department_id = $table_alias.department_id
                    LEFT JOIN
                        shift s
                            ON
                                s.shift_id = $table_alias.shift_id
                    LEFT JOIN
                        user_credential uc
                            ON
                                uc.user_id = $table_alias.user_id
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
                        b.parent_branch_id = '" . CurrentRequest::getTargetParentBranchId() . "'
                    )";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.first_name",
                    "$table_alias.last_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.first_name" => 'ASC',
                    "$table_alias.last_name" => 'ASC',
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

    public function isUniqueFullName(
        $first_name,
        $last_name
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
                $table_alias.first_name = :first_name
            AND
                $table_alias.last_name = :last_name
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('first_name', $first_name);
        $stmt->bindParam('last_name', $last_name);
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