<?php

declare(strict_types=1);

namespace Limatus\View\Helper;

use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\View\HtmlAttributesSet;

use function method_exists;
use function sprintf;

/**
 * @package Limatus\View\Helper
 *
 * Proxy method calls to HtmlAttributesSet (@see __call):
 *
 * @method \Laminas\View\HtmlAttributesSet set(iterable $attributes)
 * @method \Laminas\View\HtmlAttributesSet add(string $name, $value)
 * @method \Laminas\View\HtmlAttributesSet merge(iterable $attributes)
 * @method \Laminas\View\HtmlAttributesSet hasValue(string $name, $value)
 */
class HtmlTag extends AbstractHelper
{
    private ?string $tag = 'div';

    public function __construct(
        private HtmlAttributesSet $helper
    ) {
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function openTag(string $tag = null): string
    {
        if (null !== $tag) {
            $this->tag = $tag;
        }
        return sprintf(
            '<%s%s>',
            $this->tag,
            (string) $this->helper
        );
    }

    public function closeTag(): string
    {
        return sprintf('</%s>', $this->tag);
    }

    public function getAttribString(): string
    {
        return (string) $this->helper;
    }

    public function __call($name, $arguments)
    {
        // only make this call if the method exist
        if (method_exists($this->helper, $name)) {
            return $this->helper->$name(...$arguments);
        }
    }
}
