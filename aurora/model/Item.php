<?php
/**
 * Item Model & Collection
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 7/19/14
 * Time: 3:49 PM
 */

namespace aurora\model;

class Item
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'item';
    const PRIMARY_KEY = 'item_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'items';
    const MODULE = 'item';

    // Class properties
    public $branch_id = '';
    public $item_id = '';
    public $item_name;
    public $item_code;
    public $item_sku;
    public $description;
    public $unit_of_measure;
    public $item_cost;
    public $item_price;
    public $kitchen_area_id;
    public $preparation_time;
    public $alert_level;
    public $max_stock;
    public $re_order_quantity;
    public $item_purchase_code;
    public $main_supplier_id;
    public $photo_url;
    public $tax_setting;
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
            'item_id' => array(
                'scaffold' => false,
            ),
            'item_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'item_name',
                        $this->item_name,
                    ),
                    'error_message' => 'Item name must be unique.',
                )],
            ),
            'item_code' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'item_code',
                        $this->item_code,
                    ),
                    'error_message' => 'Item code must be unique.',
                )],
            ),
            'item_sku' => array(
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'item_sku',
                        $this->item_sku,
                    ),
                    'error_message' => 'Item code must be unique.',
                )],
            ),
            'description' => array(
                'strip_tags' => true,
            ),
            'unit_of_measure' => array(
                'required' => true,
                'lower_case' => true,
                'format' => REGEX_ALPHA_S,
            ),
            'item_cost' => array(
                'required' => true,
                'format' => REGEX_DECIMAL,
                'range' => array (0.00, 999999.9999),
            ),
            'item_price' => array(
                'required' => true,
                'format' => REGEX_DECIMAL,
                'range' => array (0.00, 999999.9999),
            ),
            'kitchen_area_id' => array(
                'foreign_key' => array (
                    'class' => '\aurora\model\Area',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'preparation_time' => array(
                'required' => true,
                'format' => REGEX_NUMERIC,
                'range' => array (0, 65534),
            ),
            'alert_level' => array(
                'required' => true,
                'format' => REGEX_DECIMAL,
                'range' => array (0.00, 99999999.99),
            ),
            'max_stock' => array(
                'required' => true,
                'format' => REGEX_DECIMAL,
                'range' => array (0.00, 99999999.99),
            ),
            're_order_quantity' => array(
                'required' => true,
                'format' => REGEX_DECIMAL,
                'range' => array (0.00, 99999999.99),
            ),
            'item_purchase_code' => array(
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'item_purchase_code',
                        $this->item_purchase_code,
                    ),
                    'error_message' => 'Item purchase code must be unique.',
                )],
            ),
            'main_supplier_id' => array(
                'foreign_key' => array (
                    'class' => '\aurora\model\Associate',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'photo_url' => array(
                'format' => REGEX_URI,
            ),
            'tax_setting' => array(
                'required' => true,
                'upper_case' => true,
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
        $self = new Item();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new Item();
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
                    Area::getSharedFields('a'),
                    Associate::getSharedFields('assoc')
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
                        area a
                            ON
                                a.area_id = $table_alias.kitchen_area_id
                    LEFT JOIN
                        associate assoc
                            ON
                                assoc.associate_id = $table_alias.main_supplier_id
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
                    "$table_alias.item_name",
                    "$table_alias.item_code",
                    "$table_alias.item_sku",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.item_name" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array (
            'mods' => array (
                'model' => '\aurora\model\ItemModifier',
                'key' => 'i_item_id',
            ),
            'ingredients' => array (
                'model' => '\aurora\model\ItemIngredient',
                'key' => 'i_item_id',
            ),
            'tags' => array (
                'model' => '\aurora\model\ItemTag',
                'key' => 'i_item_id',
            ),
        );
    }

    /**
     * Private Functions
    */

    private function isValidIngredient(
        $item_id,
        $ingredient_id
    ) {
        return false;
    }

    /**
     * Overrides
     */
} 