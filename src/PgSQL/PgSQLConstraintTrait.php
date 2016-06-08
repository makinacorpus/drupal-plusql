<?php

namespace MakinaCorpus\PluSQL\PgSQL;

use MakinaCorpus\PluSQL\ConstraintTrait;

trait PgSQLConstraintTrait
{
    use ConstraintTrait;

    /**
     * {@inheritdoc}
     */
    public function existsWithName($table, $name)
    {
        $query = "SELECT 1 FROM pg_constraint WHERE conname = '$name'";

        return (bool)$this->getConnection()->query($query)->fetchField();
    }
}
