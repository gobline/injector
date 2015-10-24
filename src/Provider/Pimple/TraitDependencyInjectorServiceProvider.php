<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Injector\Provider\Pimple;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Mendo\Injector\TraitDependencyInjector;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class TraitDependencyInjectorServiceProvider implements ServiceProviderInterface
{
    private $reference;

    public function __construct($reference = 'injector')
    {
        $this->reference = $reference;
    }

    public function register(Container $container)
    {
        $container[$this->reference] = function ($c) {
            return new TraitDependencyInjector();
        };
    }
}
