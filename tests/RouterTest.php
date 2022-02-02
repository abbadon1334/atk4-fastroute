<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Test;

use Abbadon1334\ATKFastRoute\Handler\RoutedMethod;
use Abbadon1334\ATKFastRoute\Handler\RoutedServeStatic;
use Abbadon1334\ATKFastRoute\Handler\RoutedUI;
use Abbadon1334\ATKFastRoute\Router;
use Atk4\Core\Exception;
use Atk4\Ui\App;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RouterTest extends TestCase
{
    protected function tearDown(): void
    {
        if (file_exists(__DIR__.'/../demos/routes.cache')) {
            unlink(__DIR__.'/../demos/routes.cache');
        }
    }

    public function testSetBasePath(): void
    {
        $router = new Router(new App(['always_run' => false]));
        $router->setBaseDir('basedir');

        $this->addToAssertionCount(1);
    }

    public function inc(string $file, $METHOD, $URI): void
    {
        $_SERVER['REQUEST_METHOD'] = $METHOD;
        $_SERVER['REQUEST_URI'] = $URI;

        include __DIR__.'/../demos/'.$file;
    }

    /**
     * @dataProvider dataProviderTestDemos
     * @runInSeparateProcess
     *
     * @param mixed $file
     * @param mixed $METHOD
     * @param mixed $URI
     * @param mixed $status
     * @param mixed $excepted
     */
    public function testDemos($file, $METHOD, $URI, $status, $excepted): void
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

        $this->assertSame($status, http_response_code(), $URI);

        if (false !== $excepted) {
            $this->assertSame($excepted, $content);
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
            ['GET', '/', 200, false],
            ['GET', '/index', 200, false],
            ['GET', '/callable', 200, false],
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
        $result[] = ['using-config.php', 'GET', '/test_before_after', 200, 'BEFOREcontentAFTER'];

        /** Static file serve TESTS */
        $css_content = '.test_style {
    display: none;
}';

        $result[] = ['static.php', 'GET', '/assets/test.css', 200, $css_content];
        $result[] = ['static.php', 'GET', '/assets/test.css?test=get_var', 200, $css_content]; // test correct parsing
        $result[] = ['static.php', 'GET', '/assets/test.js', 403, false];
        $result[] = ['static.php', 'GET', '/assets/not_exists.css', 403, false];
        $result[] = ['static.php', 'GET', '/assets/../test.js', 403, false]; // folder not allowed

        return $result;
    }

    public function testToArray(): void
    {
        $route = new RoutedMethod(static::class, 'testToArray');

        $this->assertSame([
            static::class,
            'testToArray',
        ], $route->toArray());

        $route = new RoutedUI(static::class, ['test' => 'value']);
        $this->assertSame([
            static::class,
            ['test' => 'value'],
        ], $route->toArray());

        $route = new RoutedServeStatic(static::class, ['test' => 'value']);
        $this->assertSame([
            static::class,
            ['test' => 'value'],
        ], $route->toArray());

        $route = RoutedServeStatic::fromArray([static::class, ['test' => 'value']]);
        $this->assertSame(
            (new RoutedServeStatic(static::class, ['test' => 'value']))->toArray(),
            $route->toArray()
        );
    }

    public function testExceptionConfig(): void
    {
        $this->expectException(Exception::class);
        include __DIR__.'/../demos/using-config-unit-test-exception.php';
    }
}
