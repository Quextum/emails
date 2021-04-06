<?php


namespace Quextum\Emails\Translation;


use Nette\Bridges\ApplicationLatte\Template;

class NoTranslationProvider implements TranslationProvider
{
    public function apply(string $type, array $configuration, Template $template): void
    {

    }
}