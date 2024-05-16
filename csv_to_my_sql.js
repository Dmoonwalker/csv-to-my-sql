const fs = require('fs');
const csv = require('csv-parser');
const mysql = require('mysql');

function insertCsvData(csvFile, database, table, server = 'localhost', user = '', password = '') {
    // Validate CSV file
    if (!fs.existsSync(csvFile)) {
        console.error("Error: CSV file does not exist.");
        return;
    }

    // Validate database and table names
    if (!database || !table) {
        console.error("Error: Database and table names must be provided.");
        return;
    }

    // Handle null username and password
    user = user || '';
    password = password || '';

    // Create MySQL connection
    const connection = mysql.createConnection({
        host: server,
        user: user,
        password: password,
        database: database
    });

    // Connect to MySQL
    connection.connect((err) => {
        if (err) {
            console.error("Error connecting to MySQL database:", err);
            return;
        }

        console.log("Connected to MySQL database.");

        // Create table if not exists
        connection.query(`CREATE TABLE IF NOT EXISTS ${table} (id INT AUTO_INCREMENT PRIMARY KEY)`, (err) => {
            if (err) {
                console.error("Error creating table:", err);
                connection.end();
                return;
            }

            console.log("Table created successfully.");

            // Read CSV file and insert data into MySQL table
            fs.createReadStream(csvFile)
                .pipe(csv())
                .on('data', (row) => {
                    connection.query(`INSERT INTO ${table} SET ?`, row, (err, result) => {
                        if (err) {
                            console.error("Error inserting data into MySQL table:", err);
                            return;
                        }
                        console.log("Data inserted successfully:", result);
                    });
                })
                .on('end', () => {
                    console.log("CSV file successfully processed.");
                    connection.end();
                });
        });
    });
}

// Parse command line arguments
const args = process.argv.slice(2);
const options = {};
let csvFile, database, table;

for (let i = 0; i < args.length; i++) {
    const arg = args[i];
    if (arg.startsWith('--')) {
        const option = arg.slice(2);
        const value = args[i + 1];
        options[option] = value;
        i++; // Skip next argument
    } else {
        // Not an option, must be CSV file, database, and table
        if (!csvFile) csvFile = arg;
        else if (!database) database = arg;
        else if (!table) table = arg;
    }
}

// Check required arguments
if (!csvFile || !database || !table) {
    console.error("Usage: node csv_to_mysql.js <csv_file> <database> <table> [--server <server>] [--user <user>] [--password <password>]");
    process.exit(1);
}

// Example usage
insertCsvData(csvFile, database, table, options.server, options.user, options.password);
