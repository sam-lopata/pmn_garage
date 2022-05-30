<?php
declare(strict_types=1);

namespace PmnGarage\Domain;

use JsonSerializable;
use Doctrine\ORM\Mapping\{Id, ManyToOne, Table, Column, Entity, GeneratedValue};
use CrEOF\Spatial\PHP\Types\Geography\Point;

/**
 * @see \PmnGarage\Tests\Domain\GarageTest
 */
#[Entity(repositoryClass: GarageRepository::class), Table(name: 'garages')]
class Garage implements JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private ?int $id;

    #[Column(type: 'string', length: 64, unique: true, nullable: false)]
    private string $name;

    #[Column(type: 'integer', nullable: false)]
    private ?int $price;

    #[Column(type: 'point', nullable: true)]
    private Point $coordinates;

    #[ManyToOne(targetEntity: Owner::class)]
    private $owner;

    #[ManyToOne(targetEntity: Currency::class)]
    private $currency;

    #[ManyToOne(targetEntity: Country::class)]
    private $country;

    public function __construct(?int $id, string $name, ?int $price, ?Point $coordinates, $owner, $currency, $country)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->price       = $price;
        $this->coordinates = $coordinates;
        $this->owner       = $owner;
        $this->currency    = $currency;
        $this->country     = $country;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getCoordinates(): Point
    {
        return $this->coordinates;
    }

    public function getOwner(): Owner
    {
        return $this->owner;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'price' => $this->getPrice(),
            'owner' => $this->getOwner(),
            'currency' => $this->getCurrency(),
            'country' => $this->getCountry(),
            'coordinates' => $this->getCoordinates()->getLatitude() . " " . $this->getCoordinates()->getLongitude()
        ];
    }
}
