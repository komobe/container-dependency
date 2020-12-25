<?php

namespace Komobe\Container\Tests;

use Exception;

use Komobe\Container\Container;
use Komobe\Container\Exception\ContainerException;
use Komobe\Container\Exception\KeyAlreadyExistsException;
use Komobe\Container\Exception\NotFoundException;
use Komobe\Container\Tests\FakeClass\FakeClassWithManyArgInConstructor;
use Komobe\Container\Tests\FakeClass\FakeClassWithOneArgInConstructor;
use Komobe\Container\Tests\FakeClass\FakeObjectOne;
use Komobe\Container\Tests\FakeClass\FakeObjectTwo;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testGetInstance()
    {
        $container = Container::getNewInstance();
        $this->assertInstanceOf(Container::class, $container);
    }

    public function testContainerInstanceIsSingleton()
    {
        $container1 = Container::getInstance();
        $container2 = Container::getInstance();
        $otherContainer1 = Container::getNewInstance();
        $otherContainer2 = Container::getNewInstance();

        $this->assertSame($container2, $container1);
        $this->assertNotSame($otherContainer1, $container1);
        $this->assertNotSame($otherContainer2, $container2);
    }

    /**
     * @throws NotFoundException
     */
    public function testNoEntryWasFoundForIdentifier()
    {
        $this->expectException(NotFoundException::class);
        $container = Container::getNewInstance();
        $container->get(FakeObjectOne::class);
    }

    /**
     * @throws Exception
     */
    public function testEntryWasFoundForIdentifier()
    {
        $container = Container::getNewInstance();
        $fakeObjectOne = new FakeObjectOne();
        $fakeObjectTwo = new FakeObjectTwo();
        $container->addValue(FakeObjectOne::class, fn() => $fakeObjectOne);
        $object = $container->get(FakeObjectOne::class);
        $this->assertSame($object, $fakeObjectOne);
        $this->assertNotSame($object, $fakeObjectTwo);
    }

    /**
     * @throws Exception
     */
    public function testContainerHasReturnTrue()
    {
        $container = Container::getNewInstance();
        $container->addValue(FakeObjectOne::class, fn() => new FakeObjectOne());
        $hasInContainer = $container->has(FakeObjectOne::class);
        $this->assertTrue($hasInContainer);
    }

    /**
     * @throws Exception
     */
    public function testKeyEntryAlreadyExists()
    {
        $this->expectException(KeyAlreadyExistsException::class);
        $container = Container::getNewInstance();
        $container->addValue(FakeObjectOne::class, fn() => new FakeObjectOne());
       $container->addValue(FakeObjectOne::class, fn() => new FakeObjectTwo());
    }

    /**
     * @throws ContainerException
     */
    public function testGetOrCreateKeyEntry()
    {
        $container = Container::getNewInstance();
        $fakeObjectOne = new FakeObjectOne();
        $container->addValue(FakeObjectOne::class, fn() => $fakeObjectOne);
        $objectOne = $container->getOrCreate(FakeObjectOne::class);
        $objectTwo = $container->getOrCreate(FakeObjectTwo::class);
        $classWithArgInConstructor = $container->getOrCreate(FakeClassWithOneArgInConstructor::class);
        $classWithManyArgInConstructor = $container->getOrCreate(FakeClassWithManyArgInConstructor::class);

        $this->assertSame($objectOne, $fakeObjectOne);
        $this->assertNotInstanceOf(FakeObjectOne::class, $objectTwo);
        $this->assertInstanceOf(FakeObjectTwo::class, $objectTwo);
        $this->assertInstanceOf(FakeClassWithOneArgInConstructor::class, $classWithArgInConstructor);
        $this->assertInstanceOf(FakeClassWithManyArgInConstructor::class, $classWithManyArgInConstructor);
        $this->assertEquals([], $classWithManyArgInConstructor->getArg3());
        $this->assertEquals(2, $classWithManyArgInConstructor->getArg4());
    }
}