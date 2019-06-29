<?php

namespace Abbadon1334\ATKFastRoute\Test;

use atk4\ui\App;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestDemos
     */
    public function testDemos($METHOD, $URI)
    {
        $_SERVER['REQUEST_METHOD'] = $METHOD;
        $_SERVER['REQUEST_URI']    = $URI;

        require __DIR__ . '/../demos/index.php';

        /** @var App $app */
        $app->run();

        $this->addToAssertionCount(1);
    }

    public function dataProviderTestDemos()
    {
        return [
            ['GET', '/test'],
            ['GET', '/test2'],
            ['POST', '/test?atk_centered_loader_callback=ajax&__atk_callback=1'],
            ['GET', '/callable'],
            ['PUT', '/test'], // FAIL - method not allowed
            ['GET', '/abc'], // FAIL - not found
        ];
    }
}
