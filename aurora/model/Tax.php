<?php
/**
 * Tax Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/11/14
 * Time: 11:49 AM
 */

namespace aurora\model;

class Tax
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'tax';
    const PRIMARY_KEY = 'tax_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'taxes';
    const MODULE = 'tax';

    // Class properties
    public $branch_id = '';
    public $tax_profile_id = '';
    public $tax_id = '';
    public $tax_name;
    public $print_label;
    public $multiplier;
    public $constant;
    public $tax_type;
    public $computation_type;
    public $priority_level;
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
            'tax_profile_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\TaxProfile',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'tax_id' => array(
                'scaffold' => false,
            ),
            'tax_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUniqueTaxName',
                    'parameter' => array (
                        $this->tax_profile_id,
                        $this->tax_name,
                    ),
                    'error_message' => 'Tax name must be unique.',
                )],
            ),
            'print_label' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
            ),
            'multiplier' => array(
                'required' => true,
                'range' => array (0.00, 9.9999),
            ),
            'constant' => array(
                'required' => true,
                'range' => array (0.00, 99999999.99),
            ),
            'tax_type' => array(
                'required' => true,
                'upper_case' => true,
            ),
            'computation_type' => array(
                'required' => true,
                'upper_case' => true,
            ),
            'priority_level' => array(
                'scaffold' => false,
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
        $self = new Tax();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new Tax();
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
                    TaxProfile::getSharedFields('tp')
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
                        tax_profile tp
                            ON
                                tp.tax_profile_id = $table_alias.tax_profile_id
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
                            tp.branch_id = '" . CurrentRequest::getTargetParentBranchId() . "'
                        AND
                            tp.is_public = 'Y'
                        )
                    )";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.tax_name",
                    "$table_alias.print_label",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.priority_level" => 'ASC',
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

    public function isUniqueTaxName(
        $tax_profile_id,
        $tax_name
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
                $table_alias.tax_profile_id = :tax_profile_id
            AND
                $table_alias.tax_name = :tax_name
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('tax_profile_id', $tax_profile_id);
        $stmt->bindParam('tax_name', $tax_name);
        $stmt->bindParam('branch_id', $this->branch_id);

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return empty($data);
    }

    /**
     * Overrides
     */

    public function delete() {
        $custom_filter = 'tax_profile_id';
        $priority_level_limit = $this->getNextPriorityLevel($this->branch_id, $custom_filter);
        $this->pushPriorityLevels($this->priority_level, $priority_level_limit, $custom_filter);
        parent::delete();
    }
} 