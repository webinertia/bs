<?php

declare(strict_types=1);

namespace Limatus\Form\View\Helper\Event;

use Laminas\EventManager\Event;
use Laminas\Form\ElementInterface;
use Limatus\Vendor\Bootstrap\LayoutMode;
use Limatus\Vendor\InputType;
use Limatus\VendorInterface;

use function explode;

class RenderEvent extends Event
{
    /** @var ?iterable $options */
    /** @var ?iterable $attribs */
    /** @var string    $markup */
    /** @var ?string   $type */
    /** @var ?string   $classString */
    /** @var ?LayoutMode $layoutMode */
    /** @var ?ElementInterface $element */

    public function setAttributes(iterable $attribs): self
    {
        if (isset($attribs['type']) && InputType::tryFrom($attribs['type']) instanceof InputType) {
            $this->setType($attribs['type']);
        }
        /**
         * convert strings into arrays so that the HtmlTag helper can search them
         */
        if (! empty($attribs['class']) && is_string($attribs['class'])) {
            $attribs['class'] = explode(' ', $attribs['class']);
        }
        $this->setParam('attribs', $attribs);
        return $this;
    }

    public function getAttributes(): iterable
    {
        return $this->getParam('attribs', []);
    }

    public function setElement(?ElementInterface $element): self
    {
        $this->setOptions($element->getOptions());
        $this->setAttributes($element->getAttributes());
        $this->setParam('element', $element);
        return $this;
    }

    public function getElement(): ?ElementInterface
    {
        return $this->getParam('element');
    }

    public function setMarkup(string $markup): self
    {
        $this->setParam('markup', $markup);
        return $this;
    }

    public function getMarkup(): string
    {
        return $this->getParam('markup');
    }

    public function setOptions(iterable $options): self
    {
        if (
            isset($options[VendorInterface::class]['layout_mode'])
            && $options[VendorInterface::class]['layout_mode'] instanceof LayoutMode
        ) {
            $this->setLayoutMode($options[VendorInterface::class]['layout_mode']);
        }
        $this->setParam('options', $options);
        return $this;
    }

    public function getOptions(): ?iterable
    {
        return $this->getParam('options');
    }

    public function setType(?string $type): self
    {
        $this->setParam('type', $type);
        return $this;
    }

    public function getType(): ?string
    {
        return $this->getParam('type');
    }

    public function setClassString(?string $classString): self
    {
        $this->setParam('classString', $classString);
        return $this;
    }

    public function getClassString(): ?string
    {
        return $this->getParam('classString');
    }

    public function setLayoutMode(?LayoutMode $layoutMode): self
    {
        $this->setParam('layoutMode', $layoutMode);
        return $this;
    }

    public function getLayoutMode(): ?LayoutMode
    {
        return $this->getParam('layoutMode');
    }
}
