# CSV to MySQL Database Importer

This repository contains scripts to import data from a CSV file into a MySQL database table. It includes scripts written in both PHP and Python.

## PHP Script

### Usage

#### Prerequisites

- PHP installed on your system
- Access to a MySQL database
- 

#### Instructions

1. Clone or download this repository to your local machine.
2. Navigate to the directory containing the PHP script (`csv_to_mysql.php`).
3. Open a terminal or command prompt in that directory.
4. install pandas
     pip install pandas
5. install mysql.connector
     pip install mysql-connector-python

#### Command Line Arguments

The PHP script accepts the following command line arguments:

- `csv_file`: Path to the CSV file containing the data to be imported (required).
- `-s/`: MySQL server name (default: localhost).
- `-u`: MySQL username (default: empty).
- `-p/`: MySQL password (default: empty).
- `-d/`: MySQL database name (required).
- `-t/`: MySQL table name (required).

#### Example Usage

php csv_to_mysql.php your_csv_file.csv -d your_database -t your_table

## Python Script

### Usage

#### Prerequisites

- Python installed on your system
- Access to a MySQL database

#### Instructions

1. Clone or download this repository to your local machine.
2. Navigate to the directory containing the Python script (`csv_to_mysql.py`).
3. Open a terminal or command prompt in that directory.

#### Command Line Arguments

The Python script accepts the following command line arguments:

- `csv_file`: Path to the CSV file containing the data to be imported (required).
- `--server`: MySQL server name (default: localhost).
- `--user`: MySQL username (default: empty).
- `--password`: MySQL password (default: empty).
- `--database`: MySQL database name (required).
- `--table`: MySQL table name (required).

#### Example Usage

python csv_to_mysql.py your_csv_file.csv --database your_database --table your_table

You can also specify optional arguments like `--server`, `--user`, and `--password`:


## License

This project is licensed under the [MIT License](LICENSE).


