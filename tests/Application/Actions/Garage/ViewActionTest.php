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
use Slim\Exception\HttpNotFoundException;
use CrEOF\Spatial\PHP\Types\Geography\Point;

/**
 * @covers \PmnGarage\Application\Actions\Garage\ViewAction
 */
class ViewActionTest extends TestCase
{
    public function testActionSuccess()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $countryName = 'USA';
        $garageId = 1;
        $point = new Point(10, 20);
        $currency = new Currency(1, '$');
        $country = new Country(1, $countryName);
        $owner = new Owner(1, 'AutoPark', 'testemail@testautopark.fi');
        $garage = new Garage( $garageId,'Garage1', 123, $point, $owner, $currency, $country);

        $garageRepositoryProphecy = $this->prophesize(GarageRepository::class);
        $garageRepositoryProphecy
            ->findOneBy(['id' => $garageId])
            ->willReturn([$garage])
            ->shouldBeCalledOnce();

        $container->set(GarageRepository::class, $garageRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/garages/' . $garageId);
        $response = $app->handle($request);

        $expectedPayload = '{"data":[{"type":"Garage","id":"1","attributes":{"garage_id":1,"name":"Garage1","hourly_price":"1.23","currency":"$","contact_email":"testemail@testautopark.fi","point":"10 20","country":"USA","owner_id":1,"garage_owner":"AutoPark"}}]}';
        self::assertSame($expectedPayload, (string)$response->getBody());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testActionNotFound()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $garageId = 10;
        $garageRepositoryProphecy = $this->prophesize(GarageRepository::class);
        $garageRepositoryProphecy
            ->findOneBy(['id' => $garageId])
            ->willReturn(null)
            ->shouldBeCalledOnce();

        $container->set(GarageRepository::class, $garageRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/garages/' . $garageId);

        try {
            $app->handle($request);
            self::fail(HttpNotFoundException::class . ' was not thrown');
        } catch (HttpNotFoundException $e) {
            self::assertStringContainsString("Garage id:$garageId not found", $e->getMessage());
        }
    }
}
