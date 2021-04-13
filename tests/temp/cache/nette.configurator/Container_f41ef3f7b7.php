<?php
// source: /home/prosky/codebase/quextum/emails/tests/config/common.neon
// source: /home/prosky/codebase/quextum/emails/tests/config/local.neon
// source: array

/** @noinspection PhpParamsInspection,PhpMethodMayBeStaticInspection */

declare(strict_types=1);

class Container_f41ef3f7b7 extends Nette\DI\Container
{
	protected $tags = ['nette.inject' => ['application.1' => true, 'application.2' => true, 'emails.sender' => true]];
	protected $types = ['container' => 'Nette\DI\Container'];

	protected $aliases = [
		'application' => 'application.application',
		'cacheStorage' => 'cache.storage',
		'httpRequest' => 'http.request',
		'httpResponse' => 'http.response',
		'nette.cacheJournal' => 'cache.journal',
		'nette.httpRequestFactory' => 'http.requestFactory',
		'nette.latteFactory' => 'latte.latteFactory',
		'nette.mailer' => 'mail.mailer',
		'nette.presenterFactory' => 'application.presenterFactory',
		'nette.templateFactory' => 'latte.templateFactory',
		'router' => 'routing.router',
		'session' => 'session.session',
	];

	protected $wiring = [
		'Nette\DI\Container' => [['container']],
		'Nette\Application\Application' => [['application.application']],
		'Nette\Application\IPresenterFactory' => [['application.presenterFactory']],
		'Nette\Application\LinkGenerator' => [['application.linkGenerator']],
		'Nette\Caching\Storages\Journal' => [['cache.journal']],
		'Nette\Caching\Storage' => [['cache.storage']],
		'Nette\Http\RequestFactory' => [['http.requestFactory']],
		'Nette\Http\IRequest' => [['http.request']],
		'Nette\Http\Request' => [['http.request']],
		'Nette\Http\IResponse' => [['http.response']],
		'Nette\Http\Response' => [['http.response']],
		'Nette\Bridges\ApplicationLatte\LatteFactory' => [['latte.latteFactory']],
		'Nette\Application\UI\TemplateFactory' => [['latte.templateFactory']],
		'Nette\Mail\Mailer' => [['mail.mailer']],
		'Nette\Http\Session' => [['session.session']],
		'Tracy\ILogger' => [['tracy.logger']],
		'Tracy\BlueScreen' => [['tracy.blueScreen']],
		'Tracy\Bar' => [['tracy.bar']],
		'Quextum\Emails\MailSender' => [['emails.sender']],
		'Nette\Application\IPresenter' => [2 => ['application.1', 'application.2']],
		'NetteModule\ErrorPresenter' => [2 => ['application.1']],
		'NetteModule\MicroPresenter' => [2 => ['application.2']],
		'Nette\Routing\Router' => [['routing.router']],
	];


	public function __construct(array $params = [])
	{
		parent::__construct($params);
		$this->parameters += [
			'appDir' => '/home/prosky/codebase/quextum/emails/tests',
			'wwwDir' => '/home/prosky/codebase/quextum/emails/tests',
			'vendorDir' => '/home/prosky/codebase/quextum/emails/vendor',
			'debugMode' => true,
			'productionMode' => false,
			'consoleMode' => true,
			'tempDir' => '/home/prosky/codebase/quextum/emails/tests/temp',
		];
	}


	public function createServiceApplication__1(): NetteModule\ErrorPresenter
	{
		return new NetteModule\ErrorPresenter($this->getService('tracy.logger'));
	}


	public function createServiceApplication__2(): NetteModule\MicroPresenter
	{
		return new NetteModule\MicroPresenter($this, $this->getService('http.request'), $this->getService('routing.router'));
	}


	public function createServiceApplication__application(): Nette\Application\Application
	{
		$service = new Nette\Application\Application(
			$this->getService('application.presenterFactory'),
			$this->getService('routing.router'),
			$this->getService('http.request'),
			$this->getService('http.response')
		);
		$service->catchExceptions = null;
		$service->errorPresenter = 'Nette:Error';
		Nette\Bridges\ApplicationDI\ApplicationExtension::initializeBlueScreenPanel(
			$this->getService('tracy.blueScreen'),
			$service
		);
		$this->getService('tracy.bar')->addPanel(new Nette\Bridges\ApplicationTracy\RoutingPanel(
			$this->getService('routing.router'),
			$this->getService('http.request'),
			$this->getService('application.presenterFactory')
		));
		$service->onResponse[]=[$this->getService('emails.sender'), 'showDebugMails'];
		return $service;
	}


	public function createServiceApplication__linkGenerator(): Nette\Application\LinkGenerator
	{
		return new Nette\Application\LinkGenerator(
			$this->getService('routing.router'),
			$this->getService('http.request')->getUrl()->withoutUserInfo(),
			$this->getService('application.presenterFactory')
		);
	}


	public function createServiceApplication__presenterFactory(): Nette\Application\IPresenterFactory
	{
		return new Nette\Application\PresenterFactory(new Nette\Bridges\ApplicationDI\PresenterFactoryCallback(
			$this,
			5,
			'/home/prosky/codebase/quextum/emails/tests/temp/cache/nette.application/touch'
		));
	}


	public function createServiceCache__journal(): Nette\Caching\Storages\Journal
	{
		return new Nette\Caching\Storages\SQLiteJournal('/home/prosky/codebase/quextum/emails/tests/temp/cache/journal.s3db');
	}


	public function createServiceCache__storage(): Nette\Caching\Storage
	{
		return new Nette\Caching\Storages\FileStorage(
			'/home/prosky/codebase/quextum/emails/tests/temp/cache',
			$this->getService('cache.journal')
		);
	}


	public function createServiceContainer(): Container_f41ef3f7b7
	{
		return $this;
	}


	public function createServiceEmails__sender(): Quextum\Emails\MailSender
	{
		$service = new Quextum\Emails\MailSender(
			false,
			\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Structure', [
				"\x00Nette\\Schema\\Elements\\Structure\x00items" => [
					'locale' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
						"\x00Nette\\Schema\\Elements\\Type\x00type" => 'null|string',
						"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
						"\x00Nette\\Schema\\Elements\\Type\x00pattern" => '[a-z]{2}(_[A-Z]{2})?',
						"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
						"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
						"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
					]),
					'to' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\AnyOf', [
						"\x00Nette\\Schema\\Elements\\AnyOf\x00set" => [
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'array',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
									"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
									"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
									"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
									"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
									"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
									"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
								]),
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
						],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00required" => false,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00default" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00before" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00deprecated" => null,
					]),
					'cc' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\AnyOf', [
						"\x00Nette\\Schema\\Elements\\AnyOf\x00set" => [
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'array',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
									"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
									"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
									"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
									"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
									"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
									"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
								]),
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
						],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00required" => false,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00default" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00before" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00deprecated" => null,
					]),
					'bcc' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\AnyOf', [
						"\x00Nette\\Schema\\Elements\\AnyOf\x00set" => [
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'array',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
									"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
									"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
									"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
									"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
									"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
									"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
								]),
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
						],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00required" => false,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00default" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00before" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00deprecated" => null,
					]),
					'reply' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\AnyOf', [
						"\x00Nette\\Schema\\Elements\\AnyOf\x00set" => [
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'array',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
									"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
									"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
									"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
									"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
									"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
									"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
									"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
								]),
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
						],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00required" => false,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00default" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00before" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00deprecated" => null,
					]),
					'from' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\AnyOf', [
						"\x00Nette\\Schema\\Elements\\AnyOf\x00set" => [
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'email',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [
									[['Quextum\Emails\EmailsExtension', 'isRecipient'], 'Name <email>'],
								],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
						],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00required" => false,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00default" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00before" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00deprecated" => null,
					]),
					'subject' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\AnyOf', [
						"\x00Nette\\Schema\\Elements\\AnyOf\x00set" => [
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'Nette\DI\Definitions\Statement',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
							\Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
								"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
								"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
								"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
								"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
								"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
								"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
								"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
							]),
						],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00required" => false,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00default" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00before" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\AnyOf\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\AnyOf\x00deprecated" => null,
					]),
					'return' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
						"\x00Nette\\Schema\\Elements\\Type\x00type" => 'email',
						"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
						"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
						"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
						"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
					]),
					'embed' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
						"\x00Nette\\Schema\\Elements\\Type\x00type" => 'array',
						"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
							"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
							"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
							"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
							"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
							"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [
								['is_file', 'Not file'],
								['is_readable', 'Not readable'],
							],
							"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
						]),
						"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
						"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
						"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
						"\x00Nette\\Schema\\Elements\\Type\x00default" => [],
						"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
					]),
					'attachment' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
						"\x00Nette\\Schema\\Elements\\Type\x00type" => 'array',
						"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
							"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
							"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
							"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
							"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
							"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [
								['is_file', 'Not file'],
								['is_readable', 'Not readable'],
							],
							"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
						]),
						"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
						"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
						"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
						"\x00Nette\\Schema\\Elements\\Type\x00default" => [],
						"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
					]),
					'variables' => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
						"\x00Nette\\Schema\\Elements\\Type\x00type" => 'array',
						"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
							"\x00Nette\\Schema\\Elements\\Type\x00type" => 'mixed',
							"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
							"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
							"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
							"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
							"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
						]),
						"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => \Nette\PhpGenerator\Dumper::createObject('Nette\Schema\Elements\Type', [
							"\x00Nette\\Schema\\Elements\\Type\x00type" => 'string',
							"\x00Nette\\Schema\\Elements\\Type\x00itemsValue" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00itemsKey" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
							"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
							"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
							"\x00Nette\\Schema\\Elements\\Type\x00default" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
							"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
							"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
						]),
						"\x00Nette\\Schema\\Elements\\Type\x00range" => [null, null],
						"\x00Nette\\Schema\\Elements\\Type\x00pattern" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00merge" => true,
						"\x00Nette\\Schema\\Elements\\Type\x00required" => false,
						"\x00Nette\\Schema\\Elements\\Type\x00default" => [],
						"\x00Nette\\Schema\\Elements\\Type\x00before" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00asserts" => [],
						"\x00Nette\\Schema\\Elements\\Type\x00castTo" => null,
						"\x00Nette\\Schema\\Elements\\Type\x00deprecated" => null,
					]),
				],
				"\x00Nette\\Schema\\Elements\\Structure\x00otherItems" => null,
				"\x00Nette\\Schema\\Elements\\Structure\x00range" => [null, null],
				"\x00Nette\\Schema\\Elements\\Structure\x00skipDefaults" => true,
				"\x00Nette\\Schema\\Elements\\Structure\x00required" => true,
				"\x00Nette\\Schema\\Elements\\Structure\x00default" => null,
				"\x00Nette\\Schema\\Elements\\Structure\x00before" => null,
				"\x00Nette\\Schema\\Elements\\Structure\x00asserts" => [],
				"\x00Nette\\Schema\\Elements\\Structure\x00castTo" => 'array',
				"\x00Nette\\Schema\\Elements\\Structure\x00deprecated" => null,
			]),
			[
				'x' => ['subject' => 'common X', 'from' => 'local X <email@domain.com>', 'to' => 'local X <email@domain.com>'],
				'a' => [
					'subject' => 'common X',
					'from' => 'local X <email@domain.com>',
					'to' => 'local A extends local X <email@domain.com>',
					'variables' => ['localVarA' => 'localValA'],
				],
				'b' => [
					'variables' => ['localVarB' => 'localValB', 'localVarA' => 'localValA'],
					'subject' => 'B',
					'from' => 'local X <email@domain.com>',
					'to' => 'local B extends local A <email@domain.com>',
				],
				'y' => ['from' => 'local Y <email@domain.com>'],
				'd' => [
					'subject' => 'common X',
					'from' => 'local Y <email@domain.com>',
					'to' => 'local D extends local B extends local X <email@domain.com>',
				],
			],
			'/home/prosky/codebase/quextum/emails/tests/templates'
		);
		$service->templateFactory = $this->getService('latte.templateFactory');
		$service->session = $this->getService('session.session');
		$service->sender = $this->getService('mail.mailer');
		$service->linkGenerator = $this->getService('application.linkGenerator');
		return $service;
	}


	public function createServiceHttp__request(): Nette\Http\Request
	{
		return $this->getService('http.requestFactory')->fromGlobals();
	}


	public function createServiceHttp__requestFactory(): Nette\Http\RequestFactory
	{
		$service = new Nette\Http\RequestFactory;
		$service->setProxy([]);
		return $service;
	}


	public function createServiceHttp__response(): Nette\Http\Response
	{
		$service = new Nette\Http\Response;
		$service->cookieSecure = $this->getService('http.request')->isSecured();
		return $service;
	}


	public function createServiceLatte__latteFactory(): Nette\Bridges\ApplicationLatte\LatteFactory
	{
		return new class ($this) implements Nette\Bridges\ApplicationLatte\LatteFactory {
			private $container;


			public function __construct(Container_f41ef3f7b7 $container)
			{
				$this->container = $container;
			}


			public function create(): Latte\Engine
			{
				$service = new Latte\Engine;
				$service->setTempDirectory('/home/prosky/codebase/quextum/emails/tests/temp/cache/latte');
				$service->setAutoRefresh();
				$service->setContentType('html');
				Nette\Utils\Html::$xhtml = false;
				return $service;
			}
		};
	}


	public function createServiceLatte__templateFactory(): Nette\Application\UI\TemplateFactory
	{
		$service = new Nette\Bridges\ApplicationLatte\TemplateFactory(
			$this->getService('latte.latteFactory'),
			$this->getService('http.request'),
			null,
			$this->getService('cache.storage')
		);
		Nette\Bridges\ApplicationDI\LatteExtension::initLattePanel($service, $this->getService('tracy.bar'));
		return $service;
	}


	public function createServiceMail__mailer(): Nette\Mail\Mailer
	{
		return new Nette\Mail\SendmailMailer;
	}


	public function createServiceRouting__router(): Nette\Routing\Router
	{
		return new Nette\Routing\SimpleRouter;
	}


	public function createServiceSession__session(): Nette\Http\Session
	{
		$service = new Nette\Http\Session($this->getService('http.request'), $this->getService('http.response'));
		$service->setOptions(['cookieSamesite' => 'Lax']);
		return $service;
	}


	public function createServiceTracy__bar(): Tracy\Bar
	{
		return Tracy\Debugger::getBar();
	}


	public function createServiceTracy__blueScreen(): Tracy\BlueScreen
	{
		return Tracy\Debugger::getBlueScreen();
	}


	public function createServiceTracy__logger(): Tracy\ILogger
	{
		return Tracy\Debugger::getLogger();
	}


	public function initialize()
	{
		// di.
		(function () {
			$this->getService('tracy.bar')->addPanel(new Nette\Bridges\DITracy\ContainerPanel($this));
		})();
		// tracy.
		(function () {
			if (!Tracy\Debugger::isEnabled()) { return; }
			Tracy\Debugger::getLogger()->mailer = [new Tracy\Bridges\Nette\MailSender($this->getService('mail.mailer')), 'send'];
		})();
	}
}
