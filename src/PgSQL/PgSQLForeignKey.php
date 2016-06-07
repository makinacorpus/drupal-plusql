<?php

namespace MakinaCorpus\PluSQL\PgSQL;

use MakinaCorpus\PluSQL\ConstraintInterface;
use MakinaCorpus\PluSQL\Standard\ForeignKeyTrait;

/**
 * PgSQL foreign key constraint handler
 */
class PgSQLForeignKey implements ConstraintInterface
{
    use PgSQLConstraintTrait;
    use ForeignKeyTrait;
}
