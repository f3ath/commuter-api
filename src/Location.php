<?php
namespace F3\Commuter;

class Location
{
    private $lat;
    private $lng;

    public function __construct(float $lat, float $lon)
    {
        $this->lat = $lat;
        $this->lng = $lon;
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
