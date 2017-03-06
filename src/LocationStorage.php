<?php
namespace F3\Commuter;

class LocationStorage
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(Location $location): void
    {
        $insert = $this->pdo->prepare('INSERT INTO locations (id, lat, lng, updated) VALUES (:id, :lat, :lng, :u)');
        $insert->execute([
            'id' => $location->getId(),
            'lat' => $location->getLat(),
            'lng' => $location->getLng(),
            'u' => time(),
        ]);
    }

    /**
     * @return Location[]
     */
    public function fetchRecent(): array
    {
        $select = $this->pdo->prepare('SELECT * FROM locations WHERE updated > :delta');
        $select->execute([
            'delta' => time() - 60,
        ]);
        $locations = [];
        foreach ($select->fetchAll(\PDO::FETCH_ASSOC) as $dto) {
            $locations[] = new Location($dto['id'], $dto['lat'], $dto['lng']);
        }
        return $locations;
    }
}
