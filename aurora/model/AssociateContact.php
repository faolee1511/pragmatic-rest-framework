<?php
/**
 * Associate Contact Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 6/14/14
 * Time: 6:16 PM
 */

namespace aurora\model;

class AssociateContact
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'associate_contact_link';
    const PRIMARY_KEY = 'associate_contact_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'associate_contacts';
    const MODULE = 'associate';

    // Class properties
    public $branch_id = '';
    public $associate_contact_id = '';
    public $associate_id = '';
    public $contact_id = '';
    public $is_primary_contact;
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
            'associate_contact_id' => array(
                'scaffold' => false,
            ),
            'associate_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Associate',
                    'requesting_branch' => $this->branch_id,
                ),
            ),
            'contact_id' => array(
                'required' => true,
                'foreign_key' => array (
                    'class' => '\aurora\model\Contact',
                    'requesting_branch' => $this->branch_id,
                ),
                'custom' => [array (
                    'method' => 'isUniqueContact',
                    'parameter' => array (
                        $this->associate_id,
                        $this->contact_id,
                    ),
                    'error_message' => 'Contact already existing for this associate.',
                )],
            ),
            'is_primary_contact' => array(
                'required' => true,
                'upper_case' => true,
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
        $self = new AssociateContact();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new AssociateContact();
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
                    Associate::getSharedFields('a'),
                    Contact::getSharedFields('c')
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
                        associate a
                            ON
                                a.associate_id = $table_alias.associate_id
                    INNER JOIN
                        contact c
                            ON
                                c.contact_id = $table_alias.contact_id
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
                    'c.first_name',
                    'c.last_name',
                );

            //  ORDER
            case 'order':
                return array(
                    'a.associate_name' => 'ASC',
                    'c.first_name' => 'ASC',
                    'c.last_name' => 'ASC',
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

    public function isUniqueContact(
        $associate_id,
        $contact_id
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
                $table_alias.associate_id = :associate_id
            AND
                $table_alias.contact_id = :contact_id
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('associate_id', $associate_id);
        $stmt->bindParam('contact_id', $contact_id);
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