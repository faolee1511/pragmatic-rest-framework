<?php
/**
 * Inventory Item Model & Collection
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 7/20/14
 * Time: 7:59 PM
 */

namespace aurora\model;

class InventoryItem
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'inventory_item_link';
    const PRIMARY_KEY = 'inventory_item_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'inventory_items';
    const MODULE = 'inventory';

    // Class properties
    public $branch_id = '';
    public $inventory_item_id = '';
    public $inventory_group_id = '';
    public $item_id = '';
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
            'inventory_item_id' => array(
                'scaffold' => false,
            ),
            'inventory_group_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\InventoryGroup',
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
                    'method' => 'isUniqueInventoryItem',
                    'parameter' => array (
                        $this->inventory_group_id,
                        $this->item_id,
                    ),
                    'error_message' => 'Item already existing in inventory group.',
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
        $self = new InventoryItem();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new InventoryItem();
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
                    InventoryGroup::getSharedFields('ig'),
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
                    INNER JOIN
                        inventory_group ig
                            ON
                                ig.inventory_group_id = $table_alias.inventory_group_id
                    INNER JOIN
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
                    'ig.inventory_group_name',
                    'i.item_name',
                    'i.item_code',
                    'i.item_sku',
                );

            //  ORDER
            case 'order':
                return array(
                    'ig.inventory_group_name' => 'ASC',
                    'i.item_name' => 'ASC',
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

    public function isUniqueInventoryItem(
        $inventory_group_id,
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
                $table_alias.inventory_group_id = :inventory_group_id
            AND
                $table_alias.item_id = :item_id
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('inventory_group_id', $inventory_group_id);
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
} 