<?php

namespace Abbadon1334\ATKFastRoute\Test;

use atk4\core\Exception;
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

        $this->assertEquals($status,http_response_code());

        if($excepted !== null)
        {
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
            ['GET', '/callable', 200, null],
            ['GET', '/test', 200, null],
            ['GET', '/testStatic', 200, null],
            ['GET', '/test2', 200, null],
            ['POST', '/test?atk_centered_loader_callback=ajax&__atk_callback=1', 200, null],
            ['PUT', '/test', 405, null], // FAIL - method not allowed
            ['GET', '/abc', 404, null], // FAIL - not found
            ['GET', '/test-parameters/6/test',200,json_encode(["6", 'test'])], // test params
            ['GET', '/test-parameters-static/6/test',200,json_encode(["6", 'test'])], // test params
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
