<?php
namespace Acilia\Bundle\MetaTagsBundle\Library\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Acilia\Bundle\MetaTagsBundle\Library\Twig\Tag\MetaTags\ConfiguratorTokenParser;
use Acilia\Bundle\MetaTagsBundle\Service\MetaTagsService;

class MetaTagsExtension extends \Twig_Extension
{
    protected $metaTagsService;

    public function __construct(MetaTagsService $metaTagsService)
    {
        $this->metaTagsService = $metaTagsService;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('render_metatags', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    public function render()
    {
        return $this->metaTagsService->render();
    }

    public function getTokenParsers()
    {
        return array(
            new ConfiguratorTokenParser(),
        );
    }

    public function getName()
    {
        return 'acilia.metatags.twig_extension';
    }

    public function configure($configuration)
    {
        $this->metaTagsService->configure($configuration);
    }
}
