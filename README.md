# pmn_garage
Garages life emulation.

## Requirements
- git
- docker
- docker-compose

## Configure app
Checkout repo
```
git clone https://github.com/sam-lopata/pmn_garage.git .
```

Build and up services
```
docker-compose build && docker-compose up
```

## Prepare database
Database schema

![Alt text](pmn_garage_db.png?raw=true )

Defaults are
```
'dbname' => 'pmn_garage',
'user' => 'pmn_garage',
'password' => 'pmn_garage',
```

Create database
```
CREATE DATABASE `pmn_garage` COLLATE 'utf8_bin';
```

Create user with default credentials and privileges on created database
```
CREATE USER 'pmn_garage'@'%' IDENTIFIED BY PASSWORD '*E88A85A7145C382F4C609FE1676C474BFDCA46F0';
GRANT ALL PRIVILEGES ON `pmn\_garage`.* TO 'pmn_garage'@'%';
```

Check DB connection
```
docker-compose run php php /var/www/check_db.php
```

Run tests
```
docker-compose run php php /var/www/vendor/bin/phpunit --configuration /var/www/phpunit.xml /var/www/tests 
```

## What is onboard:
App should be accessible on http://docker.local/garages

Adminer(mysql web panel) should be accessible on http://docker.local:8080

Where docker.local is your docker host

## Implemented endpoints:
```
GET /garages - get all garage items
GET /garages/{id} - get garage by id
GET /garages/country/{country} - get garages by country
```

## Example of execution of query to fetch garages by country
```
EXPLAIN EXTENDED SELECT * FROM garages g
LEFT JOIN countries co ON co.id = g.country_id
LEFT JOIN currencies cu ON cu.id = g.currency_id
LEFT JOIN owners o ON o.id = g.owner_id
WHERE co.name = 'Finland'


|id |select_type|table|type  |possible_keys                |key                  |key_len|ref                     |rows|filtered|Extra      |
|---|-----------|-----|------|-----------------------------|---------------------|-------|------------------------|----|--------|-----------|
|1  |SIMPLE     |co   |const |PRIMARY,UNIQ_5D66EBAD5E237E06|UNIQ_5D66EBAD5E237E06|258    |const                   |1   |100.00  |Using index|
|1  |SIMPLE     |g    |ref   |IDX_8C4330E2F92F3E70         |IDX_8C4330E2F92F3E70 |5      |const                   |297 |100.00  |           |
|1  |SIMPLE     |cu   |eq_ref|PRIMARY                      |PRIMARY              |4      |pmn_garage.g.currency_id|1   |100.00  |Using where|
|1  |SIMPLE     |o    |eq_ref|PRIMARY                      |PRIMARY              |4      |pmn_garage.g.owner_id   |1   |100.00  |Using where|

```
