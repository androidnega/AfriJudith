<?php
/**
 * Base Model.
 *
 * For now there is no database; models return curated static data.
 * When a database is wired up later, this base class is the single
 * place to add a PDO connection helper.
 */

declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected array $config;
    protected ?\PDO $db = null;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Lazily open a PDO connection when (and only when) a child model
     * actually needs the database. Until then the website runs without
     * any DB at all.
     */
    protected function db(): \PDO
    {
        if ($this->db instanceof \PDO) {
            return $this->db;
        }

        $cfg = $this->config['database'] ?? [];
        if (empty($cfg['enabled'])) {
            throw new \RuntimeException('Database is not enabled in config.');
        }

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $cfg['driver'],
            $cfg['host'],
            (int) $cfg['port'],
            $cfg['name'],
            $cfg['charset']
        );

        $this->db = new \PDO($dsn, $cfg['user'], $cfg['pass'], [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        return $this->db;
    }
}
