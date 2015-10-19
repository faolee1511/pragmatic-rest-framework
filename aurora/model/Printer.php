<?php
/**
 * Printer Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/1/14
 * Time: 11:30 AM
 */

namespace aurora\model;

class Printer
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'printer';
    const PRIMARY_KEY = 'printer_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'printers';
    const MODULE = 'printer';

    // Class properties
    public $branch_id = '';
    public $printer_id = '';
    public $printer_name;
    public $data_type;
    public $printer_address;
    public $parameter_name;
    public $printer_type;
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
            'printer_id' => array(
                'scaffold' => false,
            ),
            'printer_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'printer_name',
                        $this->printer_name,
                    ),
                    'error_message' => 'Printer name must be unique.',
                )],
            ),
            'data_type' => array(
                'required' => true,
                'upper_case' => true,
            ),
            'printer_address' => array(
                'format' => REGEX_URI,
            ),
            'parameter_name' => array(
                'required' => true,
                'format' => '/^[a-zA-Z0-9 ._%-\/]*$/',
            ),
            'printer_type' => array(
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
        $self = new Printer();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new Printer();
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
                    "$table_alias.printer_name",
                    "$table_alias.parameter_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.printer_name" => 'ASC',
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