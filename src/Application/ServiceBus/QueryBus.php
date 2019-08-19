<?php declare(strict_types=1);

namespace Feedback\Application\ServiceBus;

use ReflectionClass;
use ReflectionException;

class QueryBus
{
    /** @var array */
    private $queryHandlers = [];

    /**
     * @param $queryHandler
     * @throws ReflectionException
     */
    public function register($queryHandler)
    {
        $className = (new ReflectionClass($queryHandler))->getShortName();
        $this->queryHandlers[$className] = $queryHandler;
    }

    /**
     * @param mixed $query
     * @return mixed
     * @throws ReflectionException
     */
    public function handle($query)
    {
        $className = (new ReflectionClass($query))->getShortName();
        $className = sprintf('%sHandler', $className);
        $queryHandler = $this->queryHandlers[$className];

        return $queryHandler->handle($query);
    }
}
