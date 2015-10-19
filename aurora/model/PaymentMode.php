<?php
/**
 * Payment Mode & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/1/14
 * Time: 1:17 PM
 */

namespace aurora\model;

class PaymentMode
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'payment_mode';
    const PRIMARY_KEY = 'payment_mode_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'payment_modes';
    const MODULE = 'payment_mode';

    // Class properties
    public $branch_id = '';
    public $payment_mode_id = '';
    public $payment_mode_name;
    public $print_label;
    public $payment_mode_type;
    public $post_action;
    public $require_data;
    public $prompt_title;
    public $prompt_input_type;
    public $require_authorization;
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
            'payment_mode_id' => array(
                'scaffold' => false,
            ),
            'payment_mode_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'payment_mode_name',
                        $this->payment_mode_name,
                    ),
                    'error_message' => 'Payment mode name must be unique.',
                )],
            ),
            'print_label' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
            ),
            'payment_mode_type' => array(
                'required' => true,
                'upper_case' => true,
            ),
            'post_action' => array(
                'required' => true,
                'upper_case' => true,
            ),
            'require_data' => array(
                'required' => true,
                'upper_case' => true,
            ),
            'prompt_title' => array(
                'strip_tags' => true,
            ),
            'prompt_input_type' => array(
                'required' => true,
                'upper_case' => true,
            ),
            'require_authorization' => array(
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
        $self = new PaymentMode();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new PaymentMode();
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
                    "$table_alias.payment_mode_code",
                    "$table_alias.payment_mode_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.payment_mode_name" => 'ASC',
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