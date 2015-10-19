<?php
/**
 * Tag Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 8/18/14
 * Time: 6:03 PM
 */

namespace aurora\model;

class Tag
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'tag';
    const PRIMARY_KEY = 'tag_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'tags';
    const MODULE = 'item';

    // Class properties
    public $branch_id = '';
    public $tag_id = '';
    public $tag_name;
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
            'tag_id' => array(
                'scaffold' => false,
            ),
            'tag_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'tag_name',
                        $this->tag_name,
                    ),
                    'error_message' => 'Tag must be unique.',
                )],
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
        $self = new Tag();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );;
    }

    protected static function getQuery($query_section) {
        $self = new Tag();
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
                    "$table_alias.tag_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.tag_name" => 'ASC',
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
                'model' => '\aurora\model\ItemTag',
                'key' => 't_tag_id',
            ),
        );
    }

    /**
     * Overrides
     */
} 