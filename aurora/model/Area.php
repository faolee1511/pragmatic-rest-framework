<?php
/**
 * Area Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 6/13/14
 * Time: 9:27 PM
 */

namespace aurora\model;

class Area
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'area';
    const PRIMARY_KEY = 'area_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'areas';
    const MODULE = 'area';

    // Class properties
    public $branch_id = '';
    public $area_id = '';
    public $area_name;
    public $printer_id;
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
            'area_id' => array(
                'scaffold' => false,
            ),
            'area_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'area_name',
                        $this->area_name,
                    ),
                    'error_message' => 'Area name must be unique.',
                )],
            ),
            'printer_id' => array(
                'foreign_key' => array (
                    'class' => '\aurora\model\Printer',
                    'requesting_branch' => $this->branch_id,
                ),
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
        $self = new Area();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new Area();
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
                    Printer::getSharedFields('p')
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
                        printer p
                            ON
                                p.printer_id = $table_alias.printer_id
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
                    "$table_alias.area_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.area_name" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array (
            'locations' => array (
                'model' => '\aurora\model\Location',
                'key' => 'a_area_id'
            )
        );
    }

    /**
     * Overrides
     */
} 