<?php

declare(strict_types = 1);

namespace MegaTests\Unit\Lib\Http;

use Lib\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGetParamShouldStripSqlInjectionFromGetParams()
    {
        $malicousGetParams = [
            'name' => "' OR '1'='1"
        ];

        $request = new Request([], [], $malicousGetParams, []);
        $result = $request->getParam('name');
        $this->assertEquals('&#039; OR &#039;1&#039;=&#039;1', $result);
    }

    public function testGetParamShouldStripSqlInjectionFromPostParams()
    {
        $malicousPostParams = [
            'name' => "' OR '1'='1"
        ];

        $request = new Request([], [], [], $malicousPostParams);
        $result = $request->getParam('name');
        $this->assertEquals('&#039; OR &#039;1&#039;=&#039;1', $result);
    }
}
