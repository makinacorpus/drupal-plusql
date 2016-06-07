# Drupal PluSQL - because SQL can do plus

Nice module that enables database handling enhancements for Drupal 7. Since
Drupal core might not behave correctly when you correctly use the database,
all behaviors will remain discrete and enabled on a per-table definition in
the hook_schema().

## Current features

 *  Foreign key ON DELETE CASCADE and ON DELETE SET NULL declarations are
    propagated to the database whenever possible.

## Targeted features

 *  SQL column type automatic conversion to PHP types, using converters.
 *  SQL column type conversion based upon definition in hook_schema().

## Supported databases

 *  MySQL
 *  PostgreSQL
