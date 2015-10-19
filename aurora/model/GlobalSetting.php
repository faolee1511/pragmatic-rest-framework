<?php
/**
 * Global Setting Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/9/14
 * Time: 5:50 PM
 */

namespace aurora\model;

class GlobalSetting
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'global_setting';
    const PRIMARY_KEY = 'global_setting_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'global_settings';
    const MODULE = 'global_setting';

    // Class properties
    public $branch_id = '';
    public $global_setting_id = '';
    public $time_offset;
    public $currency_symbol;
    public $max_covers;
    public $turn_time_in_minutes;
    public $quick_transaction_default_type;
    public $table_transaction_default_type;
    public $tab_transaction_default_type;
    public $tax_computation_default_type;
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
            'global_setting_id' => array(
                'scaffold' => false,
            ),
            'time_offset' => array(
                'required' => true,
                'range' => array (-12.00, +14.00),
            ),
            'currency_symbol' => array(
                'required' => true,
            ),
            'max_covers' => array(
                'required' => true,
                'format' => REGEX_NUMERIC,
                'range' => array (1, 65534),
            ),
            'turn_time_in_minutes' => array(
                'required' => true,
                'format' => REGEX_NUMERIC,
                'range' => array (1, 65534),
            ),
            'quick_transaction_default_type' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\TransactionType',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'table_transaction_default_type' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\TransactionType',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'tab_transaction_default_type' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\TransactionType',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'tax_computation_default_type' => array(
                'required' => true,
                'upper_case' => true,
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
        $self = new GlobalSetting();

        return array_merge(
            $self->getModelProperties($alias, $alias, true),
            array(
                $alias . '_currency_symbol' => "UNHEX($alias.currency_symbol)",
            )
        );
    }

    protected static function getQuery($query_section) {
        $self = new GlobalSetting();
        $table_name = $self::TABLE_NAME;
        $table_alias = $self::TABLE_ALIAS;

        switch ($query_section) {
            //  SELECT
            case 'fields':
                return array_merge(
                    $self->getModelProperties($table_alias),
                    array(
                        'currency_symbol' => 'UNHEX(base_table.currency_symbol)',
                        'modified_by' => "CONCAT(u.first_name, ' ', u.last_name)",
                    ),
                    Branch::getSharedFields('b'),
                    TransactionType::getSharedFields('qckt'),
                    TransactionType::getSharedFields('tblt'),
                    TransactionType::getSharedFields('tabt')
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
                        transaction_type qckt
                            ON
                                qckt.transaction_type_id = $table_alias.quick_transaction_default_type
                    LEFT JOIN
                        transaction_type tblt
                            ON
                                tblt.transaction_type_id = $table_alias.table_transaction_default_type
                    LEFT JOIN
                        transaction_type tabt
                            ON
                                tabt.transaction_type_id = $table_alias.tab_transaction_default_type
                    LEFT JOIN
                        user u
                            ON
                                u.user_id = $table_alias.modified_by";

            //  WHERE
            case 'filter':
                return
                    "
                    $table_alias.branch_id = '" . CurrentRequest::getTargetParentBranchId() . "'
                    ";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.global_setting_id",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.global_setting_id" => 'ASC',
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