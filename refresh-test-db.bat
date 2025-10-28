@echo off
echo Dropping and recreating test database...
mysql -u root -proot -e "DROP DATABASE IF EXISTS laravel_11_testing;"
mysql -u root -proot -e "CREATE DATABASE laravel_11_testing;"
echo Test database refreshed!

