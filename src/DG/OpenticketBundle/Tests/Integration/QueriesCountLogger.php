<?php

namespace DG\OpenticketBundle\Tests\Integration;


use Doctrine\DBAL\Logging\SQLLogger;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class QueriesCountLogger implements SQLLogger
{
    public $queriesCount = 0;

    /**
     * Logs a SQL statement somewhere.
     *
     * @param string $sql The SQL to be executed.
     * @param array|null $params The SQL parameters.
     * @param array|null $types The SQL parameter types.
     *
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->queriesCount++;
    }

    /**
     * Marks the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
    }
}