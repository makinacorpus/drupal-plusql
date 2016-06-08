<?php

namespace MakinaCorpus\PluSQL;

interface ConstraintInterface
{
    /**
     * Get connection
     *
     * @return \DatabaseConnection
     */
    public function getConnection();

    /**
     * Get type
     *
     * @return string
     */
    public function getType();

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
    public function getSqlName($table, $name);

    /**
     * Create constraint
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     * @param string|array $definition
     *   Constraint definition from schema, can be either an array that correspond
     *   to whatever is set in the hook_schema() or an arbitrary string, depending
     *   upon the implementation. This allows to set arbitrary constraint strings
     *   using the 'arbitrary' type.
     */
    public function add($table, $name, $definition);

    /**
     * Drop SQL constraint
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     *
     * @return string
     */
    public function drop($table, $name);

    /**
     * Drop SQL constraint without prefixing name
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     *
     * @return string
     */
    public function dropUnsafe($table, $name);

    /**
     * Does this contstraint exist
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     *
     * @return string
     */
    public function exists($table, $name);

    /**
     * Does this contstraint exist without prefixing name
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     *
     * @return string
     */
    public function existsUnsafe($table, $name);

    /**
     * Find all constraints in given table.
     *
     * @param string $table
     * @param array $definition
     */
    public function findAllInTable($table, array $definition);
}
