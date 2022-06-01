<?php
declare(strict_types=1);

namespace PmnGarage\Domain;

use Doctrine\ORM\EntityRepository;

class GarageRepository extends EntityRepository implements GarageRepositoryInterface
{
    public function searchByCountry(string $country)
    {
        $query = $this->getEntityManager()->createQuery(
            sprintf(
                "SELECT g FROM %s g LEFT JOIN g.country c WHERE c.name = :country",
                Garage::class
            )
        )
            ->setParameter('country', $country);

        return $query->getResult();
    }
}
