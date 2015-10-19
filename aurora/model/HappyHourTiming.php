<?php
/**
 * Happy Hour Timing & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 8/22/14
 * Time: 1:29 PM
 */

namespace aurora\model;

class HappyHourTiming
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'happy_hour_timing';
    const PRIMARY_KEY = 'happy_hour_timing_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'happy_hour_timings';
    const MODULE = 'item';

    // Class properties
    public $branch_id = '';
    public $happy_hour_id = '';
    public $happy_hour_timing_id = '';
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
            'happy_hour_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\HappyHour',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'happy_hour_timing_id' => array(
                'scaffold' => false,
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
        $self = new HappyHourTiming();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );;
    }

    protected static function getQuery($query_section) {
        $self = new HappyHourTiming();
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
                    HappyHour::getSharedFields('hh')
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
                        happy_hour hh
                            ON
                                hh.happy_hour_id = $table_alias.happy_hour_id
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
                    "hh.happy_hour_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "hh.happy_hour_name" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array (
        );
    }

    /**
     * Overrides
     */
} 