<?php
declare(strict_types=1);

namespace PmnGarage\Application\Actions\Garage;

use PmnGarage\Domain\Garage;
use Neomerx\JsonApi\Encoder\Encoder;
use PmnGarage\Resource\GarageResourceSchema;
use Psr\Http\Message\ResponseInterface as Response;

class ListAction extends BaseGarageAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $garages = $this->garageRepository->findAll();

        $this->logger->info("Garage ListAction was called.");

        $encodedData = Encoder::instance([Garage::class => GarageResourceSchema::class])
            ->encodeData($garages);

        return $this->respond($encodedData);
    }
}
