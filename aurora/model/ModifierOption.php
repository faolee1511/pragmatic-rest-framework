<?php
/**
 * Modifier Option Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 7/21/14
 * Time: 10:34 PM
 */

namespace aurora\model;

class ModifierOption
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'modifier_option';
    const PRIMARY_KEY = 'modifier_option_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'modifier_options';
    const MODULE = 'item';

    // Class properties
    public $branch_id = '';
    public $modifier_id = '';
    public $modifier_option_id = '';
    public $modifier_option_name;
    public $print_label;
    public $price_change;
    public $follow_up_modifier_id;
    public $photo_url;
    public $priority_level;
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
            'modifier_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Modifier',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'modifier_option_id' => array(
                'scaffold' => false,
            ),
            'modifier_option_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'modifier_option_name',
                        $this->modifier_option_name,
                    ),
                    'error_message' => 'Item modifier option name must be unique.',
                )],
            ),
            'print_label' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
            ),
            'price_change' => array(
                'required' => true,
                'format' => REGEX_DECIMAL,
                'range' => array (-999999.9999, 999999.9999),
            ),
            'follow_up_modifier_id' => array(
                'foreign_key' => array (
                    'class' => '\aurora\model\Modifier',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'photo_url' => array(
                'format' => REGEX_URI,
            ),
            'priority_level' => array(
                'scaffold' => false,
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
        $self = new ModifierOption();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );;
    }

    protected static function getQuery($query_section) {
        $self = new ModifierOption();
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
                    Modifier::getSharedFields('m'),
                    Modifier::getSharedFields('fm')
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
                        modifier m
                            ON
                                m.modifier_id = $table_alias.modifier_id
                    LEFT JOIN
                        modifier fm
                            ON
                                fm.modifier_id = $table_alias.follow_up_modifier_id
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
                            m.branch_id = '" . CurrentRequest::getTargetParentBranchId() . "'
                        AND
                            m.is_public = 'Y'
                        )
                    )";

            //  SEARCH
            case 'search':
                return array(
                    "$table_alias.modifier_option_name",
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
        return array ();
    }

    /**
     * Overrides
     */

    public function delete() {
        $custom_filter = 'modifier_id';
        $priority_level_limit = $this->getNextPriorityLevel($this->branch_id, $custom_filter);
        $this->pushPriorityLevels($this->priority_level, $priority_level_limit, $custom_filter);
        parent::delete();
    }
} 