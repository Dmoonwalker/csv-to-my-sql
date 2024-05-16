<?php
// Function to insert CSV data into MySQL database
function insertCSVData($csv_file, $servername, $username, $password, $database, $table) {
    // Input validation
    if (empty($csv_file)) {
        echo "Error: Please provide a valid CSV file.\n";
        exit(1);
    }

    if (empty($database)) {
        echo "Error: Please provide a valid database name.\n";
        exit(1);
    }

    if (empty($table)) {
        echo "Error: Please provide a valid table name.\n";
        exit(1);
    }

    // Replace null username and password with empty strings
    $username = $username ?? '';
    $password = $password ?? '';
    // Replace null servername with default value
    $servername = $servername ?? 'localhost';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create table if it doesn't exist
    $create_table_sql = "CREATE TABLE IF NOT EXISTS $table (";
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        $header = fgetcsv($handle, 1000, ",");
        if ($header === FALSE) {
            echo "Error: Unable to read header row from CSV file.";
            $conn->close();
            exit(1);
        }
        foreach ($header as $column) {
            $create_table_sql .= "`$column` VARCHAR(255), ";
        }
        $create_table_sql = rtrim($create_table_sql, ", ") . ")";
        if ($conn->query($create_table_sql) !== TRUE) {
            echo "Error creating table: " . $conn->error;
            $conn->close();
            exit(1);
        }
    } else {
        echo "Error: Unable to open file.";
        $conn->close();
        exit(1);
    }

    // Open the CSV file for reading
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        // Skip the header row
        fgetcsv($handle, 1000, ",");
        // Read each line of the file
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Prepare SQL statement for insertion
            $sql = "INSERT INTO $table VALUES ('" . implode("','", array_map(array($conn, 'real_escape_string'), $data)) . "')";

            // Execute SQL statement
            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Close the file handle
        fclose($handle);
    } else {
        echo "Error: Unable to open file.";
    }

    // Close MySQL connection
    $conn->close();
}

// Check if required arguments are provided
$options = getopt("f:s:u:p:d:t:");
if (empty($options) || !isset($options['f']) || !isset($options['d']) || !isset($options['t'])) {
    echo "Usage: php script.php -f <csv_file> -d <database> [-s <servername>] [-u <username>] [-p <password>] -t <table>\n";
    exit(1);
}

// Collect arguments from command line options
$csv_file = $options['f'];
$servername = $options['s'] ?? 'localhost';
$username = $options['u'] ?? '';
$password = $options['p'] ?? '';
$database = $options['d'];
$table = $options['t'];

// Insert CSV data into MySQL database
insertCSVData($csv_file, $servername, $username, $password, $database, $table);
?>
