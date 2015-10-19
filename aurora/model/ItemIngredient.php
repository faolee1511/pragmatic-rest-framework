<?php
/**
 * Item Ingredient Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/22/14
 * Time: 3:54 PM
 */

namespace aurora\model;

class ItemIngredient
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'item_ingredient_link';
    const PRIMARY_KEY = 'item_ingredient_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'item_ingredients';
    const MODULE = 'item';

    // Class properties
    public $branch_id = '';
    public $item_ingredient_id = '';
    public $item_id = '';
    public $ingredient_id = '';
    public $quantity;
    public $wastage_multiplier;
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
            'item_ingredient_id' => array(
                'scaffold' => false,
            ),
            'item_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Item',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'ingredient_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Item',
                    'requesting_branch' => $this->branch_id,
                ),
                'custom' => [array (
                    'method' => 'isUniqueIngredientItem',
                    'parameter' => array (
                        $this->item_id,
                        $this->ingredient_id,
                    ),
                    'error_message' => 'Ingredient already existing in item ingredients.',
                ),
                array (
                'method' => 'isValidIngredientItem',
                'parameter' => array (
                    $this->item_id,
                    $this->ingredient_id,
                ),
                'error_message' => 'Ingredient is invalid.',
                )],
            ),
            'quantity' => array(
                'required' => true,
                'format' => REGEX_DECIMAL,
                'range' => array (-999999.9999, 999999.9999),
            ),
            'wastage_multiplier' => array(
                'required' => true,
                'format' => REGEX_DECIMAL,
                'range' => array (1.00, 999999.9999),
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
        $self = new ItemIngredient();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new ItemIngredient();
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
                    Item::getSharedFields('i'),
                    Item::getSharedFields('ing')
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
                        item i
                            ON
                                i.item_id = $table_alias.item_id
                    INNER JOIN
                        item ing
                            ON
                                ing.item_id = $table_alias.ingredient_id
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
                            i.branch_id = '" . CurrentRequest::getTargetParentBranchId() . "'
                        AND
                            i.is_public = 'Y'
                        )
                    )";

            //  SEARCH
            case 'search':
                return array(
                    'i.item_name',
                    'i.item_code',
                    'i.item_sku',
                    'ing.item_name',
                    'ing.item_code',
                    'ing.item_sku',
                );

            //  ORDER
            case 'order':
                return array(
                    'i.item_name' => 'ASC',
                    'ing.item_name' => 'ASC',
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

    public function isUniqueIngredientItem(
        $item_id,
        $ingredient_id
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
                $table_alias.item_id = :item_id
            AND
                $table_alias.ingredient_id = :ingredient_id
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('item_id', $item_id);
        $stmt->bindParam('ingredient_id', $ingredient_id);
        $stmt->bindParam('branch_id', $this->branch_id);

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return empty($data);
    }

    public function isValidIngredientItem(
        $item_id,
        $ingredient_id
    ) {
        if ($item_id == $ingredient_id) {
            return false;
        }

        $obj = new Item();

        $data = $obj->getAll(
            array(
                'fields' => 'item_id',
                '_ingredients' => 'show(fields=ing_item_id,ing_alert_level,quantity,wastage_multiplier)'
            )
        );

        foreach ($data as $key => $value) {
            if ($value->ingredients_count <= 0) {
                $data[$value->item_id] = null;
            } else {
                $ingredient_array = array();
                foreach ($value->ingredients as $ingredient_value) {
                    $ingredient_array[] = array(
                        'ingredient' => $ingredient_value->ing_item_id,
                        'alert_level' => $ingredient_value->ing_alert_level,
                        'quantity' => $ingredient_value->quantity,
                        'wastage_multiplier' => $ingredient_value->wastage_multiplier,
                    );
                }
                $data[$value->item_id] = $ingredient_array;
            }
            unset($data[$key]);
        }

        return $this->checkIngredient($data, $item_id, $ingredient_id);
    }

    private function checkIngredient(
        array $data,
        $item_id,
        $ingredient_id
    ) {
        if ($item_id == $ingredient_id) {
            return false;
        }

        if ($data[$ingredient_id] == null) {
            return true;
        }

        foreach ($data[$ingredient_id] as $ingredient_array) {
            if (!$this->checkIngredient($data, $item_id, $ingredient_array['ingredient'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Overrides
     */
} 