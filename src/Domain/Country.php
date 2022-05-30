<?php
declare(strict_types=1);

namespace PmnGarage\Domain;

use Doctrine\ORM\Mapping\{Id, Table, Column, Entity, GeneratedValue};
use JsonSerializable;

#[Entity, Table(name: 'countries')]
class Country implements JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private ?int $id;

    #[Column(type: 'string', length: 64, unique: true, nullable: false)]
    private string $name;

    public function __construct(?int $id, string $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
