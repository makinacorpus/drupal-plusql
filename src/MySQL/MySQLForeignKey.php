<?php

namespace MakinaCorpus\PluSQL\MySQL;

use MakinaCorpus\PluSQL\Standard\ForeignKeyTrait;
use MakinaCorpus\PluSQL\ConstraintInterface;

/**
 * MySQL foreign key constraint handler
 */
class MySQLForeignKey implements ConstraintInterface
{
    use MySQLConstraintTrait;
    use ForeignKeyTrait;

    /**
     * {@inheritdoc}
     */
    protected function dropWithName($table, $name)
    {
        $this->getConnection()->query("ALTER TABLE {{$table}} DROP FOREIGN KEY {{$name}}");
    }
}
