<?php
/**
 * Happy Hour Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 8/20/14
 * Time: 4:53 PM
 */

namespace aurora\model;

class HappyHour
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'happy_hour';
    const PRIMARY_KEY = 'happy_hour_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'happy_hours';
    const MODULE = 'item';

    // Class properties
    public $branch_id = '';
    public $happy_hour_id = '';
    public $happy_hour_name;
    public $multiplier;
    public $constant;
    public $apply_discount_on_modifier;
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

    public function getPropertySettings() {
        $property_settings = array(
            'happy_hour_id' => array(
                'scaffold' => false,
            ),
            'happy_hour_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'happy_hour_name',
                        $this->happy_hour_name,
                    ),
                    'error_message' => 'Happy hour name must be unique.',
                )],
            ),
            'multiplier' => array(
                'required' => true,
                'range' => array (0.00, 9.9999),
            ),
            'constant' => array(
                'required' => true,
                'range' => array (0.00, 999999.9999),
            ),
            'apply_discount_on_modifier' => array(
                'required' => true,
                'upper_case' => true,
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
        $self = new HappyHour();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );;
    }

    protected static function getQuery($query_section) {
        $self = new HappyHour();
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
                    "
                        $table_alias.branch_id = '" . CurrentRequest::getTargetBranchId() . "'
                    ";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.happy_hour_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.happy_hour_name" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array (
            'timings' => array (
                'model' => '\aurora\model\HappyHourTiming',
                'key' => 'hh_happy_hour_id',
            ),
            'items' => array (
                'model' => '\aurora\model\ItemHappyHour',
                'key' => 'hh_happy_hour_id',
            ),
        );
    }

    /**
     * Overrides
     */
} 