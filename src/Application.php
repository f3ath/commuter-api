<?php
declare(strict_types = 1);

namespace F3\Commuter;

class Application
{
    private $pdo;
    private $now;

    public function __construct(\PDO $pdo, \DateTimeInterface $now)
    {
        $this->pdo = $pdo;
        $this->now = $now;
    }

    public function initStorage()
    {
        $this->pdo->exec(
            'CREATE TABLE locations (
              map_id TEXT, 
              location_id TEXT, 
              description TEXT, 
              type TEXT, 
              lat FLOAT, 
              lng FLOAT,
              expires INTEGER,
              PRIMARY KEY (map_id, location_id)
            )'
        );
        $this->pdo->exec('CREATE INDEX expires_map_idx ON locations (expires, map_id)');
    }

    public function addLocation(string $map_id, array $location): void
    {
        $this->clearStorage();
        $insert = $this->pdo->prepare(
            'INSERT OR REPLACE INTO locations 
              (map_id, location_id, lat, lng, expires, description, type) 
            VALUES 
              (:map_id, :loc_id, :lat, :lng, :exp, :description, :type)'
        );
        $insert->execute([
            'map_id'      => $map_id,
            'loc_id'      => $location['id'],
            'lat'         => $location['lat'],
            'lng'         => $location['lng'],
            'description' => $location['description'],
            'type'        => $location['type'],
            'exp'         => $this->now->getTimestamp() + $location['expires'],
        ]);
    }

    public function getLocations(string $map_id): array
    {
        $select = $this->pdo->prepare(
            'SELECT location_id AS id, lat, lng, description, type FROM locations WHERE map_id = :map_id AND expires > :exp'
        );
        $select->execute([
            'map_id' => $map_id,
            'exp'    => $this->now->getTimestamp(),
        ]);
        $locations = [];
        foreach ($select->fetchAll(\PDO::FETCH_ASSOC) as $location) {
            $location['lat'] = (float)$location['lat'];
            $location['lng'] = (float)$location['lng'];
            $locations[] = $location;
        };
        return $locations;
    }

    private function clearStorage(): void
    {
        if (mt_rand(0, 100) < 1) {
            return;
        }
        $delete = $this->pdo->prepare('DELETE FROM locations WHERE expires < :exp');
        $delete->execute(['exp' => $this->now->getTimestamp()]);
    }
}
