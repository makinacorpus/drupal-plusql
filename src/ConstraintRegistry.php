<?php

namespace MakinaCorpus\PluSQL;

class ConstraintRegistry
{
    private $registry = [];
    private $instances = [];

    /**
     * Default constructor
     *
     * @param string $definitions
     *   Handler definitions, first dimension keys are drive names, second
     *   dimension keys are constraint type names, terminal values are class
     *   names which must implement ConstraintInterface
     */
    public function __construct($definitions = [])
    {
        $this->registry = $definitions;
    }

    /**
     * Create instance for the given connection
     *
     * @todo allow dynamic or configuration-based registration
     *
     * @param \DatabaseConnection $connection
     * @param string $type
     *
     * @return ConstraintInterface
     */
    protected function createInstance(\DatabaseConnection $connection, $type)
    {
        $driver = $connection->driver();

        if (!isset($this->registry[$driver][$type])) {
            throw new \InvalidArgumentException(sprintf("'%s' constraint is not supported by '%s' driver"));
        }

        $className = $this->registry[$driver][$type];

        // Consider that if the user did not register an instance, then he is
        // using the ConstraintTrait and its constructor, I do hope anyway.
        // @todo provide other means of registration
        if (!class_exists($className)) {
            throw new \InvalidArgumentException(sprintf("'%s' class does not exist", $className));
        }

        return new $className($connection, $type);
    }

    /**
     * Get constraint type handler for driver
     *
     * @param \DatabaseConnection $connection
     * @param string $type
     *
     * @return ConstraintInterface
     */
    public function get(\DatabaseConnection $connection, $type)
    {
        $driver = $connection->driver();

        if (isset($this->instances[$driver][$type])) {
            return $this->instances[$driver][$type];
        }

        return $this->instances[$driver][$type] = $this->createInstance($connection, $type);
    }

    /**
     * Get all defined handlers
     *
     * @return ConstraintInterface[]
     */
    public function getAll(\DatabaseConnection $connection)
    {
        foreach ($this->registry as $types) {
            foreach (array_keys($types) as $type) {
                $this->get($connection, $type);
            }
        }

        return $this->instances[$connection->driver()];
    }
}
