<?php
declare(strict_types=1);

namespace PmnGarage\Application\Actions\Garage;

use Psr\Log\LoggerInterface;
use PmnGarage\Application\Actions\Action;
use PmnGarage\Domain\GarageRepository;

abstract class BaseGarageAction extends Action
{
    protected GarageRepository $garageRepository;

    /**
     * @param LoggerInterface $logger
     * @param GarageRepository  $garageRepository
     */
    public function __construct(LoggerInterface $logger, GarageRepository $garageRepository)
    {
        parent::__construct($logger);
        $this->garageRepository = $garageRepository;
    }
}
