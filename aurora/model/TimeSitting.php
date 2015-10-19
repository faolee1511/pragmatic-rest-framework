<?php
/**
 * Time Sitting Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 8/19/14
 * Time: 4:18 PM
 */

namespace aurora\model;

class TimeSitting
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'time_sitting';
    const PRIMARY_KEY = 'time_sitting_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'time_sittings';
    const MODULE = 'item';

    // Class properties
    public $branch_id = '';
    public $time_sitting_id = '';
    public $time_sitting_name;
    public $sun;
    public $mon;
    public $tue;
    public $wed;
    public $thu;
    public $fri;
    public $sat;
    public $start_time;
    public $end_time;
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
            'time_sitting_id' => array(
                'scaffold' => false,
            ),
            'time_sitting_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'time_sitting_name',
                        $this->time_sitting_name,
                    ),
                    'error_message' => 'Time sitting name must be unique.',
                )],
            ),
            'sun' => array(
                'required' => true,
                'shared' => false,
            ),
            'mon' => array(
                'required' => true,
                'shared' => false,
            ),
            'tue' => array(
                'required' => true,
                'shared' => false,
            ),
            'wed' => array(
                'required' => true,
                'shared' => false,
            ),
            'thu' => array(
                'required' => true,
                'shared' => false,
            ),
            'fri' => array(
                'required' => true,
                'shared' => false,
            ),
            'sat' => array(
                'required' => true,
                'shared' => false,
            ),
            'start_time' => array(
                'required' => true,
                'format' => REGEX_TIME,
            ),
            'end_time' => array(
                'required' => true,
                'format' => REGEX_TIME,
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
        $self = new TimeSitting();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );;
    }

    protected static function getQuery($query_section) {
        $self = new TimeSitting();
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
                    "$table_alias.time_sitting_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.time_sitting_name" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array (
            'items' => array (
                'model' => '\aurora\model\ItemTimeSitting',
                'key' => 'ts_time_sitting_id',
            )
        );
    }

    /**
     * Overrides
     */
} 