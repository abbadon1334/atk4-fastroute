<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Tests;

use Atk4\Core\Phpunit\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use Symfony\Component\Process\Process;

abstract class BaseTestCase extends TestCase
{
    protected const ROOT_DIR = __DIR__.'/..';

    protected const DEMOS_DIR = self::ROOT_DIR.'/demos';

    private static ?Process $_process = null;

    protected bool $app_call_exit = true;

    protected string $host = '127.0.0.1';

    protected int $port = 9687;

    public static string $jar_file = self::DEMOS_DIR.'/_demo-data/cookie.jar';

    public static FileCookieJar $jar;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (file_exists(self::$jar_file)) {
            unlink(self::$jar_file);
        }
    }

    public static function tearDownAfterClass(): void
    {
        // stop the test server
        usleep(250 * 1000);
        self::$_process->stop(1, 9); // TODO we may need to add pcntl_async_signals/pcntl_signal to CoverageUtil.php
        self::$_process = null;

        parent::tearDownAfterClass();
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (self::$_process === null) {
            if (\PHP_SAPI !== 'cli') {
                throw new \Error('Builtin webserver can be started only from CLI');
            }

            $this->setupWebserver();

            self::$jar = new FileCookieJar(self::$jar_file);
        }
    }

    private function setupWebserver(): void
    {
        // spin up the test server
        $cmdArgs = [
            '-S',
            $this->host.':'.$this->port,
            '-t',
            static::DEMOS_DIR,
            __DIR__.'/router.php',
        ];
        if (!empty(ini_get('open_basedir'))) {
            $cmdArgs[] = '-d';
            $cmdArgs[] = 'open_basedir='.ini_get('open_basedir');
        }

        self::$_process = new Process(['php', ...$cmdArgs]);
        self::$_process->disableOutput();
        self::$_process->start();
        usleep(250 * 1000);
    }

    protected function getClient(): Client
    {
        return new Client([
            'base_uri'    => 'http://localhost:'.$this->port,
            'cookies'     => self::$jar,
            'http_errors' => false,
        ]);
    }
}
