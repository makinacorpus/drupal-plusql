<?php

namespace MakinaCorpus\PluSQL\Tests;

use MakinaCorpus\PluSQL\ConstraintRegistry;
use MakinaCorpus\PluSQL\MySQL\MySQLForeignKey;
use MakinaCorpus\PluSQL\PgSQL\PgSQLForeignKey;
use MakinaCorpus\PluSQL\Constraint;

/**
 * Tests need to happen within a Drupal environement, we don't need it to be
 * fully bootstrapped, but we do need it to have a functionning database
 * connection so that we can run stuff within the database.
 */
trait ConstraintTestTrait
{
    private $databaseKey;
    private $databaseTarget;
    private $databaseConnection;
    private $constraintRegistry;

    private function bootstrapDatabase()
    {
        $path = getenv("DRUPAL_PATH");

        if (!$path) {
            // @todo
            // Attempt to find the closest Drupal instance so far

            return false;
        }

        $target = rtrim($path, '/') . '/includes/database/database.inc';

        if (!file_exists($target)) {
            return false;
        }

        include_once DRUPAL_ROOT . '/includes/database/database.inc';
        include_once DRUPAL_ROOT . '/includes/database/log.inc';
        include_once DRUPAL_ROOT . '/includes/database/prefetch.inc';
        include_once DRUPAL_ROOT . '/includes/database/query.inc';
        include_once DRUPAL_ROOT . '/includes/database/schema.inc';
        include_once DRUPAL_ROOT . '/includes/database/select.inc';

        return true;
    }

    private function buildDatabaseCredentials($driver)
    {
        switch ($driver) {

            case 'mysql':
                $prefix = 'MYSQL';
                break;

            case 'pgsql':
                $prefix = 'PGSQL';
                break;

            default:
                throw new \InvalidArgumentException("database driver '%s' is not supported", $driver);
        }

        $info = [
            'driver'    => $driver,
            'database'  => getenv($prefix . "_BASE"),
            'username'  => getenv($prefix . "_USER"),
            'password'  => getenv($prefix . "_PASS"),
            'host'      => getenv($prefix . "_HOST"),
            'port'      => getenv($prefix . "_PORT"),
        ];

        $info = array_filter($info);
        foreach (['database', 'username', 'password'] as $key) {
            if (empty($info[$key])) {
                throw new \InvalidArgumentException("database driver '%s', missing '%s' information", $driver, $key);
            }
        }

        return $info;
    }

    /**
     * Return database connection using the given driver
     *
     * @param string $driver
     *
     * @return \DatabaseConnection
     *   Return false if the database API could not bootstrapped or if the
     *   database credentials are missing in the phpunit.xml file
     */
    final protected function getDatabaseConnection($driver)
    {
        if ($this->databaseConnection) {
            return $this->databaseConnection;
        }

        try {
            $credentials = $this->buildDatabaseCredentials();
        } catch (\Exception $e) {
            $this->markTestSkipped("'%s' driver: connection information is missing or invalid");

            return false;
        }

        $this->databaseKey = uniqid('test');
        $this->databaseTarget = $this->databaseKey;

        if (!$this->bootstrapDatabase()) {
            $this->markTestSkipped("could not bootstrap the Drupal database component");

            return false;
        }

        \Database::addConnectionInfo($this->databaseKey, $this->databaseTarget, $credentials);

        return $this->databaseConnection = \Database::getConnection($this->databaseTarget, $this->databaseKey);
    }

    /**
     * Get the constraint registry populated with this module's definitions
     *
     * @return ConstraintRegistry
     */
    final protected function getConstraintRegistry()
    {
        if ($this->constraintRegistry) {
            return $this->constraintRegistry;
        }

        $this->constraintRegistry = new ConstraintRegistry([
            'mysql' => [
                Constraint::FOREIGN_KEY => MySQLForeignKey::class,
            ],
            'pgsql' => [
                Constraint::FOREIGN_KEY => PgSQLForeignKey::class,
            ],
        ]);

        return $this->constraintRegistry;
    }
}
