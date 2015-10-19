<?php
/**
 * Item Tag Model & Collection
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 8/18/14
 * Time: 6:42 PM
 */

namespace aurora\model;


class ItemTag
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'item_tag_link';
    const PRIMARY_KEY = 'item_tag_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'item_tags';
    const MODULE = 'item';

    // Class properties
    public $branch_id = '';
    public $item_tag_id = '';
    public $item_id = '';
    public $tag_id = '';
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
            'item_tag_id' => array(
                'scaffold' => false,
            ),
            'item_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Item',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'tag_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Tag',
                    'requesting_branch' => $this->branch_id,
                ),
                'custom' => [array (
                    'method' => 'isUniqueItemTag',
                    'parameter' => array (
                        $this->item_id,
                        $this->tag_id,
                    ),
                    'error_message' => 'Tag must be unique in an item.',
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
        $self = new ItemTag();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new ItemTag();
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
                    Tag ::getSharedFields('t')
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
                        item i
                            ON
                                i.item_id = $table_alias.item_id
                    LEFT JOIN
                        tag t
                            ON
                                t.tag_id = $table_alias.tag_id
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
                    'i.item_name',
                    't.tag_name',
                );

            //  ORDER
            case 'order':
                return array(
                    'i.item_name' => 'ASC',
                    't.tag_name' => 'ASC',
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

    public function isUniqueItemTag(
        $item_id,
        $tag_id
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
                $table_alias.tag_id = :tag_id
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('item_id', $item_id);
        $stmt->bindParam('tag_id', $tag_id);
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