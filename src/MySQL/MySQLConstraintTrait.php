<?php

namespace MakinaCorpus\PluSQL\MySQL;

use MakinaCorpus\PluSQL\ConstraintTrait;

trait MySQLConstraintTrait
{
    use ConstraintTrait;

    /**
     * {@inheritdoc}
     */
    protected function existsWithName(string $table, string $name): bool
    {
        $query = <<<EOT
SELECT 1 FROM information_schema.TABLE_CONSTRAINTS
WHERE
   CONSTRAINT_SCHEMA = DATABASE() AND
   CONSTRAINT_NAME   = '$name'
;
EOT;

        return (bool)$this->getConnection()->query($query)->fetchField();
    }
}
