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
    public function create($className)
    {
        $metaClass = new \ReflectionClass($className);

        if (!$metaClass->hasMethod('__construct')) {
            return new $className();
        }

        $arguments = $this->resolveDependencies([$className, '__construct']);

        return $metaClass->newInstanceArgs($arguments);
    }

    public function resolveDependencies($method)
    {
        $method = new \ReflectionMethod($method[0], $method[1]);

        $dependencies = [];

        foreach ($method->getParameters() as $parameter) {
            if (!isset($parameter->getClass()->name)) {
                break;
            }

            $dependencies[] = $this->create($parameter->getClass()->name);
        }

        return $dependencies;
    }
}
