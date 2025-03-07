<?php

declare(strict_types=1);

namespace TalesBot;

/**
 * Provides database support.
 */
class Database
{
    /**
     * Creates a database connection.
     *
     *  - databaseDsn: A data source name pointing to the TalesBot database
     *
     * @param array<string, mixed> $options An array of options for this instance
     */
    public function __construct(array $options = [])
    {
        // set the dbh into this for regular access.
        $dbh = new \PDO($options['databaseDsn']);
        $query = 'CREATE TABLE IF NOT EXISTS some_table (id INTEGER PRIMARY KEY AUTOINCREMENT);';
        $dbh->exec($query);
    }
}
