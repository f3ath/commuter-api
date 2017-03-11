<?php
namespace F3\Commuter;

class Application
{
    private $expirable_storage;

    public function __construct(ExpirableStorage $expirable_storage)
    {
        $this->expirable_storage = $expirable_storage;
    }

    public function add(array $location): void
    {
        $this->expirable_storage->add(serialize($location));
    }

    public function getAll(): array
    {
        $locations = [];
        foreach ($this->expirable_storage->getAll() as $serialized)
        {
            $locations[] = unserialize($serialized);
        }
        return $locations;
    }
}
