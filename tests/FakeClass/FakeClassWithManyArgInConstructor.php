<?php

namespace Komobe\Container\Tests\FakeClass;

class FakeClassWithManyArgInConstructor
{
    private FakeClassWithOneArgInConstructor $classWithArgInConstructor;

    private FakeObjectTwo $fakeObjectTwo;

    private ?array $arg3;

    private int $arg4;

    /**
     * FakeClassWithManyArgInConstructor constructor.
     *
     * @param FakeClassWithOneArgInConstructor $classWithArgInConstructor
     * @param FakeObjectTwo                    $fakeObjectTwo
     * @param array|null                       $arg3
     * @param int                              $arg4
     */
    public function __construct(
        FakeClassWithOneArgInConstructor $classWithArgInConstructor,
        FakeObjectTwo $fakeObjectTwo,
        ?array $arg3,
        int $arg4 = 2
    ) {
        $this->classWithArgInConstructor = $classWithArgInConstructor;
        $this->fakeObjectTwo = $fakeObjectTwo;
        $this->arg3 = $arg3;
        $this->arg4 = $arg4;
    }

    /**
     * @return FakeClassWithOneArgInConstructor
     */
    public function getClassWithArgInConstructor(): FakeClassWithOneArgInConstructor
    {
        return $this->classWithArgInConstructor;
    }

    /**
     * @param FakeClassWithOneArgInConstructor $classWithArgInConstructor
     *
     * @return FakeClassWithManyArgInConstructor
     */
    public function setClassWithArgInConstructor(FakeClassWithOneArgInConstructor $classWithArgInConstructor
    ): FakeClassWithManyArgInConstructor {
        $this->classWithArgInConstructor = $classWithArgInConstructor;

        return $this;
    }

    /**
     * @return FakeObjectTwo
     */
    public function getFakeObjectTwo(): FakeObjectTwo
    {
        return $this->fakeObjectTwo;
    }

    /**
     * @param FakeObjectTwo $fakeObjectTwo
     *
     * @return FakeClassWithManyArgInConstructor
     */
    public function setFakeObjectTwo(FakeObjectTwo $fakeObjectTwo): FakeClassWithManyArgInConstructor
    {
        $this->fakeObjectTwo = $fakeObjectTwo;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getArg3(): ?array
    {
        return $this->arg3;
    }

    /**
     * @param array|null $arg3
     *
     * @return FakeClassWithManyArgInConstructor
     */
    public function setArg3(?array $arg3): FakeClassWithManyArgInConstructor
    {
        $this->arg3 = $arg3;

        return $this;
    }

    /**
     * @return int
     */
    public function getArg4(): int
    {
        return $this->arg4;
    }

    /**
     * @param int $arg4
     *
     * @return FakeClassWithManyArgInConstructor
     */
    public function setArg4(int $arg4): FakeClassWithManyArgInConstructor
    {
        $this->arg4 = $arg4;

        return $this;
    }
}