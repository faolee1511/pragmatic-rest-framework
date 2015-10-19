<?php
/**
 * Special Turn Time Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/1/14
 * Time: 4:14 PM
 */

namespace aurora\model;

class SpecialTurnTime
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'special_turn_time';
    const PRIMARY_KEY = 'special_turn_time_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'special_turn_times';
    const MODULE = 'special_turn_time';

    // Class properties
    public $branch_id = '';
    public $special_turn_time_id = '';
    public $day_of_week;
    public $turn_time;
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
            'special_turn_time_id' => array(
                'scaffold' => false,
            ),
            'day_of_week' => array(
                'required' => true,
                'format' => REGEX_NUMERIC,
                'range' => array (0, 6),
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'day_of_week',
                        $this->day_of_week,
                    ),
                    'error_message' => 'Day of week must be unique.',
                )],
            ),
            'turn_time' => array(
                'required' => true,
                'format' => REGEX_NUMERIC,
                'range' => array (1, 65534),
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
        $self = new SpecialTurnTime();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new SpecialTurnTime();
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
                    "$table_alias.day_of_week",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.day_of_week" => 'ASC',
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