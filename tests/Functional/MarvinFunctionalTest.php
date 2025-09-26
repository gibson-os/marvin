<?php
declare(strict_types=1);

namespace Functional;

use GibsonOS\Test\Functional\Core\FunctionalTest;

class MarvinFunctionalTest extends FunctionalTest
{
    protected function getDir(): string
    {
        return __DIR__;
    }
}
