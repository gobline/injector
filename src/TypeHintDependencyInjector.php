<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Injector;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class TypeHintDependencyInjector
{
    private $callbackOnCreateDependency;

    public function __construct(callable $callbackOnCreateDependency = null)
    {
        $this->callbackOnCreateDependency = $callbackOnCreateDependency ?: [$this, 'create'];
    }

    public function create($className)
    {
        $metaClass = new \ReflectionClass($className);

        if (!$metaClass->hasMethod('__construct')) {
            return new $className();
        }

        $arguments = $this->resolveDependencies([$className, '__construct']);

        return $metaClass->newInstanceArgs($arguments);
    }

    public function resolveDependencies($method, $positions = [])
    {
        $method = new \ReflectionMethod($method[0], $method[1]);

        $dependencies = [];

        $pos = 0;
        foreach ($method->getParameters() as $metaParameter) {
            $parameterName = $metaParameter->getName();

            if (!isset($metaParameter->getClass()->name)) {
                if ($positions) {
                    continue;
                }
                break;
            }
            if (!$positions || in_array($pos, $positions)) {
                $dependencies[$pos++] = call_user_func($this->callbackOnCreateDependency, $metaParameter->getClass()->name);
            }
        }

        return $dependencies;
    }
}
