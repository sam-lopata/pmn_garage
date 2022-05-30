<?php
declare(strict_types=1);

namespace PmnGarage\Domain;

interface GarageRepositoryInterface
{
    public function searchByCountry(string $country);
}
