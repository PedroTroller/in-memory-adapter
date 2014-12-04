<?php

namespace Gaufrette\Test;

use Gaufrette\Adapter\InMemory;
use Gaufrette\TestSuite\Adapter\AdapterFactory;

class InMemoryFactory implements AdapterFactory
{
    public function create()
    {
        return new InMemory;
    }

    public function destroy()
    {

    }
}
