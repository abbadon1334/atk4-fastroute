<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Demos;

use Atk4\Ui\App;

date_default_timezone_set('UTC');

require_once __DIR__ . '/init-autoloader.php';

require_once __DIR__ . '/bootstrap.php';

// collect coverage for HTTP tests 1/2
if (file_exists(__DIR__ . '/CoverageUtil.php') && !class_exists(\PHPUnit\Framework\TestCase::class, false)) {
    require_once __DIR__ . '/CoverageUtil.php';
    \CoverageUtil::start();
}

$app = new App([
    'catch_exceptions' => false,
    'always_run' => false,
    'catch_runaway_callbacks' => false,
    'call_exit' => false,
]);

// collect coverage for HTTP tests 2/2
if (file_exists(__DIR__ . '/CoverageUtil.php') && !class_exists(\PHPUnit\Framework\TestCase::class, false)) {
    $app->onHook(\Atk4\Ui\App::HOOK_BEFORE_EXIT, function () {
        \CoverageUtil::saveData();
    });
}

$app->invokeInit();
