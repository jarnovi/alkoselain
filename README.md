# AlkoSelain

A simple site to browse Alko's listings, created for a school project.

![Logo](./static/logo.svg)

The project hopefully fulfills all the listed requirements (OUTDATED):

- [ ] Browsing the catalog in a paginated table, with 25 rows per page
- [ ] Displays at least the drink's:
  - [ ] Product number
  - [ ] Name
  - [ ] Manufacturer
  - [ ] Bottle size
  - [ ] Price
  - [ ] Price per liter
  - [ ] Type
  - [ ] Manufacturing country
  - [ ] Vintage
  - [ ] Alcohol percentage
  - [ ] Energy in kcal per 100ml
- [ ] Storing the data in a database
- [ ] Implementing filters that can be used alone or combined for at least:
  - [ ] Type
  - [ ] Manufacturing country
  - [ ] Bottle size
  - [ ] Price (range)
  - [ ] Energy amount (range)
- [ ] The data can be refreshed directly from alko's excel file url
- [x] Display a specific title which says from which time the data is from
- [ ] Display filters below the table's title
- [ ] Display the table's data below the filters
- [ ] Variable configuration options for:
  - [x] Database connection
  - [ ] The displayed columns
  - [ ] Rows per page
- [x] Divide the application into modules

Uses [SimpleXLSX](https://github.com/shuchkin/simplexlsx) for importing the spreadsheet data from Alko.

## Setup

### Dev

The setup is made to be reliable and easy for development with docker-compose.
First copy the example environment file to `.env` and edit the values.
Then just run `docker-compose up` in this directory and it'll start a container for you.

### Production

Setup nginx to serve the `src/` folder and the `static/` folder, or copy files from each to a combined folder and only serve files from there.

## Refreshing the data

Visit `applcation-root-url/refresh.php`
Where `applcation-root-url` in the provided dev server is at `http://localhost:8080` and in the course's production system is at `http://xx.xx.xx.xx/product-catalog`, with the IP being provided in the assignment return.

Note that if we were deploying the application into production in a way that it was accessible over the internet to anyone, we would want to protect that url trough nginx with basic authentication for example so that only administrators can do it, as to avoid someone launching a dos attack trough this service against alko.
