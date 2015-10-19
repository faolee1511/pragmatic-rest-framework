<?php
/**
 * Customer Variable Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/1/14
 * Time: 6:29 PM
 */

namespace aurora\model;

class CustomerVariable
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'customer_variable';
    const PRIMARY_KEY = 'customer_variable_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'customer_variables';
    const MODULE = 'customer';

    // Class properties
    public $branch_id = '';
    public $customer_id = '';
    public $customer_variable_id = '';
    public $variable_name = '';
    public $variable_value = '';
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

    protected function getPropertySettings() {
        $property_settings = array(
            'customer_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Customer',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'customer_variable_id' => array(
                'scaffold' => false,
            ),
            'variable_name' => array(
                'required' => true,
                'lower_case' => true,
                'strip_tags' => true,
                'format' => REGEX_VARIABLE_NAME,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'variable_name',
                        $this->variable_name,
                    ),
                    'error_message' => 'Variable name must be unique.',
                )],
            ),
            'variable_value' => array(
                'required' => true,
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

        // Append enum format
        $this->addEnumFormatValidator($this::TABLE_NAME, $property_settings);

        return $property_settings;
    }

    public static function getSharedFields($alias) {
        $self = new CustomerVariable();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new CustomerVariable();
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
                    Customer::getSharedFields('c')
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
                        customer c
                            ON
                                c.customer_id = $table_alias.customer_id
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
                    $table_alias.customer_variable_id != ''
                    ";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.variable_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.variable_name" => 'ASC',
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
} 