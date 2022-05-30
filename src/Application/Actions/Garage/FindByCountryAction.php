<?php
declare(strict_types=1);

namespace PmnGarage\Application\Actions\Garage;

use PmnGarage\Domain\Garage;
use Neomerx\JsonApi\Encoder\Encoder;
use PmnGarage\Resource\GarageResourceSchema;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * @see \PmnGarage\Tests\Application\Actions\FindByCountryActionTest
 */
class FindByCountryAction extends BaseGarageAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $country = $this->request->getAttribute('country');
        $garages = $this->garageRepository->searchByCountry($country);

        $this->logger->info("Garage FindByCountryAction was called.");

        $encodedData = Encoder::instance([Garage::class => GarageResourceSchema::class])
            ->encodeData($garages);

        return $this->respond($encodedData);
    }
}
