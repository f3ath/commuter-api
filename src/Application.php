<?php
namespace F3\Commuter;

class Application
{
    private $storage;

    public function __construct(ExpirableStorage $expirable_storage)
    {
        $this->storage = $expirable_storage;
    }

    public function set(string $id, array $location): void
    {
        $this->storage->set($id, serialize($location));
    }

    public function getAll(): array
    {
        $locations = [];
        foreach ($this->storage->getAll() as $serialized) {
            $locations[] = unserialize($serialized);
        }
        return $locations;
    }
}
