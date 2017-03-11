<?php
namespace F3\Commuter;

class ExpirableStorage
{
    private $pdo;
    private $lifetime;

    public function __construct(\PDO $pdo, float $lifetime_seconds)
    {
        $this->pdo = $pdo;
        $this->lifetime = $lifetime_seconds;
    }

    public static function createInMemory(float $lifetime_seconds)
    {
        $storage = new self(self::createInMemoryPDO(), $lifetime_seconds);
        $storage->init();
        return $storage;
    }

    private static function createInMemoryPDO(): \PDO
    {
        $pdo_options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
        return new \PDO('sqlite::memory:', null, null, $pdo_options);
    }

    public function set(string $id, string $value)
    {
        $this->cleanRandomly();
        $insert = $this->pdo->prepare('INSERT OR REPLACE INTO storage (id, val, expires) VALUES (:id, :val, :exp)');
        $parameters = [
            'id' => $id,
            'val' => serialize($value),
            'exp' => microtime(true) + $this->lifetime,
        ];
        $insert->execute($parameters);
    }

    public function getAll(): \Iterator
    {
        $select = $this->pdo->prepare('SELECT val FROM storage WHERE expires > :e');
        $parameters = [
            'e' => microtime(true),
        ];
        $select->execute($parameters);
        while ($row = $select->fetch(\PDO::FETCH_ASSOC)) {
            yield unserialize($row['val']);
        }
    }

    public function init(): void
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS storage (id TEXT PRIMARY KEY, val TEXT, expires REAL)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS expires_ind ON storage (expires)');
    }

    public function clean()
    {
        $delete = $this->pdo->prepare('DELETE FROM storage WHERE expires < :e');
        $delete->execute([
            'e' => microtime(true),
        ]);
    }

    private function cleanRandomly(): void
    {
        if (mt_rand(0, 9) < 1) {
            $this->clean();
        }
    }
}
