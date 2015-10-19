<?php
/**
 * Transaction Type Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 6/14/14
 * Time: 1:13 PM
 */

namespace aurora\model;

class TransactionType
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'transaction_type';
    const PRIMARY_KEY = 'transaction_type_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'transaction_types';
    const MODULE = 'transaction_type';

    // Class properties
    public $branch_id = '';
    public $transaction_type_id = '';
    public $transaction_type_name;
    public $print_label;
    public $tax_setting;
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
            'transaction_type_id' => array(
                'scaffold' => false,
            ),
            'transaction_type_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'transaction_type_name',
                        $this->transaction_type_name,
                    ),
                    'error_message' => 'Transaction type name must be unique.',
                )],
            ),
            'print_label' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
            ),
            'tax_setting' => array(
                'required' => true,
                'upper_case' => true,
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
        $self = new TransactionType();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new TransactionType();
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
                    "$table_alias.transaction_type_name",
                    "$table_alias.print_label",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.transaction_type_name" => 'ASC',
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
} 