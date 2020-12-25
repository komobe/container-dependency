<?php

namespace Komobe\Container\Exception;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class KeyAlreadyExistsException extends Exception implements ContainerExceptionInterface
{
}