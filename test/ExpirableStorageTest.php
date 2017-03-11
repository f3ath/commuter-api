<?php
namespace F3\Commuter;

use PHPUnit\Framework\TestCase;

class ExpiringStorageTest extends TestCase
{
    public function testLocationsExpire()
    {
        $storage = ExpirableStorage::createInMemory(0.1);
        $this->assertStorageContains([], $storage, 'New storage is empty');
        $storage->add('foo');
        $this->sleep(.06);
        $this->assertStorageContains(['foo'], $storage, 'Storage contains foo');
        $storage->add('bar');
        $this->assertStorageContains(['foo', 'bar'], $storage, 'Storage contains both foo and bar');
        $this->sleep(.06);
        $this->assertStorageContains(['bar'], $storage, 'foo has expired, only bar remains');
        $this->sleep(.06);
        $this->assertStorageContains([], $storage, 'All items have expired');
    }

    private function assertStorageContains(array $items, ExpirableStorage $storage, string $message): void
    {
        $this->assertEquals($items, iterator_to_array($storage->getAll()), $message);
    }

    private function sleep(float $seconds): void
    {
        usleep($seconds * 1000000);
    }
}
