<?php

namespace Abbadon1334\ATKFastRoute\Test;

use Abbadon1334\ATKFastRoute\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function tearDown(): void
    {
        @unlink(__DIR__.'/../demos/routes.cache');
    }

    public function inc(string $file, $METHOD, $URI)
    {
        $_SERVER['REQUEST_METHOD'] = $METHOD;
        $_SERVER['REQUEST_URI'] = $URI;

        include __DIR__.'/../demos/'.$file;
    }

    /**
     * @runInSeparateProcess
     * @dataProvider dataProviderTestDemos
     */
    public function testDemos($file, $METHOD, $URI, $status)
    {
        try {
            $this->inc($file, $METHOD, $URI);
        } catch (\atk4\core\Exception $e) {
            $e->addMoreInfo('path', $file);
            $e->addMoreInfo('method', $METHOD);
            $e->addMoreInfo('uri', $URI);

            throw $e;
        }

        $this->assertEquals(http_response_code(), $status);
    }

    public function dataProviderTestDemos()
    {
        $files = [
            'index.php',
            'cached.php',
            'using-config.php',
        ];

        $cases = [
            ['GET', '/callable', 200],
            ['GET', '/test', 200],
            ['GET', '/test2', 200],
            ['POST', '/test?atk_centered_loader_callback=ajax&__atk_callback=1', 200],
            ['PUT', '/test', 405], // FAIL - method not allowed
            ['GET', '/abc', 404], // FAIL - not found
        ];

        $result = [];
        foreach ($files as $f) {
            foreach ($cases as $c) {
                $result[] = array_merge([$f], $c);
            }
        }

        return $result;
    }
}
