<?php

declare(strict_types = 1);

namespace MegaTests\Unit\Lib;

use Lib\Uid;
use PHPUnit\Framework\TestCase;

class UidTest extends TestCase
{
    public function testGenerateShouldCreateAValidUid()
    {
        $result = Uid::generate();
        $this->assertEquals(36, strlen($result));

        // uid has always the same format. Example: 19dea045-0619-06fe-23cd-f0658e3e9261
        $this->assertMatchesRegularExpression('/[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}/', $result);
    }
}
