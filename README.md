# pmn_garage

## API SPEC

## Up app
```
docker-compose build && docker-compose up
```

## Database
```
CREATE DATABASE `pmn_garage` COLLATE 'utf8_bin';
```

Creds are: pmn_garage:pmn_garage
```
CREATE USER 'pmn_garage'@'%' IDENTIFIED BY PASSWORD '*E88A85A7145C382F4C609FE1676C474BFDCA46F0';
GRANT ALL PRIVILEGES ON `pmn\_garage`.* TO 'pmn_garage'@'%';
```

Check connection
```
docker-compose run php php /var/www/check_db.php
```

Run tests
```
[docker-compose://[/Users/sam.lopata/dev_env/pmn_garage/docker-compose.yml]:php/]:php /var/www/vendor/bin/phpunit --configuration /var/www/phpunit.xml --filter PmnGarage\\Tests\\Application\\Actions\\ActionTest --test-suffix ActionTest.php /var/www/tests/Application/Actions --teamcity
```

TODO?
Migrations
Make UUID as ids
Add neormex encoder
Error when empty coordinates
Add indexes
