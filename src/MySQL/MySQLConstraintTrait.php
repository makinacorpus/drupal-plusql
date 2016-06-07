<?php

namespace MakinaCorpus\PluSQL\MySQL;

use MakinaCorpus\PluSQL\ConstraintTrait;

trait MySQLConstraintTrait
{
    use ConstraintTrait;

    /**
     * {@inheritdoc}
     */
    public function exists($table, $name)
    {
        $constaintName = $this->getSqlName($table, $name);
        $query = <<<EOT
SELECT 1 FROM information_schema.TABLE_CONSTRAINTS
WHERE
   CONSTRAINT_SCHEMA = DATABASE() AND
   CONSTRAINT_NAME   = '$constaintName'
;
EOT;

        return (bool)$this->getConnection()->query($query)->fetchField();
    }
}
