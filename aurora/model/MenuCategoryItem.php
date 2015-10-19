<?php
/**
 * Menu Category Item Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/22/14
 * Time: 12:51 AM
 */

namespace aurora\model;

class MenuCategoryItem
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'menu_category_item_link';
    const PRIMARY_KEY = 'menu_category_item_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'menu_category_items';
    const MODULE = 'menu';

    // Class properties
    public $branch_id = '';
    public $menu_category_item_id = '';
    public $menu_category_id = '';
    public $item_id = '';
    public $priority_level;
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
            'menu_category_item_id' => array(
                'scaffold' => false,
            ),
            'menu_category_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\MenuCategory',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'item_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Item',
                    'requesting_branch' => $this->branch_id,
                ),
                'custom' => [array (
                    'method' => 'isUniqueCategoryItem',
                    'parameter' => array (
                        $this->menu_category_id,
                        $this->item_id,
                    ),
                    'error_message' => 'Item must be unique inside a menu category.',
                )],
            ),
            'priority_level' => array(
                'scaffold' => false,
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
        $self = new MenuCategoryItem();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new MenuCategoryItem();
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
                    MenuCategory::getSharedFields('mc'),
                    Item::getSharedFields('i')
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
                        menu_category mc
                            ON
                                mc.menu_category_id = $table_alias.menu_category_id
                    LEFT JOIN
                        item i
                            ON
                                i.item_id = $table_alias.item_id
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
                    'mc.menu_category_name',
                    'i.item_name',
                    'i.item_code',
                    'i.item_sku',
                );

            //  ORDER
            case 'order':
                return array(
                    'mc.menu_category_name' => 'ASC',
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

    public function isUniqueCategoryItem(
        $menu_category_id,
        $item_id
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
                $table_alias.menu_category_id = :menu_category_id
            AND
                $table_alias.item_id = :item_id
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('menu_category_id', $menu_category_id);
        $stmt->bindParam('item_id', $item_id);
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
        $custom_filter = '';
        $priority_level_limit = $this->getNextPriorityLevel($this->branch_id, $custom_filter);
        $this->pushPriorityLevels($this->priority_level, $priority_level_limit, $custom_filter);
        parent::delete();
    }
} 