<?php

namespace Abbadon1334\ATKFastRoute\Test;

use Abbadon1334\ATKFastRoute\Handler\RoutedMethod;
use Abbadon1334\ATKFastRoute\Handler\RoutedUI;
use Abbadon1334\ATKFastRoute\Router;
use atk4\core\Exception;
use atk4\ui\App;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function tearDown(): void
    {
        @unlink(__DIR__ . '/../demos/routes.cache');
    }

    public function testSetBasePath()
    {
        $router = new Router(new App(['always_run' => false]));
        $router->setBaseDir('basedir');

        $this->addToAssertionCount(1);
    }

    public function inc(string $file, $METHOD, $URI)
    {
        $_SERVER['REQUEST_METHOD'] = $METHOD;
        $_SERVER['REQUEST_URI'] = $URI;

        include __DIR__ . '/../demos/' . $file;
    }

    /**
     * @dataProvider dataProviderTestDemos
     * @runInSeparateProcess
     */
    public function testDemos($file, $METHOD, $URI, $status, $excepted)
    {
        try {
            ob_start();
            $this->inc($file, $METHOD, $URI);
            $content = ob_get_clean();
        } catch (\Throwable $e) {
            ob_end_flush();

            $e = new Exception($e->getMessage(), $e->getCode(), $e);

            $e->addMoreInfo('path', $file);
            $e->addMoreInfo('method', $METHOD);
            $e->addMoreInfo('uri', $URI);

            throw $e;
        }

        $this->assertEquals($status, http_response_code());

        if ($excepted !== false) {
            $this->assertEquals($excepted, $content);
        }
    }

    public function dataProviderTestDemos()
    {
        $files = [
            'index.php',
            'cached.php',
            'using-config.php',
        ];

        $cases = [
            ['GET', '/callable', 200, false],
            ['GET', '/test', 200, false],
            ['GET', '/testStatic', 200, false],
            ['GET', '/test2', 200, false],
            ['POST', '/test?atk_centered_loader_callback=ajax&__atk_callback=1', 200, false],
            ['PUT', '/test', 405, false], // FAIL - method not allowed
            ['GET', '/abc', 404, false], // FAIL - not found
            ['GET', '/test-parameters/6/test', 200, json_encode(['6', 'test'])], // test params
            ['GET', '/test-parameters-static/6/test', 200, json_encode(['6', 'test'])], // test params
        ];

        $result = [];
        foreach ($files as $f) {
            foreach ($cases as $c) {
                $result[] = array_merge([$f], $c);
            }
        }

        $result[] = ['index.php', 'GET', '/test3', 200, false];

        /** Static file serve TESTS */

        $css_content = ".test_style { display:none; }";

        $result[] = ['static.php', 'GET', '/assets/test.css', 200, $css_content];
        $result[] = ['static.php', 'GET', '/assets/test.css?test=get_var', 200, $css_content]; // test correct parsing
        $result[] = ['static.php', 'GET', '/assets/test.js', 403, false];
        $result[] = ['static.php', 'GET', '/assets/not_exists.css', 403, false];
        $result[] = ['static.php', 'GET', '/assets/../test.js', 403, false]; // folder not allowed

        return $result;
    }

    public function testToArray()
    {
        $route = new RoutedMethod(static::class,'testToArray');

        $this->assertEquals([
            static::class,
            'testToArray'
        ],$route->toArray());

        $route = new RoutedUI(static::class,['test' => 'value']);
        $this->assertEquals([
            static::class,
            ['test' => 'value']
        ],$route->toArray());
    }
}
