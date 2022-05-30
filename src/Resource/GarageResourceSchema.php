<?php
declare(strict_types=1);

namespace PmnGarage\Resource;

use PmnGarage\Domain\Garage;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

class GarageResourceSchema extends BaseSchema
{
    public const RESOURCE_TYPE       = 'Garage';

    public function getType(): string
    {
        return static::RESOURCE_TYPE;
    }

    /**
     * @param Garage $resource
     *
     * @return string|null
     */
    public function getId($resource): ?string
    {
        return (string)$resource->getId();
    }

    /**
     * @param Garage           $resource
     * @param ContextInterface $context
     *
     * @return iterable
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            'garage_id' => $resource->getId(),
            'name' => $resource->getName(),
            'hourly_price' => number_format($resource->getPrice()/100, 2, '.', ' '),
            'currency' => $resource->getCurrency()->getCode(),
            'contact_email' => $resource->getOwner()->getEmail(),
            'point' => sprintf(
                "%s %s",
                $resource->getCoordinates()->getLongitude(),
                $resource->getCoordinates()->getLatitude()
            ),
            'country' => $resource->getCountry()->getName(),
            'owner_id' => $resource->getOwner()->getId(),
            'garage_owner' => $resource->getOwner()->getName()
        ];
    }

    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getLinks($resource): iterable
    {
        return [];
    }
}
