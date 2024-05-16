import argparse
import os
import pandas as pd
import mysql.connector
from mysql.connector import Error

def insert_csv_data(csv_file, server='localhost', database, table, user='', password=''):
    # Validate CSV file
    if not os.path.isfile(csv_file):
        print("Error: CSV file does not exist.")
        return

    # Validate database and table names
    if not database or not table:
        print("Error: Database and table names must be provided.")
        return

    # Read CSV file into pandas DataFrame
    try:
        df = pd.read_csv(csv_file)
    except Exception as e:
        print(f"Error reading CSV file: {e}")
        return

    # Handle null username and password
    user = user if user is not None else ''
    password = password if password is not None else ''

    # Connect to MySQL database
    try:
        connection = mysql.connector.connect(
            host=server,
            database=database,
            user=user,
            password=password
        )

        if connection.is_connected():
            cursor = connection.cursor()

            # Create table if not exists
            create_table_query = f"CREATE TABLE IF NOT EXISTS {table} ("
            create_table_query += ', '.join([f"{column} VARCHAR(255)" for column in df.columns])
            create_table_query += ")"
            cursor.execute(create_table_query)

            # Insert data into MySQL table
            for index, row in df.iterrows():
                insert_query = f"INSERT INTO {table} ({', '.join(df.columns)}) VALUES ({', '.join(['%s']*len(df.columns))})"
                cursor.execute(insert_query, tuple(row))

            connection.commit()
            print("Data inserted successfully into MySQL table")

    except Error as e:
        print(f"Error connecting to MySQL database: {e}")

    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("MySQL connection is closed")

def main():
    parser = argparse.ArgumentParser(description='Import data from CSV file into MySQL database.')
    parser.add_argument('csv_file', type=str, help='Path to the CSV file')
    parser.add_argument('--server', type=str, default='localhost', help='MySQL server name')
    parser.add_argument('--database', type=str, required=True, help='MySQL database name')
    parser.add_argument('--table', type=str, required=True, help='MySQL table name')
    parser.add_argument('--user', type=str, default=None, help='MySQL username')
    parser.add_argument('--password', type=str, default=None, help='MySQL password')

    args = parser.parse_args()

    insert_csv_data(args.csv_file, args.server, args.database, args.table, args.user, args.password)

if __name__ == "__main__":
    main()
