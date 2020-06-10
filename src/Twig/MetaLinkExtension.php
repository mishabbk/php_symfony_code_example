<?php

namespace App\Twig;

use Throwable;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class MetaLinkExtension.
 */
class MetaLinkExtension extends AbstractExtension
{
    private const TEMPLATE = 'Extension/link.html.twig';

    /**
     * @var array
     */
    private $items = [
        'link' => [],
        'meta' => [],
    ];

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('link_push', [$this, 'pushLink']),
            new TwigFunction('meta_push', [$this, 'pushMeta']),
            new TwigFunction(
                'meta_link_render',
                [$this, 'render'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }

    public function pushLink(string $rel, string $href)
    {
        $this->items['link'][$rel] = [
            'rel'  => $rel,
            'href' => $href,
        ];
    }

    public function pushMeta(string $name, string $content)
    {
        $this->items['meta'][$name] = [
            'name'    => $name,
            'content' => $content,
        ];
    }

    public function noIndexNoFollow()
    {
        $this->pushMeta('robots', 'noindex,nofollow');
    }

    /**
     * @param string $url
     */
    public function setCanonical($url)
    {
        $this->pushLink('canonical', $url);
    }

    /**
     * @throws Throwable
     *
     * @return string
     */
    public function render(Environment $environment)
    {
        return $environment->load(self::TEMPLATE)->renderBlock(
            'links',
            [
                'items' => $this->items,
            ]
        );
    }
}
