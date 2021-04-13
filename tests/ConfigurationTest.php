<?php declare(strict_types=1);

namespace Quextum\Emails\Tests;

use Nette\DI\Container;
use Quextum\Emails\MailSender;
use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

Environment::setup();
$container = Bootstrap::boot()->createContainer();

/**
 * @testCase
 */
class ConfigurationTest extends TestCase
{
    private Container $di;

    /**
     * ConfigurationTest constructor.
     * @param Container $di
     */
    public function __construct(Container $di)
    {
        $this->di = $di;
    }

    protected function getTestData(): array
    {
        return [
            ['b', [
                'variables' => [
                    'localVarB' => 'localValB',
                    'localVarA' => 'localValA',
                ],
                'subject' => 'B',
                'from' => 'local X <email@domain.com>',
                'to' => 'local B extends local A <email@domain.com>',
            ], null],
            ['d', [
                'subject' => 'common X',
                'from' => 'local Y <email@domain.com>',
                'to' => 'local D extends local B extends local X <email@domain.com>',
            ], null],
            ['unexisting', [
                'to' => '',
                'subject' => 'unexisting',
            ], [
                'to' => '',
                'subject' => 'unexisting',
            ]]
        ];
    }

    /**
     * @dataProvider getTestData
     * @param string $key
     * @param array $expected
     * @param array|null $config
     */
    public function testOne(string $key, array $expected, ?array $config): void
    {
        /** @var MailSender $sender */
        $sender = $this->di->getByType(MailSender::class);
        Assert::with($sender, function () use ($key, $expected, $config) {
            /** @var MailSender $this */
            Assert::equal($expected, $this->getConfiguration($key, $config));
        });
    }
}

(new ConfigurationTest($container))->run();