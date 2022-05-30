<?php
declare(strict_types=1);

namespace PmnGarage\Tests\Application\Actions;

use DI\Container;
use PmnGarage\Domain\Owner;
use PmnGarage\Domain\Garage;
use PmnGarage\Tests\TestCase;
use PmnGarage\Domain\Country;
use PmnGarage\Domain\Currency;
use PmnGarage\Domain\GarageRepository;
use CrEOF\Spatial\PHP\Types\Geography\Point;

/**
 * @covers \PmnGarage\Application\Actions\Garage\FindByCountryAction
 */
class FindByCountryActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $countryName = 'USA';
        $point = new Point(10, 20);
        $currency = new Currency(1, '$');
        $country = new Country(1, $countryName);
        $owner = new Owner(1, 'AutoPark', 'testemail@testautopark.fi');
        $garage = new Garage( 1,'Garage1', 123, $point, $owner, $currency, $country);

        $garageRepositoryProphecy = $this->prophesize(GarageRepository::class);
        $garageRepositoryProphecy
            ->searchByCountry($countryName)
            ->willReturn([$garage])
            ->shouldBeCalledOnce();

        $container->set(GarageRepository::class, $garageRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/garages/country/' . $countryName);
        $response = $app->handle($request);

        $expectedPayload = '{"data":[{"type":"Garage","id":"1","attributes":{"garage_id":1,"name":"Garage1","hourly_price":"1.23","currency":"$","contact_email":"testemail@testautopark.fi","point":"10 20","country":"USA","owner_id":1,"garage_owner":"AutoPark"}}]}';
        self::assertSame($expectedPayload, (string)$response->getBody());
        self::assertEquals(200, $response->getStatusCode());
    }
}
