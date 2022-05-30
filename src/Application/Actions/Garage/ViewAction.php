<?php
declare(strict_types=1);

namespace PmnGarage\Application\Actions\Garage;

use PmnGarage\Domain\Garage;
use Neomerx\JsonApi\Encoder\Encoder;
use PmnGarage\Resource\GarageResourceSchema;
use Psr\Http\Message\ResponseInterface as Response;
use PmnGarage\Domain\DomainException\DomainRecordNotFoundException;

/**
 * @see \PmnGarage\Tests\Application\Actions\ViewActionTest
 */
class ViewAction extends BaseGarageAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = $this->resolveArg('id');
        $garage = $this->garageRepository->findOneBy(['id' => $id]);

        if (null === $garage) {
            throw new DomainRecordNotFoundException(sprintf("Garage id:%s not found", $id));
        }

        $this->logger->info(sprintf("Garage id:%s was viewed", $id));

        $encodedData = Encoder::instance([Garage::class => GarageResourceSchema::class])
            ->encodeData($garage);

        return $this->respond($encodedData);
    }
}
