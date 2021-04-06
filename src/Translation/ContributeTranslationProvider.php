<?php


namespace Quextum\Emails\Translation;


use Contributte\Translation\Translator;
use Contributte\Translation\Wrappers\Message;
use Nette\Bridges\ApplicationLatte\Template;

class ContributeTranslationProvider implements TranslationProvider
{
    private Translator $translator;
    private string $namespace;

    /**
     * ContributeTranslatorFactory constructor.
     * @param Translator $translator
     * @param string $namespace
     */
    public function __construct(string $namespace, Translator $translator)
    {
        $this->translator = $translator;
        $this->namespace = $namespace;
    }


    public function apply(string $type, array &$configuration, Template $template): void
    {
        $locale = $configuration['locale'];
        $translator = $this->translator->createPrefixedTranslator("$this->namespace.$type");
        $template->locale = $locale;
        $template->setTranslator($translator);
        array_walk_recursive($configuration, function (mixed &$value) use ($translator): mixed {
            if ($value instanceof Message) {
                $value = $translator->translate($value);
            }
            return $value;
        });
    }

}