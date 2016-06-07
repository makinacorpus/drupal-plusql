<?php

namespace MakinaCorpus\PluSQL\PgSQL;

use MakinaCorpus\PluSQL\ConstraintTrait;

trait PgSQLConstraintTrait
{
    use ConstraintTrait;

    /**
     * {@inheritdoc}
     */
    public function exists($table, $name)
    {
        $constaintName = $this->getSqlName($table, $name);
        $query = "SELECT 1 FROM pg_constraint WHERE conname = '$constaintName'";

        return (bool)$this->getConnection()->query($query)->fetchField();
    }
}
