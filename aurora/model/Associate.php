<?php
/**
 * Associate Model & Collection
 *
 * Created by PhpStorm.
 * User: Carnage
 * Date: 6/14/14
 * Time: 5:12 PM
 */

namespace aurora\model;

class Associate
    extends DbContextAbstract
{
    // Class constants
    const TABLE_NAME = 'associate';
    const PRIMARY_KEY = 'associate_id';
    const TABLE_ALIAS = 'base_table';
    const ROUTE = 'associates';
    const MODULE = 'associate';

    // Class properties
    public $branch_id = '';
    public $associate_id = '';
    public $associate_name;
    public $associate_code;
    public $associate_type;
    public $physical_address;
    public $physical_city;
    public $physical_province;
    public $physical_zip_code;
    public $mailing_address;
    public $mailing_city;
    public $mailing_province;
    public $mailing_zip_code;
    public $email;
    public $phone;
    public $phone2;
    public $fax;
    public $remarks;
    public $photo_url;
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

        // Get current user
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
            'associate_id' => array(
                'scaffold' => false,
            ),
            'associate_name' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'associate_name',
                        $this->associate_name,
                    ),
                    'error_message' => 'Associate code name must be unique.',
                )],
            ),
            'associate_code' => array(
                'required' => true,
                'format' => REGEX_GENERIC,
                'custom' => [array (
                    'method' => 'isUnique',
                    'parameter' => array (
                        'associate_code',
                        $this->associate_code,
                    ),
                    'error_message' => 'Associate code name must be unique.',
                )],
            ),
            'associate_type' => array(
                'required' => true,
                'upper_case' => true,
            ),
            'physical_address' => array(
                'strip_tags' => true,
            ),
            'physical_city' => array(
                'strip_tags' => true,
            ),
            'physical_province' => array(
                'strip_tags' => true,
            ),
            'physical_zip_code' => array(
                'strip_tags' => true,
            ),
            'mailing_address' => array(
                'strip_tags' => true,
            ),
            'mailing_city' => array(
                'strip_tags' => true,
            ),
            'mailing_province' => array(
                'strip_tags' => true,
            ),
            'mailing_zip_code' => array(
                'strip_tags' => true,
            ),
            'email' => array(
                'format' => REGEX_EMAIL,
            ),
            'phone' => array(
                'format' => REGEX_PHONE_NUMBER,
            ),
            'phone2' => array(
                'format' => REGEX_PHONE_NUMBER,
            ),
            'fax' => array(
                'format' => REGEX_PHONE_NUMBER,
            ),
            'remarks' => array(
                'strip_tags' => true,
            ),
            'photo_url' => array(
                'format' => REGEX_URI,
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
        $self = new Associate();

        return array_merge(
            $self->getModelProperties($alias, $alias, true)
        );
    }

    protected static function getQuery($query_section) {
        $self = new Associate();
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
                    "$table_alias.associate_code",
                    "$table_alias.associate_name",
                );

            //  ORDER
            case 'order':
                return array(
                    "$table_alias.associate_name" => 'ASC',
                );

            default:
                return null;
        }
    }

    protected static function getChildDataSettings() {
        // List of allowed child data
        // $code => $model
        return array (
            'contacts' => array (
                'model' => '\aurora\model\AssociateContact',
                'key' => 'a_associate_id',
            )
        );
    }

    /**
     * Overrides
     */

} 