<?php declare(strict_types=1);

namespace ApiGen\Generator\TemplateGenerators;

use ApiGen\Configuration\Theme\ThemeConfigOptions;
use ApiGen\Contracts\Generator\TemplateGenerators\TemplateGeneratorInterface;
use ApiGen\Templating\TemplateFactory;

final class CombinedGenerator implements TemplateGeneratorInterface
{
    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    public function __construct(TemplateFactory $templateFactory)
    {
        $this->templateFactory = $templateFactory;
    }

    public function generate(): void
    {
        $template = $this->templateFactory->createForType(ThemeConfigOptions::COMBINED);
        $template->save();
    }
}
