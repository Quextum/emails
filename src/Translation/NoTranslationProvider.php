<?php


namespace Quextum\Emails\Translation;


use Nette\Bridges\ApplicationLatte\Template;

class NoTranslationProvider implements TranslationProvider
{
    public function __construct(string $namespace)
    {
    }

    public function apply(string $type, array &$configuration, Template $template): void
    {

    }
}