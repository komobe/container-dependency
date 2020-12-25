<?php

namespace Komobe\Container;

use Komobe\Container\Exception\ContainerException;
use Komobe\Container\Exception\KeyAlreadyExistsException;
use Komobe\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    private static ?self $INSTANCE = null;

    private array $registries = [];

    private array $instances = [];

    /**
     * @return static
     */
    public static function getNewInstance(): self
    {
        return new static();
    }

    public static function getInstance(): self
    {
        if (is_null(static::$INSTANCE)) {
            static::$INSTANCE = static::getNewInstance();
        }

        return static::$INSTANCE;
    }

    /**
     * @param string $id
     *
     * @return ContainerException|NotFoundException|mixed|null
     * @throws NotFoundException
     */
    public function get($id)
    {
        if (!isset($this->instances[$id])) {
            if (isset($this->registries[$id])) {
                $this->instances[$id] = $this->registries[$id]();

                return $this->instances[$id];
            }
        } else {
            return $this->instances[$id];
        }

        throw new NotFoundException();
    }

    /**
     * @param $id
     *
     * @return ContainerException|mixed|object|null
     * @throws ContainerException
     */
    public function getOrCreate($id)
    {
        try {
            $getResult = $this->get($id);
            $this->instances[$id] = $getResult;
        } catch (NotFoundException $e) {
            $resolverResult = $this->resolver($id);
            $this->instances[$id] = $resolverResult;
        }

        return $this->instances[$id];
    }

    /**
     * @param mixed $id
     *
     * @return ContainerException|object|null
     * @throws ContainerException
     */
    private function resolver($id)
    {
        try {
            $reflectionClass = new ReflectionClass($id);
            $isInstantiable = $reflectionClass->isInstantiable();
            if ($isInstantiable) {
                $constructor = $reflectionClass->getConstructor();
                if ($constructor) {
                    $constructorParameters = $constructor->getParameters();
                    $argConstructor = [];
                    foreach ($constructorParameters as $key => $constructorParameter) {
                        if ($constructorParameter->hasType()) {
                            $paramType = $constructorParameter->getType();
                            if ($paramType->isBuiltin()) {
                                $paramTypeName = $paramType->getName();
                                if ($constructorParameter->isOptional()) {
                                    array_push($argConstructor, $constructorParameter->getDefaultValue());
                                } else {
                                    settype($paramType, $paramTypeName);
                                    array_push($argConstructor, $paramType);
                                }
                            } else {
                                $paramTypeName = $paramType->getName();
                                $paramType = $this->resolver($paramTypeName);
                                array_push($argConstructor, $paramType);
                            }
                        } else {
                            array_push($argConstructor, $constructorParameter->getDefaultValue());
                        }
                    }

                    return $reflectionClass->newInstanceArgs($argConstructor);
                } else {
                    return $reflectionClass->newInstance();
                }
            }
        } catch (ReflectionException $e) {
            throw new ContainerException("La classe {$id} n'est pas instanciable");
        }

        return null;
    }

    public function has($id): bool
    {
        try {
            $this->get($id);

            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }

    /**
     * @return array
     */
    public function getRegistries(): array
    {
        return $this->registries;
    }

    /**
     * @param string   $id
     * @param callable $resolver
     *
     * @return void
     * @throws KeyAlreadyExistsException
     */
    public function addValue(string $id, callable $resolver): void
    {
        if (isset($this->registries[$id])) {
            throw new KeyAlreadyExistsException();
        }

        $this->registries[$id] = $resolver;
    }
}