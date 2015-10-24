<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Mendo\Injector\TraitDependencyInjector;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class TraitDependencyInjectorTest extends PHPUnit_Framework_TestCase
{
    private $injector;

    public function setUp()
    {
        $this->injector = new TraitDependencyInjector();
    }

    public function testInjectAllDependencies()
    {
        $this->injector->register("CatTrait", function ($o) { $o->setCat(new Cat()); });
        $this->injector->register("DogTrait", function ($o) { $o->setDog(new Dog()); });

        $petstore = new Petstore();

        $this->injector->inject($petstore);

        $petstore->getCat();
        $petstore->getDog();

        $this->assertTrue(true); // if we reached this line, all dependencies have been injected
    }

    public function testInjectDependenciesWithMissing()
    {
        $this->setExpectedException('\Exception', "I don't have any more 'dog' for you.");

        $this->injector->register("CatTrait", function ($o) { $o->setCat(new Cat()); });

        $petstore = new Petstore();

        $this->injector->inject($petstore);

        $petstore->getCat();
        $petstore->getDog();
    }

    public function testInjectAllDependenciesChildClass()
    {
        $this->injector->register("CatTrait", function ($o) { $o->setCat(new Cat()); });
        $this->injector->register("DogTrait", function ($o) { $o->setDog(new Dog()); });
        $this->injector->register("SnakeTrait", function ($o) { $o->setSnake(new Snake()); });
        $this->injector->register("TurtleTrait", function ($o) { $o->setTurtle(new Turtle()); });

        $petstore = new BiggerPetstore();

        $this->injector->inject($petstore);

        $petstore->getCat();
        $petstore->getDog();
        $petstore->getTurtle();
        $petstore->getSnake();

        $this->assertTrue(true);
    }

    public function testInjectDependenciesWithMissingParent()
    {
        $this->setExpectedException('\Exception', "I don't have any more 'dog' for you.");

        $this->injector->register("CatTrait", function ($o) { $o->setCat(new Cat()); });
        $this->injector->register("SnakeTrait", function ($o) { $o->setSnake(new Snake()); });
        $this->injector->register("TurtleTrait", function ($o) { $o->setTurtle(new Turtle()); });

        $petstore = new BiggerPetstore();

        $this->injector->inject($petstore);

        $petstore->getCat();
        $petstore->getDog();
        $petstore->getTurtle();
        $petstore->getSnake();

        $this->assertTrue(true);
    }

    public function testInjectDependenciesWithMissingChild()
    {
        $this->setExpectedException('\Exception', "I don't have any more 'snake' for you.");

        $this->injector->register("DogTrait", function ($o) { $o->setDog(new Dog()); });
        $this->injector->register("CatTrait", function ($o) { $o->setCat(new Cat()); });
        $this->injector->register("TurtleTrait", function ($o) { $o->setTurtle(new Turtle()); });

        $petstore = new BiggerPetstore();

        $this->injector->inject($petstore);

        $petstore->getCat();
        $petstore->getDog();
        $petstore->getTurtle();
        $petstore->getSnake();

        $this->assertTrue(true);
    }
}

trait DogTrait
{
    private $dog;
    public function setDog(Dog $dog)
    {
        $this->dog = $dog;
    }
    public function getDog()
    {
        if (!$this->dog) {
            throw new \Exception("I don't have any more 'dog' for you.");
        }
        return $this->dog;
    }
}
trait CatTrait
{
    private $cat;
    public function setCat(Cat $cat)
    {
        $this->cat = $cat;
    }
    public function getCat()
    {
        if (!$this->cat) {
            throw new \Exception("I don't have any more 'cat' for you.");
        }
        return $this->cat;
    }
}
trait SnakeTrait
{
    private $snake;
    public function setSnake(Snake $snake)
    {
        $this->snake = $snake;
    }
    public function getSnake()
    {
        if (!$this->snake) {
            throw new \Exception("I don't have any more 'snake' for you.");
        }
        return $this->snake;
    }
}
trait TurtleTrait
{
    private $turtle;
    public function setTurtle(Turtle $turtle)
    {
        $this->turtle = $turtle;
    }
    public function getTurtle()
    {
        if (!$this->turtle) {
            throw new \Exception("I don't have any more 'turtle' for you.");
        }
        return $this->turtle;
    }
}

class Dog
{
    public $name = "doggy";
}
class Cat
{
    public $name = "kitten";
}
class Turtle
{
    public $name = "raphael";
}
class Snake
{
    public $name = "trouser";
}

class Petstore
{
    use DogTrait, CatTrait;
}
class BiggerPetstore extends Petstore
{
    use TurtleTrait, SnakeTrait;
}
