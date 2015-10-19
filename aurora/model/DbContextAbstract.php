<?php
/**
 * Abstract Base Model Class
 *
 * This class is used as a blue print for all model class
 *
 * Created by PhpStorm.
 * User: DevMode
 * Date: 3/15/14
 * Time: 2:03 PM
 */

namespace aurora\model;

abstract class DbContextAbstract
    extends BaseUtilityAbstract
{
    // Constants
    const STATUS_ENABLED = 'ENABLED';
    const STATUS_DISABLED = 'DISABLED';

    /**
     * Base class constructor
     */
    public function __construct() {

    }

    /**
     * Base class destructor
     */
    public function __destruct() {

    }

    /**
     * Run validation on class properties
    */
    protected function validateProperties(){
        // Trim string based on table schema
        $this->trimProperties();

        $this->isValid($this::getPropertySettings());
    }

    /**
     * Creates PDO binding of all class properties
     *
     * Will bind only visible properties
     *
     * @parameter reference PDOStatement $stmt PDO Statement object
     */
    protected function bindProperties(&$stmt) {
        foreach (get_object_vars($this) as $prop => $val) {
            if (property_exists($this, $prop)
                &&
                !is_int($prop)
            ) {

                $stmt->bindParam((string) $prop, $this->$prop);
            }
        }
    }

    /**
     * Check if properties parameter matched
     * the all class protected properties
     *
     * @parameter array $properties list of property values
     */
    public function validateParameterCompleteness(array $properties) {
        $error = array();

        foreach ($this::getPropertySettings() as $property => $settings) {

            // Check for scaffolding settings for requirement validation
            if (!array_key_exists($property, $properties)) {
                if (!array_key_exists('scaffold', $settings)
                    ||
                    (
                        array_key_exists('scaffold', $settings)
                        &&
                        $settings['scaffold'] != false
                    )
                ) {

                    // Add error for missing parameter
                    $error[] = array (
                        'parameter' => $property,
                        'errorMessage' => "Missing parameter.",
                    );
                }
            }
        }

        // Throw error if at least one parameter is missing
        if (!empty($error)) {
            deliver_error_response(400, 40002, 'Your data is missing some parameters.', $error);
        }
    }

    /**
     * Clear sub class protected properties
     *
     * Sets all protected properties of a sub class to null
     */
    public function clearProperties() {
        foreach ($this::getPropertySettings() as $property => $settings) {
            if (property_exists($this, $property)
            ) {
                if (!array_key_exists('scaffold', $settings)
                    ||
                    (
                        array_key_exists('scaffold', $settings)
                        &&
                        $settings['scaffold'] != false
                    )
                ) {
                    $this->{$property} = null;
                }

            }
        }
    }

    /**
     * Map array values to sub class protected properties
     *
     * Populate protected properties with values from array
     *
     * @parameter array $properties list of property values
     */
    public function mapProperties(array $properties) {
        $map_count = 0;
        foreach ($this::getPropertySettings() as $property => $settings) {
            if (array_key_exists($property, $properties)
                &&
                property_exists($this, $property)
            ) {

                // Map only scaffold properties
                if (!array_key_exists('scaffold', $settings)
                    ||
                    (
                        array_key_exists('scaffold', $settings)
                        &&
                        $settings['scaffold'] != false
                    )
                ) {
                    $this->{$property} = $properties[$property];
                    ++$map_count;
                }
            }
        }

        return $map_count;
    }

    /**
     * Trim string's maximum length to avoid database error
     *
     * @parameter string $table_name
     */
    protected function trimProperties() {
        // Reassign statics for readability
        $table_name = $this::TABLE_NAME;

        // Get database schema
        $schema = $this->getSchema($table_name);
        $reflect = new \ReflectionObject($this);

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $key = $prop->getName();
            $data_type = strtolower($schema[$key]->data_type);

            // Trim length if data type is char or varchar
            if ($data_type == 'char'
                ||
                $data_type == 'varchar'
            ) {
                if (strlen($this->{$key}) > (int) $schema[$key]->character_maximum_length) {
                    $this->{$key} = trim(substr($this->{$key}, 1, (int) $schema[$key]->character_maximum_length));
                }
            }
        }
    }

    /**
     * Check for value uniqueness
     *
     * @parameter string $field field name of value to check
     * @parameter var $value field actual value to check
     */
    protected function isUnique(
        $field,
        $value
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
                $table_alias.$field != ''
            AND
                $table_alias.$field = :$field
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam($field, $value);
        $target_branch_id = CurrentRequest::getTargetBranchId();
        $stmt->bindParam('branch_id', $target_branch_id);

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return empty($data);
    }

    /**
     * Gets accessibility of a resource from a requesting branch
     *
     * @parameter string $id Id of the resource
     * @parameter string $requesting_branch Id of the requesting branch
     * @parameter string $table_name
     * @parameter string $primary_key
     */
    public function getAccessibility(
        $id,
        $requesting_branch
    ) {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;
        $table_name = $this::TABLE_NAME;

        $body = '';
        $filter = "
            AND
                base_table.branch_id = :requesting_branch";
        if (property_exists($this, 'is_public')) {
            $body = "
                LEFT JOIN
                    branch b2
                        ON
                            b2.parent_branch_id = base_table.branch_id
                        AND
                            b2.branch_id = :requesting_branch";
            $filter = "
                AND
                    (
                        base_table.branch_id = :requesting_branch
                    OR
                        (
                            b2.parent_branch_id = base_table.branch_id
                        AND
                            base_table.is_public = 'Y'
                        )
                    )";
        }

        // SQL query
        $sql ="
            SELECT
                base_table.$primary_key
            FROM
                $table_name base_table
            INNER JOIN
                branch b
                    ON
                        b.branch_id = base_table.branch_id
                    AND
                        b.status = 'ENABLED'
            $body
            WHERE
                base_table.$primary_key = :$primary_key
            $filter
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $id);
        $stmt->bindParam('requesting_branch', $requesting_branch);

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return !empty($data);
    }

    /**
     * Gets accessibility of a resource from a requesting branch
     *
     * @parameter string $requesting_branch Id of the requesting branch
     * @parameter string $custom_filter name of the column
     * @parameter string $custom_filter_value value of the column
     */
    public function getNextPriorityLevel(
        $custom_filter = ''
    ) {
        // Reassign statics for readability
        $table_name = $this::TABLE_NAME;

        $filter = "";
        if (property_exists($this, $custom_filter)) {
            $filter = "
                AND
                    base_table.$custom_filter = '" . $this->{$custom_filter} ."'
                ";
        }

        // SQL query
        $sql ="
            SELECT
                IFNULL(MAX(base_table.priority_level + 1),0)  AS next_priority_level
            FROM
                $table_name base_table
            INNER JOIN
                branch b
                    ON
                        b.branch_id = base_table.branch_id
                    AND
                        b.status = 'ENABLED'
            WHERE
                base_table.branch_id = :requesting_branch
            $filter
            ";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('requesting_branch', $this->branch_id);

        // Execute query
        $data = $this->fetch($stmt);

        // Check and return data
        return $data->next_priority_level;
    }

    public function setPriorityLevel(
        $new_priority_level,
        $custom_filter = ''
    ) {
        // Throw error if new priority level exceeds limit
        $priority_level_limit = $this->getNextPriorityLevel($this->branch_id, $custom_filter);
        if ($priority_level_limit <= $new_priority_level
            ||
            $new_priority_level < 0
        ) {
            deliver_error_response('400', 40002, 'Priority level must be 0 and ' . ($priority_level_limit - 1) . '.');
        }

        // Throw error if no movement
        if ($this->priority_level == $new_priority_level) {
            return 0;
        }

        // Push affected rows
        $affectedRows = $this->pushPriorityLevels($this->priority_level, $new_priority_level, $custom_filter);

        if ($affectedRows <= 0) {
            return 0;
        }

        // Reassign statics for readability
        $table_name = $this::TABLE_NAME;
        $primary_key = $this::PRIMARY_KEY;

        // SQL query
        $sql ="
            UPDATE
                $table_name base_table
            SET
                base_table.priority_level = :new_priority_level
            WHERE
                base_table.$primary_key = :$primary_key
            ";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('new_priority_level', $new_priority_level);

        // Execute query
        $this->execute($stmt);

        return $stmt->rowCount() + $affectedRows;
    }

    protected function pushPriorityLevels(
        $start,
        $end,
        $custom_filter
    ) {
        // Reassign statics for readability
        $table_name = $this::TABLE_NAME;
        $primary_key = $this::PRIMARY_KEY;

        $direction = '-';

        if ($start == $end
            ||
            $start < 0
            ||
            $end < 0
        ) {
            return 0;
        }

        if ($start > $end) {
            // Push down settings
            swap_values($start, $end);
            $direction = '+';
        }

        $filter = "";
        if (property_exists($this, $custom_filter)) {
            $filter = "
                AND
                    base_table.$custom_filter = '" . $this->{$custom_filter} ."'
                ";
        }

        // SQL query
        $sql ="
            UPDATE
                $table_name base_table
            SET
                base_table.priority_level = base_table.priority_level $direction 1
            WHERE
                base_table.priority_level >= :start
            AND
                base_table.priority_level <= :end
            AND
                base_table.$primary_key != :$primary_key
            $filter
            ";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('start', $start);
        $stmt->bindParam('end', $end);
        $stmt->bindParam($primary_key, $this->{$primary_key});

        // Execute query
        $this->execute($stmt);

        return $stmt->rowCount();
    }

    /**
     * Returns an array of list of model properties based on model property settings and request
     *
     * @parameter string $table_alias an alias used in join
     * @parameter string $alias_prefix prefix for field alias
     * @parameter bool $shared_only type of property for filtering
    */
    protected function getModelProperties(
        $table_alias = '',
        $alias_prefix = '',
        $shared_only = false
    ) {
        $route = $this::ROUTE;
        $primary_key = $this::PRIMARY_KEY;

        $shared_properties = array();

        if (!empty($alias_prefix)) {
            $alias_prefix = $alias_prefix . '_';
        }
        if (!empty($table_alias)) {
            $table_alias = $table_alias . '.';
        }

        $shared_properties[$alias_prefix . 'href'] = "IF ($table_alias$primary_key != '',
                            CONCAT('" . CurrentRequest::getHostName() . "','/$route/', $table_alias$primary_key), '#')";

        foreach ($this->getPropertySettings() as $key => $value) {
            if (
                (
                    !array_key_exists('visible', $value)
                ||
                    (
                        array_key_exists('visible', $value)
                    &&
                        $value['visible'] == true
                    )
                )
                &&
                (
                    (
                        !$shared_only
                    &&
                        (
                            !array_key_exists('foreign_key', $value)
                        ||
                            (
                                array_key_exists('visible', $value)
                            &&
                                $value['visible'] == true
                            )
                        )
                    )
                ||
                    (
                        $shared_only
                        &&
                        (
                            (
                                array_key_exists('shared', $value)
                            &&
                                $value['shared'] == true
                            )
                        ||
                            (
                                (
                                    !array_key_exists('foreign_key', $value)
                                    ||
                                    (
                                        array_key_exists('visible', $value)
                                        &&
                                        $value['visible'] == true
                                    )
                                )
                            &&
                                (
                                    !array_key_exists('shared', $value)
                                ||
                                    (
                                        array_key_exists('shared', $value)
                                    &&
                                        $value['shared'] == true
                                    )
                                )
                            )
                        )
                    )
                )
            ) {
                $shared_properties[$alias_prefix . $key] = $table_alias . $key;
            }
        }

        return $shared_properties;
    }

    /**
     * Core CRUD functions
     */

    protected function load($id) {
        if (empty($id)) {
            return;
        }

        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;

        // SQL query
        $sql = "
            SELECT
                base_table.* "
            . $this::getQuery('body') . "
            WHERE
                base_table.$primary_key = :$primary_key
            AND "
            . $this::getQuery('filter') . "
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind parameters
        $stmt->bindParam($primary_key, $id);

        // Execute query
        $data = $this->fetchClass($stmt);

        if (!$data) {
            deliver_error_response('404', 40401, 'Resource not found.');
        } else {
            // Map data to properties
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function create() {
        // Reassign statics for readability
        $table_name = $this::TABLE_NAME;

        // Validate values
        $this->validateProperties();

        // Get field list from schema
        $schema = $this->getSchema($table_name);

        $sql_fields = array ();

        foreach ($schema as $field_name => $field_data) {
            $sql_fields[] = ":$field_name";
        }

        $sql =
            "INSERT
            INTO
                $table_name
            VALUES
            ("
            . implode(', ', $sql_fields) . "
            )";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind properties to parameters
        $this->bindProperties($stmt);

        // Execute query
        $this->execute($stmt);

        if ($stmt->rowCount() > 0) {
            return $this->{$this::PRIMARY_KEY};
        } else {
            return null;
        }
    }

    public function update() {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;
        $table_name = $this::TABLE_NAME;

        // Validate values
        $this->ValidateProperties();

        // Get field list from schema
        $schema = $this->getSchema($table_name);

        $sql_fields = array ();

        foreach ($schema as $field_name => $field_data) {
            $sql_fields[] = "$field_name = :$field_name";
        }
        $sql =
            "UPDATE
                $table_name
            SET "
            . implode(', ', $sql_fields) . "
            WHERE
                $primary_key = :$primary_key";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind properties to parameters
        $this->bindProperties($stmt);

        // Execute query
        $this->execute($stmt);
    }

    public function updateStatus(array $data) {
        if (!array_key_exists('status', $data)) {
            deliver_error_response('400', 40002, 'Status required.');
        }

        switch (trim(strtoupper($data['status']))) {
            case $this::STATUS_ENABLED:
                $this->changeStatus($this::STATUS_ENABLED);
                break;
            case $this::STATUS_DISABLED:
                $this->changeStatus($this::STATUS_DISABLED);
                break;
            default:
                deliver_error_response('400', 40001, 'Invalid status.');
                break;
        }
    }

    private function changeStatus($status) {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;
        $table_name = $this::TABLE_NAME;

        // SQL query
        $sql =
            "UPDATE
                $table_name
            SET
                status = :status,
                modified_on = :modified_on,
                modified_by = :modified_by
            WHERE
                $primary_key = :$primary_key";

        // Update fields for parameter binding
        $this->modified_on = date('Y-m-d H:i:s.u');
        $this->modified_by =CurrentRequest::getUserId();

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});
        $stmt->bindParam('status', $status);
        $stmt->bindParam('modified_on', $this->modified_on);
        $stmt->bindParam('modified_by', $this->modified_by);

        // Execute query
        $this->execute($stmt);
    }

    public function getAll($parameters = array()) {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;

        // Read parameters
        $per_page = (int) get_value_from_parameter($parameters, 'per_page', 0);
        $page = (int) get_value_from_parameter($parameters, 'page', 0);
        $keywords = (string) get_value_from_parameter($parameters, 'keywords', '');
        $fields = (string) get_value_from_parameter($parameters, 'fields', '');
        $sort_by = (string) get_value_from_parameter($parameters, 'sort_by', '');

        // Make sure id is always included
        if (!empty($fields)) {
            $foreign_keys = array();
            foreach ($this->getPropertySettings() as $property => $setting) {
                if (array_key_exists('foreign_key', $setting)) {
                    $foreign_keys[] = $property;
                }
            }
            $fields = implode(',', $foreign_keys) . ",$primary_key," . $fields;
        }

        // SQL query
        $sql = "
            SELECT "
            . $this->convertToSqlColumns($this::getQuery('fields'), $fields)
            . $this::getQuery('body')
            . "
            WHERE "
            . $this::getQuery('filter')
            . $this->convertToSQLTextSearch($this::getQuery('search'), $keywords, 'AND')
            . $this->convertToCustomSearch($this::getQuery('fields'), $parameters, 'AND')
            . $this->convertToSqlOrder($this::getQuery('fields'), $this::getQuery('order'), $sort_by)
            . $this->convertToSqlPaging($per_page, $page);

        // Start connection
        $stmt = $this->prepare($sql);

        // Execute query
        $data = $this->fetchAll($stmt);

        // Add child data
        foreach ($this::getChildDataSettings() as $code => $model) {
            $this->getChildData($data, $parameters, $code);
        }

        return $data;
    }

    public function getCount($parameters = array()) {
        // Read parameters
        $keywords = (string) get_value_from_parameter($parameters, 'keywords', '');

        // SQL query
        $sql = "
            SELECT "
            . $this->convertToSqlColumns($this::getQuery('fields'))
            . $this::getQuery('body')
            . "
            WHERE "
            . $this::getQuery('filter')
            . $this->convertToSQLTextSearch($this::getQuery('search'), $keywords, 'AND')
            . $this->convertToCustomSearch($this::getQuery('fields'), $parameters, 'AND');

        // Start connection
        $stmt = $this->prepare($sql);

        // Execute query
        return count($this->fetchAll($stmt));
    }

    public function getById(
        $id,
        $parameters = array()
    ) {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;

        // Read parameters
        $fields = (string) get_value_from_parameter($parameters, 'fields', '');

        // Make sure id is always included
        if (!empty($fields)) {
            $foreign_keys = array();
            foreach ($this->getPropertySettings() as $property => $setting) {
                if (array_key_exists('foreign_key', $setting)) {
                    $foreign_keys[] = $property;
                }
            }
            $fields = implode(',', $foreign_keys) . ",$primary_key," . $fields;
        }

        // SQL query
        $sql ="
            SELECT "
            . $this->convertToSqlColumns($this::getQuery('fields'), $fields)
            . $this::getQuery('body')
            . "
            WHERE
                base_table.$primary_key = :$primary_key
            AND "
            . $this::getQuery('filter') . "
            LIMIT 1";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind parameters
        $stmt->bindParam($primary_key, $id);

        // Execute query
        $data = $this->fetchAll($stmt);

        // Check and return data
        if (empty($data)) {
            deliver_error_response(404, 40401, 'Invalid resource or resource is not accessible to your branch.');
        } else {
            // Add child data
            foreach ($this::getChildDataSettings() as $code => $model) {
                $this->getChildData($data, $parameters, $code);
            }

            return $data;
        }
    }

    public function delete() {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;
        $table_name = $this::TABLE_NAME;

        // SQL query
        $sql = "
            DELETE
            FROM
                $table_name
            WHERE
                $primary_key = :$primary_key";

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam($primary_key, $this->{$primary_key});

        // Execute query
        $this->execute($stmt);

        $row_count = $stmt->rowCount();

        if ($row_count > 0) {
            $today = date('Y-m-d H:i:s.u');

            // SQL query
            $sql = "
            INSERT
            INTO
                deleted_data
            (
                branch_id,
                table_name,
                primary_key,
                primary_key_value,
                deleted_on,
                deleted_by
            )
            VALUES
            (
                :branch,
                :table_name,
                :primary_key,
                :primary_key_value,
                :deleted_on,
                :deleted_by
            )";

            // Start connection
            $stmt = $this->prepare($sql);

            // Bind custom parameters
            $stmt->bindParam('branch', CurrentRequest::getTargetBranchId());
            $stmt->bindParam('table_name', $table_name);
            $stmt->bindParam('primary_key', $primary_key);
            $stmt->bindParam('primary_key_value', $this->{$primary_key});
            $stmt->bindParam('deleted_on', $today);
            $stmt->bindParam('deleted_by', CurrentRequest::getUserId());

            // Execute query
            $this->execute($stmt);
        }

        return $row_count;
    }

    public function deleteBatch($parameters = array()) {
        // Reassign statics for readability
        $table_name = $this::TABLE_NAME;

        $filter = '';
        $filter_array = array();
        foreach ($parameters as $key => $value) {
            if (property_exists($this, $key)) {
                $filter_array[] = "$key = '$value'";
            }
        }

        if (!empty($filter_array)) {
            $filter = 'WHERE ' . implode(' AND ', $filter_array);
        }

        // SQL query
        $sql = "
            DELETE
            FROM
                $table_name
                $filter
            ";

        // Start connection
        $stmt = $this->prepare($sql);

        // Execute query
        $this->execute($stmt);

        return $stmt->rowCount();
    }

    public function getAllChildren(
        $parameters = array(),
        $key,
        $key_collection
    ) {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;

        // Get parameters
        if (empty($parameters)) {
            $parameters = (array) \Slim\Slim::getInstance()->request()->params();
        }

        // Read parameters
        $per_page = (int) get_value_from_parameter($parameters, 'per_page', 0);
        $page = (int) get_value_from_parameter($parameters, 'page', 0);
        $keywords = (string) get_value_from_parameter($parameters, 'keywords', '');
        $fields = (string) get_value_from_parameter($parameters, 'fields', '');
        $sort_by = (string) get_value_from_parameter($parameters, 'sort_by', '');

        // Make sure id is always included
        if (!empty($fields)) {
            $foreign_keys = array();
            foreach ($this->getPropertySettings() as $property => $setting) {
                if (array_key_exists('foreign_key', $setting)) {
                    $foreign_keys[] = $property;
                }
            }
            $fields = implode(',', $foreign_keys) . ",$key,$primary_key," . $fields;
        }

        $custom_filter = "";
        if (property_exists($this, 'branch_id')
            &&
            $primary_key != 'branch_id'
        ) {
            $custom_filter = " AND b.status = 'ENABLED' ";
        }

        // SQL query
        $sql = "
            SELECT "
            . $this->convertToSqlColumns($this::getQuery('fields'), $fields)
            . $this::getQuery('body')
            . "
            WHERE
                base_table.$key IN ($key_collection) $custom_filter "
            . $this->convertToSQLTextSearch($this::getQuery('search'), $keywords, 'AND')
            . $this->convertToCustomSearch($this::getQuery('fields'), $parameters, 'AND')
            . $this->convertToSqlOrder($this::getQuery('fields'), $this::getQuery('order'), "base_table.$sort_by")
            . $this->convertToSqlPaging($per_page, $page);

        // Start connection
        $stmt = $this->prepare($sql);

        // Execute query
        $data = $this->fetchAll($stmt);

        // Add child data
        foreach ($this::getChildDataSettings() as $code => $model) {
            $this->getChildData($data, $parameters, $code);
        }

        return $data;
    }

    protected function getChildData(
        &$data,
        array $parameters = null,
        $child_data_name = ''
    ) {
        // Reassign statics for readability
        $primary_key = $this::PRIMARY_KEY;

        // Get required parameter
        $option = get_value_from_parameter($parameters, "_$child_data_name", null);

        // Get required parameter value
        $prefix = trim(substr($option, 0, strpos($option, '(')));

        if (empty($prefix)) {
            $prefix = $option;
        }

        // Return if no data provided or required parameter is invalid
        if (empty($data)
            ||
            strtolower($prefix) != 'show'
        ) {
            return;
        }

        // Convert custom parameters to array
        $child_parameters = $this->convertParametersToArray($option, '|');

        $obj = null;
        $key = $primary_key;

        foreach ($this::getChildDataSettings() as $code => $model) {
            if ($code == $child_data_name) {
                if (array_key_exists('model', $model)) {
                    $obj = new $model['model']();
                }
                if (array_key_exists('key', $model)) {
                    $key = $model['key'];
                }
            }
        }

        if (!array_key_exists('sort_by', $child_parameters)) {
            $child_parameters['sort_by'] = '';
        }
        $child_parameters['sort_by'] = "$key," . $child_parameters['sort_by'];

        // Make sure key is included in fields parameter
        if (array_key_exists('fields', $child_parameters)) {
            $child_parameters['fields'] .= ",$key";
        }

        if (empty($obj)) {
            return;
        }

        $key_collection = array();

        foreach ($data as $value) {
            $key_collection[] = "'" . $value->{$primary_key} . "'";
        }

        $child_data = $obj->getAllChildren($child_parameters, $primary_key, implode(',', $key_collection));

        // Loop each parent to compare Ids
        foreach ($data as $value) {
            $child_count = 0;

            // Loop each child to compare Ids
            foreach ($child_data as $child_key => $child_value) {

                // Check if Ids matched
                if ($value->{$primary_key} == $child_value->{$key}) {

                    //Remove redundant Id
                    unset($child_value->{$key});

                    // Add child to parent
                    $value->{$child_data_name}[] = $child_value;

                    // Remove child from child object
                    unset($child_data[$child_key]);

                    $child_count++;
                }
            }

            $value->{$child_data_name . '_count'} = "$child_count";
        }
    }

    /**
     * Overrides
    */

    public function addEnumFormatValidator($table_name, array &$property_settings) {
        parent::addEnumFormatValidator($table_name, $property_settings);
    }
} 