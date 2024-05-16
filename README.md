# CSV to MySQL Database Importer

This PHP script allows you to import data from a CSV file into a MySQL database table.

## Usage

### Prerequisites

- PHP installed on your system
- Access to a MySQL database

### Instructions

1. Clone or download this repository to your local machine.
2. Navigate to the directory containing the PHP script.
3. Open a terminal or command prompt in that directory.

### Command Line Arguments

The script accepts the following command line arguments:

- `-f <csv_file>`: Path to the CSV file containing the data to be imported (required).
- `-s <servername>`: MySQL server name (default: localhost).
- `-u <username>`: MySQL username (default: empty).
- `-p <password>`: MySQL password (default: empty).
- `-d <database>`: MySQL database name (required).
- `-t <table>`: MySQL table name (required).

### Example Usage

If you want to specify the server name, username, and password.

## License

This project is licensed under the [MIT License](LICENSE).
