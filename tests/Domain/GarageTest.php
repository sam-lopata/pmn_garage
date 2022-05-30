<?php
declare(strict_types=1);

namespace PmnGarage\Tests\Domain;

use PmnGarage\Domain\Owner;
use PmnGarage\Domain\Garage;
use PmnGarage\Tests\TestCase;
use PmnGarage\Domain\Country;
use PmnGarage\Domain\Currency;
use CrEOF\Spatial\PHP\Types\Geography\Point;


/**
 * @covers \PmnGarage\Domain\Garage
 */
class GarageTest extends TestCase
{
    public function testGetters()
    {
        $garageId = 1;
        $garageName = 'Garage1';
        $garagePrice = 123;

        $point = new Point(10, 20);
        $currencyCode = "$";
        $currency = new Currency(1, $currencyCode);

        $countryName = 'USA';
        $country = new Country(1, $countryName);

        $ownerName = 'AutoPark';
        $ownerEmail = 'testemail@testautopark.fi';
        $owner = new Owner(1, $ownerName, $ownerEmail);

        $garage = new Garage($garageId, $garageName, $garagePrice, $point, $owner, $currency, $country);

        $this->assertEquals($garageId, $garage->getId());
        $this->assertEquals($garageName, $garage->getName());
        $this->assertEquals($garagePrice, $garage->getPrice());
        $this->assertEquals($point, $garage->getCoordinates());
        $this->assertEquals($owner, $garage->getOwner());
        $this->assertEquals($currency, $garage->getCurrency());
        $this->assertEquals($country, $garage->getCountry());
    }

    public function testJsonSerialize()
    {
        $garageId = 1;
        $garageName = 'Garage1';
        $garagePrice = 123;

        $point = new Point(10, 20);
        $currencyCode = "$";
        $currency = new Currency(1, $currencyCode);

        $countryName = 'USA';
        $country = new Country(1, $countryName);

        $ownerName = 'AutoPark';
        $ownerEmail = 'testemail@testautopark.fi';
        $owner = new Owner(1, $ownerName, $ownerEmail);

        $garage = new Garage($garageId, $garageName, $garagePrice, $point, $owner, $currency, $country);

        $expectedPayload = '{"id":1,"price":123,"owner":{"id":1,"name":"AutoPark","email":"testemail@testautopark.fi"},"currency":{"id":1,"code":"$"},"country":{"id":1,"name":"USA"},"coordinates":"20 10"}';
        $this->assertEquals($expectedPayload, json_encode($garage));
    }
}
