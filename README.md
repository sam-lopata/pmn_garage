# pmn_garage
Garages life emulation.

## Note
The app implemented on top of  https://github.com/slimphp/Slim-Skeleton - I decided to use it because you mentioned it as your main tool.
But changed the structure slightly. Overall nice tool.

As all the actions to fetch by country/owner/location etc. will be pretty same I implemented only GarageRepository::searchByCountry().
And there are also 2 default actions to list all and view one item. Same with tests - implemented couple as example.

I decided to follow official JSON API (https://jsonapi.org) and use https://github.com/neomerx/json-api encoder to be strict on JSON spec. 
I believe that is proper implementation which insures from future problems with the API grow. 
It has a lot of benefits, few obvious are that implementation allows to have included resources, relationships and links - which later will ease frontend app development, 
i.e. the navigation might be autogenerated, requesting one resource with all included reduces amount of call and more.
You will note that difference. The produced format is:
```
{
    "data":
    {
        "type": "Garage",
        "id": "10",
        "attributes":
        {
            "garage_id": 10,
            "name": "TestGarage9",
            "hourly_price": "12.09",
            "currency": "$",
            "contact_email": "testemail@testautopark.fi",
            "point": "100 20",
            "country": "Finland",
            "owner_id": 1,
            "garage_owner": "AutoPark"
        }
    }
}
```
Additionally added CI build workflow to Github actions.

## Requirements
- git
- composer
- docker
- docker-compose

## Configure app
Checkout repo
```
git clone https://github.com/sam-lopata/pmn_garage.git .
```

Fetch dependencies:
```
composer install
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
    response:
        200 - Success, returns array of items
        500 - Internal error
GET /garages/{id} - get garage by id
    response:
        200 - Success, returns one item
        404 - Item not found
        500 - Internal error
GET /garages/country/{country} - get garages by country
    response: 
        200 - Success
        500 - Internal error
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
