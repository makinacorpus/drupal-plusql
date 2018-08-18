<?php

namespace MakinaCorpus\PluSQL;

use Drupal\Core\Database\Connection;

/**
 * Default implementation matches
 */
trait ConstraintTrait
{
    private $type;
    private $connection;

    /**
     * Default constructor
     */
    public function __construct(Connection $connection, string $type)
    {
        $this->connection = $connection;
        $this->type = $type;
    }

    /**
     * Get connection
     */
    final public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * Get type
     */
    final public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get SQL constraint name
     */
    public function getSqlName(string $table, string $name): string
    {
        return $table . '_' . $this->getType() . '_' . $name;
    }

    /**
     * Doe the constraint with given name exists (please do not prefix)
     */
    abstract protected function existsWithName(string $table, string $name): bool;

    /**
     * {@inheritdoc}
     */
    final public function exists(string $table, string $name): bool
    {
        return $this->existsWithName($table, $this->getSqlName($table, $name));
    }

    /**
     * {@inheritdoc}
     */
    final public function existsUnsafe(string $table, string $name): bool
    {
        return $this->existsWithName($table, $name);
    }

    /**
     * Drop constraint with given name (please do not prefix)
     */
    protected function dropWithName(string $table, string $name)
    {
        // This is not fully standard, I guess, but should work with most SQL
        // databases, except MySQL which will never do like the others. Anyway
        // you probably should never use MySQL in the first place.
        $this->getConnection()->query("ALTER TABLE {{$table}} DROP CONSTRAINT {{$name}}");
    }

    /**
     * {@inheritdoc}
     */
    final public function drop(string $table, string $name)
    {
        $this->dropWithName($table, $this->getSqlName($table, $name));
    }

    /**
     * {@inheritdoc}
     */
    final public function dropUnsafe(string $table, string $name)
    {
        $this->dropWithName($table, $name);
    }
}
