<?php
/**
 * Menu Group Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/15/14
 * Time: 5:10 PM
 */

namespace aurora\model;

class MenuGroup
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'menu_group';
    const PRIMARY_KEY = 'menu_group_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'menu_groups';
    const MODULE = 'menu';

    // Class properties
    public $branch_id = '';
    public $menu_group_id = '';
    public $menu_group_name;
    public $priority_level;
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
            'menu_group_id' => array(
                'scaffold' => false,
            ),
            'menu_group_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'menu_group_name',
                        $this->menu_group_name,
                    ),
                    'error_message' => 'Menu group name must be unique.',
                )],
            ),
            'priority_level' => array(
                'scaffold' => false,
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
        $self = new MenuGroup();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new MenuGroup();
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
                    "$table_alias.menu_group_name",
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
        return array (
            'categories' => array (
                'model' => '\aurora\model\MenuGroupCategory',
                'key' => 'mg_menu_group_id',
            )
        );
    }

    /**
     * Overrides
     */

    public function delete() {
        $custom_filter = '';
        $priority_level_limit = $this->getNextPriorityLevel($this->branch_id, $custom_filter);
        $this->pushPriorityLevels($this->priority_level, $priority_level_limit, $custom_filter);
        parent::delete();
    }
} 