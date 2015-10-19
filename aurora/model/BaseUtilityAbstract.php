<?php
/**
 * Abstract Base Utility Class
 *
 * Created by PhpStorm.
 * User: Pao
 * Date: 5/22/14
 * Time: 8:23 PM
 */

namespace aurora\model;

class BaseUtilityAbstract {

    /**
     * Opens a database connection and prepare PDO statement
     *
     * @parameter string $sql SQL query
     */
    protected function prepare($sql) {
        try {
            // Get database connection
            $db = get_db_connection();
            // Return a PDOStatement
            return $db->prepare($sql);
        } catch(\PDOException $e) {
            // Close connection
            $db = null;
            // Throw exception
            deliver_error_response(500, 50002, $e->getMessage());
        }
    }

    /**
     * Used for INSERT, UPDATE and DELETE operations
     *
     * @parameter reference PDOStatement $stmt PDO statement object
     */
    protected function execute(&$stmt) {
        try {
            $stmt->execute();
            // Close connection
            $db = null;
        } catch(\PDOException $e) {
            // Throw exception
            deliver_error_response(500, 50002, $e->getMessage());
        }
    }

    /**
     * Used for SELECT operations that returns multiple rows
     *
     * @parameter reference PDOStatement $stmt PDO statement object
     */
    protected function fetchAll(&$stmt) {
        try {
            $stmt->execute();
            // Close connection
            $db = null;
            // Return data
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch(\PDOException $e) {
            // Throw exception
            deliver_error_response(500, 50002, $e->getMessage());
        }
    }

    /**
     * Used for SELECT operations that returns a single row
     *
     * @parameter reference PDOStatement $stmt PDO statement object
     */
    protected function fetch(&$stmt) {
        try {
            $stmt->execute();
            // Close connection
            $db = null;
            // Return data
            return $stmt->fetchObject();
        } catch(\PDOException $e) {
            // Throw exception
            deliver_error_response(500, 50002, $e->getMessage());
        }
    }

    /**
     * Used for SELECT operations that expects a
     * data object of the current class
     *
     * @parameter reference PDOStatement $stmt PDO statement object
     */
    protected function fetchClass(&$stmt) {
        try {

            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, '');
            $properties = $stmt->fetch();

            // Close connection
            $db = null;

            if (!$properties) {
                return false;
            }
            // Return object data
            return $properties;
        } catch(\PDOException $e) {
            // Throw exception
            deliver_error_response(500, 50002, $e->getMessage());
        }
    }

    /**
     * Returns list of table columns properties
     *
     * @parameter string $table_name
     */
    protected function getSchema($table_name) {
        // SQL query
        $sql = "
            SELECT
                column_name,
                ordinal_position,
                column_default,
                data_type,
                column_type,
                character_maximum_length
            FROM
                `information_schema`.`columns`
            WHERE
                `table_schema` = :table_schema
            AND
                `table_name` = :table_name";

        // Update fields for parameter binding
        $db_name  = DB_NAME;

        // Start connection
        $stmt = $this->prepare($sql);

        // Bind custom parameters
        $stmt->bindParam('table_schema', $db_name);
        $stmt->bindParam('table_name', $table_name);

        // Execute query
        $data = $this->fetchAll($stmt);

        $schema = array();

        foreach ($data as $row) {
            $schema[$row->column_name] = $row;
        }

        return $schema;
    }

    /**
     * Append an enum option format validator in property setting array
     *
     * @parameter string $table_name
     * @parameter reference array $property_settings
     */
    public function addEnumFormatValidator($table_name, array &$property_settings) {
        $table_schema = $this->getSchema($table_name);

        foreach($table_schema as $value) {
            if ($value->data_type == 'enum'
                &&
                array_key_exists($value->column_name, $property_settings)
            ) {
                $property_settings[$value->column_name]['format'] = $this->convertToEnumRegex($value->column_type);
            }
        }

    }

    /**
     * Converts a column_type schema data to a regex option string
     *
     * @parameter string $schema_column_type value of column_type from schema
     */
    protected function convertToEnumRegex($schema_column_type) {
        preg_match('/\(([^)]+)\)/', $schema_column_type, $enum_options);

        if (empty($enum_options)) {
            return null;
        }

        $enum_options[1] = str_replace("'", "", $enum_options[1]);
        $enum_options[1] = str_replace(",", "|", $enum_options[1]);
        return "/^($enum_options[1])$/";
    }

    /**
     * Validate model properties
     *
     * @parameter array $property_settings list of validation & format settings
     */
    protected function isValid(array $property_settings) {
        // Apply auto formats
        $this->formatProperties($property_settings);

        // Create an array to store all validation errors
        $error = array();

        if (empty($error)) {
            // Validate required fields
            $this->validateRequiredFields($property_settings, $error);

            if (empty($error)) {
                // Validate for acceptable format requirements
                $this->validateFormatRequirements($property_settings, $error);

                if (empty($error)) {
                    // Check for foreign key accessibility
                    $this->validateForeignKey($property_settings, $error);

                    if (empty($error)) {
                        // Custom validations
                        $this->validateCustom($property_settings, $error);
                    }
                }
            }
        }

        // Throw error if not empty
        if (!empty($error)) {
            deliver_error_response(400, 40001, 'Your data contains invalid parameters.', $error);
        } else {
            return true;
        }
    }

    /**
     * Apply text format settings on each properties
     *
     * @parameter array $property_settings list of validation & format settings
     */
    protected function formatProperties(array $property_settings) {
        foreach ($property_settings as $property => $settings) {
            if (property_exists($this, $property)) {

                // Check and run upper case formatting
                if (array_key_exists('upper_case' , $settings)
                    &&
                    !empty($settings['upper_case'])
                ) {
                    $this->{$property} = strtoupper($this->{$property});

                // Check and run lower case formatting
                } else if (array_key_exists('lower_case' , $settings)
                    &&
                    !empty($settings['lower_case'])
                ) {
                    $this->{$property} = strtolower($this->{$property});
                }

                // Check and run tag stripping
                if (array_key_exists('strip_tags' , $settings)
                    &&
                    !empty($settings['strip_tags'])
                ) {
                    $this->{$property} = strip_tags($this->{$property});
                }

                // Check and run html entity conversion
                if (array_key_exists('html_entities' , $settings)
                    &&
                    !empty($settings['html_entities'])
                ) {
                    $this->{$property} = htmlentities($this->{$property}, ENT_NOQUOTES);
                }
            }
        }
    }

    /**
     * Checks for required fields for empty values
     *
     * @parameter array $property_settings list of validation & format settings
     * @parameter array referenced $error collection of errors thrown
     */
    protected function validateRequiredFields(
        array $property_settings,
        array &$error
    ) {
        foreach ($property_settings as $property => $settings) {

            // Check if property is require
            if (property_exists($this, $property)) {
                if (array_key_exists('required' , $settings)
                    &&
                    !empty($settings['required'])
                ) {
                    val_required($error, $property, $this->{$property});
                }
            }
        }
    }

    /**
     * Checks for format compliance
     *
     * @parameter array $property_settings list of validation & format settings
     * @parameter array referenced $error collection of errors thrown
     */
    protected function validateFormatRequirements(
        array $property_settings,
        array &$error
    ) {
        foreach ($property_settings as $property => $settings) {

            // Check if property has format validation
            if (property_exists($this, $property)) {
                if (array_key_exists('format' , $settings)
                    &&
                    !empty($settings['format'])
                ) {
                    val_regex($error, $property, $this->{$property}, $settings['format']);
                }
            }

            // Check if property has range validation
            if (property_exists($this, $property)) {
                if (array_key_exists('range' , $settings)
                    &&
                    !empty($settings['range'])
                ) {
                    $range_options = $settings['range'];
                    $min = 0.0;
                    $max = 0.0;
                    if (array_key_exists(0, $range_options)) {
                        $min = $range_options[0];
                    }
                    if (array_key_exists(1, $range_options)) {
                        $max = $range_options[1];
                    }
                    if ($min > $max) {
                        swap_values($min, $max);
                    }
                    val_range($error, $property, $this->{$property}, $min, $max);
                }
            }
        }
    }

    /**
     * Checks if foreign keys are valid and accessible
     *
     * @parameter array $property_settings list of validation & format settings
     * @parameter array referenced $error collection of errors thrown
     */
    protected function validateForeignKey(
        array $property_settings,
        array &$error
    ) {
        foreach ($property_settings as $property => $settings) {

            // Check if property has foreign key validation
            if (property_exists($this, $property)) {
                if (array_key_exists('foreign_key' , $settings)
                    &&
                    !empty($settings['foreign_key'])
                    &&
                    !empty($this->{$property})
                ) {

                    $foreign_key_properties = $settings['foreign_key'];

                    // Check for foreign key settings completeness
                    if (!array_key_exists('class', $foreign_key_properties)
                        ||
                        !array_key_exists('requesting_branch', $foreign_key_properties)
                        ||
                        !class_exists($foreign_key_properties['class'])
                    ) {
                        deliver_error_response(500, 50003, "Invalid foreign key properties for $property.");
                    }

                    // Create an instance of target class for checking foreign key
                    $obj = new $foreign_key_properties['class']();

                    // Check for accessibility of resource from the requesting branch
                    if (!$obj->getAccessibility($this->{$property}, $foreign_key_properties['requesting_branch'])) {
                        // Add error
                        $error[] = array (
                            'parameter' => $property,
                            'valueSent' => $this->{$property},
                            'errorMessage' => "Foreign key is not accessible to the requesting branch.",
                        );
                    }
                }
            }
        }
    }

    /**
     * Checks for custom requirements compliance
     *
     * @parameter array $property_settings list of validation & format settings
     * @parameter array referenced $error collection of errors thrown
     */
    protected function validateCustom(
        array $property_settings,
        array &$error
    ) {
        foreach ($property_settings as $property => $settings) {

            // Check if property has custom validation
            if (property_exists($this, $property)) {
                if (array_key_exists('custom' , $settings)
                    &&
                    !empty($settings['custom'])
                ) {

                    // Check if custom method is valid and existing
                    foreach($settings['custom'] as $custom_validation) {
                        if (array_key_exists('method' , $custom_validation)
                            &&
                            !empty($custom_validation['method'])
                            &&
                            method_exists($this, $custom_validation['method'])
                        ) {

                            // Run custom method
                            if (!call_user_func_array(array($this, $custom_validation['method']), $custom_validation['parameter'])) {

                                $error_message = '';

                                // Check for custom error message
                                if (array_key_exists('error_message' , $custom_validation)
                                    &&
                                    !empty($custom_validation['error_message'])
                                ) {
                                    $error_message = $custom_validation['error_message'];
                                }

                                // Add error
                                $error[] = array (
                                    'parameter' => $property,
                                    'valueSent' => $this->{$property},
                                    'errorMessage' => $error_message,
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Filter column array with filter string
     *
     * @parameter array $columns complete list of columns
     * @parameter string $filter comma delimited list of requested columns
     */
    static private function filterColumns(
        array $columns,
        $filter
    ) {
        $filtered_columns = array();

        $filter = explode(',', $filter);

        // Get requested columns according to filter
        foreach ($filter as $value) {
            if (array_key_exists(trim($value), $columns)) {
                $filtered_columns[trim($value)] = $columns[trim($value)];
            }
        }

        if (empty($filtered_columns)) {
            return $columns;
        } else {
            return $filtered_columns;
        }
    }

    /**
     * Sort order array with filter string
     *
     * @parameter array $columns complete list of columns
     * @parameter string string $sort_by comma delimited list of requested columns sorting
     */
    static private function filterOrder(
        array $columns,
        $sort_by
    ) {
        $filtered_columns = array();

        $sort_by = explode(',', $sort_by);

        // Get requested columns from according to filter
        foreach ($sort_by as $value) {
            $new_key = trim($value);
            $new_value = 'ASC';

            if (preg_match('/^[\-]+/',$new_key )) {
                $new_key = preg_replace('/^[\-]+/', '', $new_key);
                $new_value = 'DESC';
            }

            if (array_key_exists($new_key, $columns)) {
                $filtered_columns[$new_key] = $new_value;
            }
        }

        return $filtered_columns;
    }

    /**
     * Converts column array to SQL readable columns
     * with optional filter
     *
     * @parameter array $columns complete list of columns
     * @parameter optional string $filter comma delimited list of requested columns
     */
    static protected function convertToSqlColumns(
        array $columns,
        $filter = ''
    ) {
        // Start filter process if filter is provided
        if (!empty(trim($filter))) {
            $columns = DbContextAbstract::filterColumns($columns, $filter);
        }

        // Convert array to SQL fields
        $sql_columns = array();

        foreach ($columns as $key => $value) {
            //Use column value as field and key as alias
            $sql_columns[$key] = "$value $key";
        }

        //Return a SQL readable string of columns
        return implode(', ', $sql_columns);
    }

    /**
     * Converts column array to SQL readable columns
     * with optional filter
     *
     * @parameter array $text_columns list of searchable columns with keyword
     * @parameter optional string $q keyword used to match beginning of column
     */
    static protected function convertToSQLTextSearch(
        array $text_columns,
        $q = '',
        $prefix = ''
    ) {
        $default_search = array();

        foreach ($text_columns as $value) {
            //Use column value as field and key as alias
            $keyword = addslashes($q);
            if ($keyword != ''
                &&
                $keyword != null
            ) {
                $in_search = explode(',', $keyword);
                if (count($in_search) > 1) {
                    foreach ($in_search as $search_key => $search_val) {
                        $in_search[$search_key] = "'$search_val'";
                    }
                    $default_search[] = "$value IN (" . implode(',', $in_search) . ")" ;
                } else {
                    $default_search[] = "$value LIKE '$keyword%'" ;
                }
            }
        }

        if (empty($default_search)){
            return '';
        } else {
            return "$prefix (" . implode(' OR ', $default_search) . ') ';
        }

    }

    /**
     * Converts custom search queries to SQL readable
     *
     * @parameter array $searchable_columns valid columns to search
     * @parameter array $query_parameters list of search requests
     * @parameter optional string $prefix
     */
    static protected function convertToCustomSearch(
        array $searchable_columns,
        array $query_parameters,
        $prefix = ''
    ) {

        $custom_search = array();

        foreach ($searchable_columns as $key => $value) {
            if (array_key_exists($key, $query_parameters)) {

                $keyword = addslashes($query_parameters[$key]);
                if ($keyword != ''
                    &&
                    $keyword != null
                ) {
                    $in_search = explode(',', $keyword);
                    if (count($in_search) > 1) {
                        foreach ($in_search as $search_key => $search_val) {
                            $in_search[$search_key] = "'$search_val'";
                        }
                        $custom_search[] = "$value IN (" . implode(',', $in_search) . ")" ;
                    } else {
                        $custom_search[] = "$value LIKE '$keyword%'" ;
                    }
                }
            }
        }

        if (empty($custom_search)){
            return '';
        } else {
            return "$prefix (" . implode(' AND ', $custom_search) . ') ';
        }
    }

    /**
     * Converts column sorting array to SQL readable columns
     *
     * @parameter array $columns complete list of columns
     * @parameter array $default_order the default column order used
     * @parameter optional string $sort_by comma delimited list of requested columns sorting
     */
    static protected function convertToSqlOrder(
        array $columns,
        array $default_order,
        $sort_by = ''
    ) {
        // Start filter process if filter is provided
        if (!empty(trim($sort_by))) {
            $columns = DbContextAbstract::filterOrder($columns, $sort_by);
        }

        // Use default ordering if no sorting is valid
        if (empty(trim($sort_by))
            ||
            empty($columns)
        ) {
            $columns = $default_order;
        }

        // Convert array to SQL fields
        $sql_order = array();

        foreach ($columns as $key => $value) {
            //Use column value as field and key as alias
            $sql_order[$key] = "$key $value";
        }

        //Return a SQL readable string of columns
        if (empty($sql_order)) {
            return '';
        } else {
            return ' ORDER BY ' . implode(', ', $sql_order) . ' ';
        }

    }

    /**
     * Converts paging information to SQL offset and limit
     *
     * @parameter int $per_page number of records to be returned
     * @parameter int $page offset multiplier for paging
     */
    static protected function convertToSqlPaging(
        $per_page,
        $page
    ){
        $paging = ''; // Set default to no paging
        $per_page = (int) $per_page;
        $page = (int) $page;

        // Create paging only if per_page value is appropriate
        if ($per_page > 0) {
            // Adjust page value to make sure that offset value is correct
            if ($page <= 0) {
                $page = 0;
            } else {
                $page--;
            }

            // Compose SQL offset & limit
            $offset = $page * $per_page;
            $paging = " LIMIT $offset, $per_page ";
        }

        return $paging;
    }

    /**
     * Converts string parameter to key pair value
     *
     * @parameter string $string_parameter URI parameter
     * @parameter string $parameter_delimiter
     * @parameter string $key_pair_delimiter
     */
    static protected function convertParametersToArray(
        $option,
        $parameter_delimiter = '&',
        $key_pair_delimiter = '='
    ) {
        $parameters = array();
        if (empty($option)) {
            return $parameters;
        }
        // Get optional custom parameter string
        preg_match('/\(([^)]+)\)/', $option, $string_parameter);

        if (array_key_exists(1, $string_parameter)) {
            $params = explode($parameter_delimiter, $string_parameter[1]);
            foreach ($params as $value) {
                $new_key = trim(substr($value, 0, strpos($value, $key_pair_delimiter)));
                $parameters[$new_key] = substr(trim(strrchr($value, $key_pair_delimiter)), 1);
            }
        }

        return $parameters;
    }
} 