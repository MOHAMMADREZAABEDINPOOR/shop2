@echo off
echo Starting MariaDB on port 3306...
start "" "C:\Program Files\MariaDB 13.0\bin\mysqld.exe" --datadir="D:\mysql_data" --port=3306 --bind-address=127.0.0.1

echo Starting PostgreSQL on port 5433...
start "" "C:\Program Files\PostgreSQL\18\bin\postgres.exe" -D "D:\postgres_data" -p 5433

echo Starting Laravel server on port 8000...
cd /d "D:\code\shop2"
start "" php artisan serve --host=127.0.0.1 --port=8000

echo All database services and Laravel server started successfully!
timeout /t 3
