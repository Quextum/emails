<?php declare(strict_types=1);

namespace Quextum\Emails;


use Nette\Application\LinkGenerator;
use Nette\Application\UI\Template;
use Nette\Application\UI\TemplateFactory;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use Nette\DI\Attributes\Inject;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\InvalidArgumentException;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Mail\SendException;
use Nette\Schema\Helpers;
use Nette\SmartObject;
use Quextum\Emails\Translation\TranslationProvider;
use stdClass;
use Tracy\Debugger;
use function is_int;

/**
 * Description of MailSender
 * @method onError(SendException $exception, Message $message, array $detail)
 * @method onBeforeSend(Message $message)
 * @method onSend(Message $message)
 * @method onConfig(array &$configuration)
 * @author Jan Proskovec
 */
class MailSender
{
    use SmartObject;

    protected static array $setters = [
        'to' => 'addTo',
        'cc' => 'addCc',
        'bb' => 'addBcc',
        'reply' => 'addReplyTo',

        'from' => 'setFrom',
        'subject' => 'setSubject',
        'return' => 'setReturnPath',
        'embed' => 'addEmbeddedFile',
        'attachment' => 'addAttachment'
    ];

    public array $onSend;
    public array $onConfig;
    public array $onBeforeSend;
    public array $onError;

    #[Inject]
    public Session $session;
    #[Inject]
    public Mailer $sender;
    #[Inject]
    public TemplateFactory $templateFactory;
    #[Inject]
    public LinkGenerator $linkGenerator;

    public TranslationProvider|null $translation = null;

    protected array $defaults;
    protected string $templatesDirectory;
    private bool $catchExceptions;

    /**
     * MailSender constructor.
     * @param bool $catchExceptions
     * @param array $defaults
     * @param string $templatesDirectory
     */
    public function __construct(
        bool $catchExceptions,
        array $defaults,
        string $templatesDirectory)
    {
        $this->catchExceptions = $catchExceptions;
        $this->defaults = $defaults;
        $this->templatesDirectory = $templatesDirectory;
        $this->onBeforeSend[] = function (Message $message) {
            $this->debugMail($message);
        };
        $this->onError[] = function (\Exception $exception, Message $message, array $detail) {
            bdump($exception);
            bdump($detail);
        };
        $this->onConfig[] = function (array &$config) {
            bdump($config, 'onConfig');
        };
    }

    public function debugMail(Message $message): void
    {
        if (Debugger::isEnabled() && $section = $this->getSession('emails')) {
            $key = hash('crc32', serialize($message));
            $section->offsetSet($key, $message);
            $section->setExpiration('10 minutes');
        }
    }

    /**
     *
     * @param string|null $namespace
     * @return Session|SessionSection
     */
    private function getSession(string $namespace = null): Session|SessionSection
    {
        return $namespace ? $this->session->getSection($namespace) : $this->session;
    }

    /**
     * @param string $type
     * @param array $settings
     * @param array|stdClass $params
     * @return Message
     * @throws InvalidArgumentException
     * @throws SendException
     */
    public function send(string $type, array $settings, $params = []): Message
    {
        $message = $this->createMessage($type, $settings, $params);
        try {
            $this->onBeforeSend($message);
            $this->sender->send($message);
            $this->onSend($message);
        } catch (SendException $e) {
            //bdump($e);
            $this->onError($e, $message, get_defined_vars());
            if (!$this->catchExceptions) {
                // Na vývojovém prostředí je na localhostu chyba pravděpodobná pokud není správně nastaveno odesílání.
                throw $e;
            }
        }
        return $message;
    }

    private function getTemplateFile(string $type): string
    {
        $file = $this->templatesDirectory . DIRECTORY_SEPARATOR . $type . '.latte';
        if (!is_file($file)) {
            throw new InvalidArgumentException("Type '$type' is not supported. Template file '$file' does not exists.");
        }
        return $file;
    }

    /**
     * @param string $type
     * @param array $settings
     * @param array $templateVariables
     * @return Message
     * @throws InvalidArgumentException
     */
    public function createMessage(string $type, array $settings, array $templateVariables = []): Message
    {
        $file = $this->getTemplateFile($type);
        $message = new Message();

        /** @var DefaultTemplate $template */
        $template = $this->createTemplate();
        $template->setFile($file);
        $defaultConfiguration = $this->defaults[$type] ?? [];
        $configuration = Helpers::merge($settings, $defaultConfiguration);
        if ($variables = $configuration['variables']) {
            $template->setParameters($variables);
        }
        if ($this->translation) {
            $this->translation->apply($type, $configuration, $template);
        }
        $this->onConfig($configuration);
        foreach (self::$setters as $prop => $method) {
            if ($value = $configuration[$prop] ?? null) {
                foreach ((array)$value as $key => $args) {
                    $args = (array)$args;
                    is_int($key) || array_unshift($args, $key);
                    $message->$method(...$args);
                }
            }
        }
        $template->setParameters([
            '_message' => $message
        ]);
        $template->setParameters($templateVariables);

        $message->setHtmlBody((string)$template);
        return $message;
    }

    /**
     * @return Template
     */
    protected function createTemplate(): Template
    {
        $template = $this->templateFactory->createTemplate();
        $latte = $template->getLatte();
        $latte->addProvider('uiControl', $this->linkGenerator);//Zprovoznění makra link, generované linky jsou absolutní
        return $template;
    }

    public function showDebugMails(): void
    {
        if (session_status() === PHP_SESSION_DISABLED) {
            bdump('Session is not available. Mail debug bar is not working');
        } else if ($this->getSession()->hasSection('emails')
            && ($section = $this->getSession('emails'))) {
            $bar = Debugger::getBar();
            foreach ($section as $key => $message) {
                $bar->addPanel(new EmailDebugPanel($message));
            }
        }
    }

    /**
     * @param TranslationProvider $translation
     */
    public function setTranslationProvider(TranslationProvider $translation): void
    {
        $this->translation = $translation;
    }

    /**
     * @return TranslationProvider
     */
    public function getTranslationProvider(): TranslationProvider
    {
        return $this->translation;
    }

}
