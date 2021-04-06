<?php


namespace Quextum\Emails\Translation;

use Nette\Bridges\ApplicationLatte\Template;

interface TranslationProvider
{
    public function apply(string $type, array &$configuration, Template $template): void;
}