<?php

namespace MakinaCorpus\PluSQL;

/**
 * Default implementation matches
 */
trait ConstraintTrait
{
    private $type;
    private $connection;

    /**
     * Defautl constructor
     *
     * @param \DatabaseConnection $connection
     * @param string $type
     */
    public function __construct(\DatabaseConnection $connection, $type)
    {
        $this->connection = $connection;
        $this->type = $type;
    }

    /**
     * Get connection
     *
     * @return \DatabaseConnection
     */
    final public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get type
     *
     * @return string
     */
    final public function getType()
    {
        return $this->type;
    }

    /**
     * Get SQL constraint name
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     *
     * @return string
     */
    public function getSqlName($table, $name)
    {
        return $table . '_' . $this->getType() . '_' . $name;
    }

    /**
     * Doe the constraint with given name exists (please do not prefix)
     *
     * @param string $table
     * @param string $name
     *
     * @return boolean
     */
    abstract protected function existsWithName($table, $name);

    /**
     * {@inheritdoc}
     */
    final public function exists($table, $name)
    {
        return $this->existsWithName($table, $this->getSqlName($table, $name));
    }

    /**
     * {@inheritdoc}
     */
    final public function existsUnsafe($table, $name)
    {
        return $this->existsWithName($table, $name);
    }

    /**
     * Drop constraint with given name (please do not prefix)
     *
     * @param string $table
     * @param string $name
     */
    protected function dropWithName($table, $name)
    {
        // This is not fully standard, I guess, but should work with most SQL
        // databases, except MySQL which will never do like the others. Anyway
        // you probably should never use MySQL in the first place.
        $this->getConnection()->query("ALTER TABLE {{$table}} DROP CONSTRAINT {{$name}}");
    }

    /**
     * {@inheritdoc}
     */
    final public function drop($table, $name)
    {
        return $this->dropWithName($table, $this->getSqlName($table, $name));
    }

    /**
     * {@inheritdoc}
     */
    final public function dropUnsafe($table, $name)
    {
        return $this->dropWithName($table, $name);
    }
}
