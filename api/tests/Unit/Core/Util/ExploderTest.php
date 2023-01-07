<?php
declare(strict_types=1);

namespace Test\Unit\Core\Util;

use App\Core\Util\Exploder;
use Test\Unit\Shared\AbstractUnitTest;

class ExploderTest extends AbstractUnitTest
{
    public function testItExplodesByCapitalLetter()
    {
        self::assertEquals(
            ['String', 'With', 'Some', 'Words'],
            Exploder::explodeByCapitalLetter('StringWithSomeWords')
        );
    }
}
