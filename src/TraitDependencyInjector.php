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
class TraitDependencyInjector
{
    private $registry = [];

    public function register($trait, $setup)
    {
        $this->registry[$trait] = $setup;

        return $this;
    }

    public function inject($object)
    {
        $traits = $this->getTraits(get_class($object));

        foreach ($traits as $trait) {
            if (isset($this->registry[$trait])) {
                $this->registry[$trait]($object);
            }
        }
    }

    private function getTraits($className)
    {
        $traitsNames = [];

        $recursiveClasses = function ($class) use (&$recursiveClasses, &$traitsNames) {
            if ($class->getParentClass()) {
                $recursiveClasses($class->getParentClass());
            }
            $traitsNames = array_merge($traitsNames, $class->getTraitNames());
        };
        $recursiveClasses(new \ReflectionClass($className));

        return $traitsNames;
    }
}
