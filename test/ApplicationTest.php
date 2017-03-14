<?php
namespace F3\Commuter;

use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * @var Application
     */
    private $app;
    private $pdo;

    protected function setUp()
    {
        $pdo_options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
        $this->pdo = new \PDO('sqlite::memory:', null, null, $pdo_options);
        $this->initApplication(new \DateTime('2000-01-01 00:00:00'));
        $this->app->initStorage();
    }

    public function testMapIsEmpty()
    {
        $this->assertLocations([], 'my_map');
    }

    public function testSetLocationReadLocations()
    {
        $location = $this->createRandomLocation();
        $this->addLocation($location, 'my_map');
        $this->assertLocations([$location], 'my_map');
    }

    public function testSetLocationCanBeCalledManyTimes()
    {
        $location = $this->createRandomLocation();
        $this->addLocation($location, 'my_map');
        $this->assertLocations([$location], 'my_map');
        $location['lat'] = 11.22;
        $this->addLocation($location, 'my_map');
        $this->assertLocations([$location], 'my_map');
    }

    public function testLocationBelongsToMap()
    {
        $a = $this->createRandomLocation();
        $b = $this->createRandomLocation();
        $this->addLocation($a, 'my_map_0');
        $this->addLocation($b, 'my_map_1');
        $this->assertLocations([$a], 'my_map_0');
        $this->assertLocations([$b], 'my_map_1');
    }

    public function testLocationsExpire()
    {
        $a = $this->createRandomLocation(['expires' => 10]);
        $b = $this->createRandomLocation(['expires' => 20]);
        $this->addLocation($a, 'my_map');
        $this->addLocation($b, 'my_map');
        $this->assertLocations([$a, $b], 'my_map');
        $this->initApplication(new \DateTime('2000-01-01 00:00:15'));
        $this->assertLocations([$b], 'my_map');
    }

    private function initApplication(\DateTime $time)
    {
        $this->app = new Application($this->pdo, $time);
    }

    private function createRandomLocation(array $filter = []): array
    {
        return array_replace_recursive(
            [
                'id'      => 'my_point' . mt_rand(),
                'lat'     => 1 + mt_rand(0, 1000) / 1000,
                'lng'     => 2 + mt_rand(0, 1000) / 1000,
                'expires' => 1,
            ],
            $filter
        );
    }

    private function assertLocations(array $locations, string $map_id): void
    {
        $this->assertSameLocationSets($locations, $this->app->getLocations($map_id));
    }

    private function assertSameLocationSets(array $a, array $b): void
    {
        sort($a);
        sort($b);
        $filter = function (array $a) {
            return array_intersect_key($a, [
                'id'  => 1,
                'lat' => 1,
                'lng' => 1,
            ]);
        };
        $this->assertSame(array_map($filter, $a), array_map($filter, $b), '');
    }

    private function addLocation($location, string $map_id): void
    {
        $this->app->addLocation($map_id, $location);
    }
}
