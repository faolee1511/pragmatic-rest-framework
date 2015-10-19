<?php
/**
 * Customer Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/1/14
 * Time: 5:14 PM
 */

namespace aurora\model;

class Customer
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'customer';
    const PRIMARY_KEY = 'customer_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'customers';
    const MODULE = 'customer';

    // Class properties
    public $branch_id = '';
    public $customer_id = '';
    public $customer_code;
    public $title;
    public $first_name;
    public $last_name;
    public $date_of_birth;
    public $address;
    public $city;
    public $province;
    public $zip_code;
    public $alt_address;
    public $alt_city;
    public $alt_province;
    public $alt_zip_code;
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
            'customer_id' => array(
                'scaffold' => false,
            ),
            'customer_code' => array(
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUniqueCustomerCode',
                    'parameter' => array (
                        'customer_code',
                        $this->customer_code,
                    ),
                    'error_message' => 'Customer code name must be unique.',
                )],
            ),
            'title' => array(
                'required' => true,
                'upper_case' => true,
            ),
            'first_name' => array(
                'required' => true,
                'format' => REGEX_ALPHANUMERIC_S,
            ),
            'last_name' => array(
                'required' => true,
                'format' => REGEX_ALPHANUMERIC_S,
            ),
            'date_of_birth' => array(
                'required' => true,
                'format' => REGEX_DATE,
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
            'alt_address' => array(
                'strip_tags' => true,
            ),
            'alt_city' => array(
                'strip_tags' => true,
            ),
            'alt_province' => array(
                'strip_tags' => true,
            ),
            'alt_zip_code' => array(
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
        $self = new Customer();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new Customer();
        $table_name = $self::TABLE_NAME;
        $table_alias = $self::TABLE_ALIAS;

        switch ($query_section) {
            // SELECT
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
                    "
                    $table_alias.customer_id != ''
                    ";

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
        return array (
            'vars' => array (
                'model' => '\aurora\model\CustomerVariable',
                'key' => 'c_customer_id'
            ),
        );
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

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return empty($data);
    }

    public function isUniqueCustomerCode(
        $customer_code
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
                $table_alias.customer_code = :customer_code
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('customer_code', $customer_code);

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return empty($data);
    }

    /**
     * Overrides
     */
} 