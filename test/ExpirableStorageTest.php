<?php
namespace F3\Commuter;

use PHPUnit\Framework\TestCase;

class ExpiringStorageTest extends TestCase
{
    public function testItemsExpire()
    {
        $storage = $this->createStorage();
        $this->assertStorageContains([], $storage, 'New storage is empty');
        $storage->set('foo', 'foo');
        $this->sleep();
        $this->assertStorageContains(['foo'], $storage, 'Storage contains foo');
        $storage->set('bar', 'bar');
        $this->assertStorageContains(['foo', 'bar'], $storage, 'Storage contains both foo and bar');
        $this->sleep();
        $this->assertStorageContains(['bar'], $storage, 'foo has expired, only bar remains');
        $this->sleep();
        $this->assertStorageContains([], $storage, 'All items have expired');
    }

    public function testItemsCanBeReplaced()
    {
        $storage = $this->createStorage();
        $storage->set('foo', 'foo');
        $storage->set('foo', 'bar');
        $this->assertStorageContains(['bar'], $storage, 'Storage contains bar, not foo');
    }

    private function assertStorageContains(array $items, ExpirableStorage $storage, string $message): void
    {
        $this->assertEquals($items, iterator_to_array($storage->getAll()), $message);
    }

    private function sleep(): void
    {
        usleep(.06 * 1000000);
    }

    private function createStorage(): ExpirableStorage
    {
        return ExpirableStorage::createInMemory(0.1);
    }
}
