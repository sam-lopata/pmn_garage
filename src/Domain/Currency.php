<?php
declare(strict_types=1);

namespace PmnGarage\Domain;

use Doctrine\ORM\Mapping\{Id, Table, Column, Entity, GeneratedValue};
use JsonSerializable;

#[Entity, Table(name: 'currencies')]
class Currency implements JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private ?int $id;

    #[Column(type: 'string', length: 3, unique: true, nullable: false)]
    private string $code;

    public function __construct(?int $id, string $code)
    {
        $this->id   = $id;
        $this->code = $code;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
        ];
    }
}
