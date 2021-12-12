# AlkoSelain

A simple site to browse Alko's listings, created for a school project.

![Logo](./src/logo.svg)

The project hopefully fulfills all the listed requirements:

- [x] Browsing the catalog in a paginated table, with 25 rows per page
- [x] Displays at least the drink's:
  - [x] Product number
  - [x] Name
  - [x] Manufacturer
  - [x] Bottle size
  - [x] Price
  - [x] Price per liter
  - [x] Type
  - [x] Manufacturing country
  - [x] Vintage
  - [x] Alcohol percentage
  - [x] Energy in kcal per 100ml
- [x] Storing the data in a database
- [x] Implementing filters that can be used alone or combined for at least:
  - [x] Type
  - [x] Manufacturing country
  - [x] Bottle size
  - [x] Price (range)
  - [x] Energy amount (range)
- [x] The data can be refreshed directly from alko's excel file url
- [x] Display a specific title which says from which time the data is from
- [x] Display filters below the table's title
- [x] Display the table's data below the filters
- [x] Variable configuration options for:
  - [x] Database connection (environment)
  - [x] The displayed columns (query parameter with limited possible values)
  - [x] Rows per page (query parameter with limits)
- [x] Divide the application into modules

Uses [SimpleXLSX](https://github.com/shuchkin/simplexlsx) for importing the spreadsheet data from Alko.

## Setup

The minimum supported PHP version is PHP8.0.
Most likely PHP7.4 should work as well, but it's not been validated.

### Dev

The setup is made to be reliable and easy for development with docker-compose.
First copy the example environment file to `.env` and edit the values.
Then just run `docker-compose up` in this directory and it'll start a container for you.

### Production

Setup a mariadb (mysql) database and create an user and a database for this project.

Then setup nginx to serve the `src/` folder.
It should process the php files with php-fpm (you can read the [nginx.conf](./config/nginx.conf) for an example).
The php-fpm process should also have the following variables set for the database configuration: `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_DATABASE`.

## Refreshing the data

Visit `applcation-root-url/refresh.php`
Where `applcation-root-url` in the provided dev server is at `http://localhost:8080` and in the course's production system is at `http://xx.xx.xx.xx/product-catalog`, with the IP being provided in the assignment return.

Note that if we were deploying the application into production in a way that it was accessible over the internet to anyone, we would want to protect that url trough nginx with basic authentication for example so that only administrators can do it, as to avoid someone launching a dos attack trough this service against alko.
