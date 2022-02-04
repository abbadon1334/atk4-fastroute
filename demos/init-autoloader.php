<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Demos;

use Composer\Autoload\ClassLoader;

$isRootProject = file_exists(__DIR__.'/../vendor/autoload.php');
/** @var ClassLoader $loader */
$loader = require dirname(__DIR__, $isRootProject ? 1 : 4).'/vendor/autoload.php';
if (!$isRootProject && !class_exists(\Abbadon1334\ATKFastRoute\Tests\RouterTest::class)) {
    throw new \Error('Demos can be run only if Abbadon1334/ATKFastRoute is a root composer project or if dev files are autoloaded');
}
unset($isRootProject, $loader);
