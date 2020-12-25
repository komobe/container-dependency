<?php

namespace Komobe\Container\Tests\FakeClass;

class FakeClassWithOneArgInConstructor
{
    private FakeObjectOne $fakeObjectOne;

    public function __construct(FakeObjectOne $arg)
    {
        $this->fakeObjectOne = $arg;
    }
}