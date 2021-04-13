<?php declare(strict_types=1);

namespace Quextum\Emails;

use Nette\Application\Application;
use Nette\DI;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\Definitions\Statement;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Schema\Expect;
use Nette\Schema\Helpers;
use Nette\Schema\Schema;
use Nette\Utils\Strings;
use Nette\Utils\Validators;
use Quextum\Emails\Translation\ContributeTranslationProvider;
use Quextum\Emails\Translation\NoTranslationProvider;
use Quextum\Emails\Translation\TranslationProvider;

/**
 * Description of EmailsExtension
 *
 * @author Jan Proskovec <proskovec@prosky.cz>
 */
class EmailsExtension extends DI\CompilerExtension
{
    public const IS_RECIPIENT = [self::class, 'isRecipient'];

    private ServiceDefinition $sender;
    private ?ServiceDefinition $translation = null;

    private Schema $schema;

    public function __construct()
    {
        $this->schema = $this->getSenderConfigSchema();
    }


    public static function isRecipient(string $value): bool
    {
        ['name' => $name, 'email' => $email] = ((array)Strings::match($value, '/((?<name>.+) )?\<(?<email>.+@.+)\>/')) + ['name' => '', 'email' => ''];
        $name = strlen($name) === 0 || strlen(trim($name)) > 0;
        return $name && Validators::isEmail($email);
    }

    public static function getDefaultTranslationProvider(): string
    {
        if (class_exists(\Contributte\Translation\Translator::class)) {
            return ContributeTranslationProvider::class;
        }
        return NoTranslationProvider::class;
    }

    public function getSenderConfigSchema(): Schema
    {
        $email = Expect::email();
        $recipient = Expect::anyOf(
            $email, Expect::string()->assert(static::IS_RECIPIENT, 'Name <email>')
        );
        $recipients = Expect::anyOf(
            Expect::string(),
            Expect::arrayOf(Expect::string())
        );
        $attachment = Expect::string()
            ->assert('is_file', 'Not file')
            ->assert('is_readable', 'Not readable');
        return Expect::structure([
            'locale' => Expect::string()->pattern('[a-z]{2}(_[A-Z]{2})?')->nullable(),
            'to' => $recipients,
            'cc' => $recipients,
            'bcc' => $recipients,
            'reply' => $recipients,
            'from' => $recipient,
            'subject' => Expect::anyOf(Expect::type(Statement::class), Expect::string()),
            'return' => $email,
            'embed' => Expect::arrayOf($attachment),
            'attachment' => Expect::arrayOf($attachment),
            'variables' => Expect::arrayOf(Expect::mixed(), Expect::string())
        ])->skipDefaults()
            ->castTo('array');
    }

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'templates' => Expect::string()
                ->assert('is_dir')
                ->assert('is_readable'),
            'translation' => Expect::anyOf([
                Expect::type(DI\Definitions\Statement::class),
                Expect::string()->assert(fn($class) => (new \ReflectionClass($class))->implementsInterface(TranslationProvider::class))
            ])->default(self::getDefaultTranslationProvider()),
            'catchExceptions' => Expect::bool(false)
        ])->otherItems($this->schema)
            ->castTo('array');
    }

    public function mergeConfiguration(): void
    {
        $newConfig = [];
        foreach ($this->config as $key => $value) {
            [$newKey, $parent] = array_map('trim', explode('<', $key) + ['', '']);
            $prevConfig = $this->config[$parent] ?? null;
            if (isset($newConfig[$newKey])) {
                $prevConfig = $this->schema->merge($prevConfig, $newConfig[$newKey]);
            }
            $newConfig[$newKey] = $this->schema->merge($value, $prevConfig);
        }
        $this->config = $newConfig;
    }

    /**
     *
     * @throws InvalidArgumentException
     * @throws InvalidStateException
     */
    public function loadConfiguration(): void
    {
        $this->mergeConfiguration();
        $config = $this->getConfig();
        $builder = $this->getContainerBuilder();
        if ($config['translation']) {
            $this->translation = $builder->addDefinition($this->prefix('translation'))
                ->setFactory($config['translation'], is_string($config['translation']) ? ['namespace' => $this->name] : [])
                ->setType(TranslationProvider::class);
        }
        $defaults = array_diff_key($config, array_flip(['templates', 'translation', 'catchExceptions']));
        $this->sender = $builder->addDefinition($this->prefix('sender'))
            ->addTag(DI\Extensions\InjectExtension::TAG_INJECT)
            ->setFactory(MailSender::class, [
                'catchExceptions' => $config['catchExceptions'],
                'schema' => $this->schema,
                'defaults' => $defaults,
                'templatesDirectory' => $config['templates']]);

    }

    public function beforeCompile(): void
    {
        $builder = $this->getContainerBuilder();
        $app = $builder->getDefinitionByType(Application::class);
        if ($app instanceof ServiceDefinition) {
            $app->addSetup('$service->onResponse[]=?', [[$this->sender, 'showDebugMails']]);
        }
        if ($this->translation) {
            $this->sender->addSetup('$service->setTranslationProvider(?)', [$this->translation]);
        }
    }


}
