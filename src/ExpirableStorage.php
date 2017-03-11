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
        $storage = new self(new \PDO('sqlite::memory:'), $lifetime_seconds);
        $storage->init();
        return $storage;
    }

    public function add(string $value)
    {
        $insert = $this->pdo->prepare('INSERT INTO storage (val, expires) VALUES (:v, :e)');
        $parameters = [
            'v' => serialize($value),
            'e' => microtime(true) + $this->lifetime,
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
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS storage (id INTEGER PRIMARY KEY, val TEXT, expires REAL)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS ON storage (expires)');
    }
}
