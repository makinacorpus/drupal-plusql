<?php

namespace MakinaCorpus\PluSQL\Standard;

use MakinaCorpus\PluSQL\ConstraintExistsException;

/**
 * Foreign key constraint
 *
 * This is based upon the Drupal standard hook_schema() foreign key definition
 * on which you should add the 'delete' key to trigger. If the 'delete' key is
 * omited, no constraint will be set.
 *
 * 'delete' key might be one of the following:
 *   - 'cascade': refers to ON DELETE CASCADE
 *   - 'set nul': refers to ON DELETE SET NULL
 *   - 'restrict': register the foreign key but do net set ON DELETE behaviour
 *     (breaks on violation)
 *
 * Exemple in you hook_schema:
 *
 * @code
 *   // Simple foreign key (one column)
 *   'menu_name' => [
 *     'table'   => 'menu',
 *     'columns' => ['menu_name' => 'menu_name'],
 *     'delete'  => 'cascade',
 *   ],
 *   // With multiple columns:
 *   'ucms_node_site' => [
 *     'table'   => 'ucms_node_site',
 *     'columns' => [
 *       'node_id' => 'nid',
 *       'site_id' => 'site_id',
 *     'delete'  => 'cascade',
 *   ]
 * @endcode
 */
trait ForeignKeyTrait
{
    /**
     * {@inheritdoc}
     */
    public function add($table, $name, $definition)
    {
        if (!is_array($definition)) {
            throw new \InvalidArgumentException("Invalid definition given");
        }
        if (!array_key_exists('table', $definition)) {
            throw new \InvalidArgumentException("Missing 'table' in definition");
        }
        if (!array_key_exists('columns', $definition)) {
            throw new \InvalidArgumentException("Missing 'columns' in definition");
        }
        if (!array_key_exists('delete', $definition)) {
            throw new \InvalidArgumentException("Missing 'delete' in definition");
        }

        switch ($definition['delete']) {

            case 'cascade':
                $suffix = " ON DELETE CASCADE";
                break;

            case 'set null':
                $suffix = " ON DELETE SET NULL";
                break;

            case 'restrict':
                $suffix = "";
                break;

            default:
                throw new \InvalidArgumentException(sprintf("'%s' delete behavior unknown, must be on of 'cascade', 'set null' or 'restrict'", $definition['delete']));
        }

        $constraintName = $this->getSqlName($table, $name);
        $foreignTable = $definition['table'];
        $columns = implode(', ', array_keys($definition['columns']));
        $foreignColumns = implode(', ', $definition['columns']);

        $query = "ALTER TABLE {{$table}} ADD CONSTRAINT {{$constraintName}} FOREIGN KEY ({$columns}) REFERENCES {{$foreignTable}} ({$foreignColumns}) $suffix";

var_dump($query);
        try {
            $this->getConnection()->query($query);
        } catch (\PDOException $e) {

            switch ($e->getCode()) {

              case 42710: // PostgreSQL constraint already exists
                  throw new ConstraintExistsException(sprintf("Foreign key '%s' already exists", $constraintName), null, $e);

              case 23000: // MySQL duplicate key in table
                  throw new ConstraintExistsException(sprintf("Foreign key '%s' already exists", $constraintName), null, $e);

              default:
                  throw $e;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findAllInTable($table, array $definition)
    {
        $ret = [];

        if (isset($definition['foreign keys'])) {
            foreach ($definition['foreign keys'] as $name => $item) {
                if (isset($item['delete'])) {
                    $ret[$name] = $definition['foreign keys'][$name];
                }
            }
        }
var_dump($ret);
        return $ret;
    }
}
