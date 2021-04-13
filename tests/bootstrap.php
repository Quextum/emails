<?php declare(strict_types=1);

namespace Quextum\Emails\Tests;

use Nette\Bootstrap\Configurator;

define('TESTS_DIR', __DIR__);
define('ROOT_DIR', dirname(__DIR__));
define('TEMP_DIR', TESTS_DIR . '/temp');

class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator;
        $configurator->setDebugMode(true);
        $configurator->setTimeZone('Europe/Prague');
        $configurator->setTempDirectory(TEMP_DIR);
        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $configurator->addConfig(__DIR__ . '/config/common.neon');
        $configurator->addConfig(__DIR__ . '/config/local.neon');
        $configurator->addParameters([
            'appDir' => TESTS_DIR
        ]);
        return $configurator;
    }
}