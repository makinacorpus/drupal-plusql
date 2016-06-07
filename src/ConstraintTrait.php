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
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
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
        return '{table}_' . $this->getType() . '_' . $name;
    }


    /**
     * {@inheritdoc}
     */
    public function drop($table, $name)
    {
        $constraintName = $this->getSqlName($table, $name);

        // This is not fully standard, I guess, but should work with most SQL
        // databases, except MySQL which will never do like the others. Anyway
        // you probably should never use MySQL in the first place.
        $this->getConnection()->query("ALTER TABLE {{$table}} DROP CONSTRAINT {{$constraintName}}");
    }
}
