<?php
namespace F3\Commuter;

use Ramsey\Uuid\Uuid;

class Location
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $lng;

    public function __construct(string $id, float $lat, float $lng)
    {
        $this->id = $id;
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public static function fromLatLng(float $lat, float $lng): self
    {
        return new self(
            Uuid::uuid4(),
            $lat,
            $lng
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }
}
