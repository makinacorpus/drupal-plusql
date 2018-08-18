<?php

namespace MakinaCorpus\PluSQL;

use Drupal\Core\Database\Connection;

interface ConstraintInterface
{
    /**
     * Get connection
     */
    public function getConnection(): Connection;

    /**
     * Get type
     */
    public function getType(): string;

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
    public function getSqlName(string $table, string $name): string;

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
    public function add(string $table, string $name, $definition);

    /**
     * Drop SQL constraint
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     */
    public function drop(string $table, string $name);

    /**
     * Drop SQL constraint without prefixing name
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     */
    public function dropUnsafe(string $table, string $name);

    /**
     * Does this contstraint exist
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     *
     * @return bool
     */
    public function exists(string $table, string $name): bool;

    /**
     * Does this contstraint exist without prefixing name
     *
     * @param string $table
     *   Table name.
     * @param string $name
     *   Constraint name.
     *
     * @return bool
     */
    public function existsUnsafe(string $table, string $name): bool;

    /**
     * Find all constraints in given table.
     *
     * @param string $table
     * @param array $definition
     *
     * @return array
     */
    public function findAllInTable(string $table, array $definition): array;
}
