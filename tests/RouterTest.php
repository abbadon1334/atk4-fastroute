<?php

namespace Abbadon1334\ATKFastRoute\Test;

use Abbadon1334\ATKFastRoute\Router;
use atk4\ui\App;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function setUp() : void
    {
        ini_set('detect_unicode','Off');
    }

    public function inc(string $path, $METHOD, $URI)
    {
        $_SERVER['REQUEST_METHOD'] = $METHOD;
        $_SERVER['REQUEST_URI']    = $URI;

        include __DIR__ .'/../demos/' . $path;

        /** @var Router $router */
        return $router;
    }

    /**
     * @runInSeparateProcess
     * @dataProvider dataProviderTestDemos
     */
    public function testDemos($path, $METHOD, $URI)
    {
        try {
            $this->inc($path,$METHOD,$URI)->run();
        } catch (\atk4\core\Exception $e) {
            $e->addMoreInfo('path', $path);
            $e->addMoreInfo('method', $METHOD);
            $e->addMoreInfo('uri', $URI);

            throw $e;
        }

        $this->addToAssertionCount(1);
    }

    public function dataProviderTestDemos()
    {
        return [
            ['index.php', 'GET', '/callable'],
            ['index.php', 'GET', '/test'],
            ['index.php', 'GET', '/test2'],
            ['index.php', 'POST', '/test?atk_centered_loader_callback=ajax&__atk_callback=1'],
            ['index.php', 'PUT', '/test'], // FAIL - method not allowed
            ['index.php', 'GET', '/abc'], // FAIL - not found
        ];
    }
}
