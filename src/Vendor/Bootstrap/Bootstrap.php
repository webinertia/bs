<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap;

use Limatus\Form\View\Helper\Event\RenderEvent;
use Limatus\View\Helper\HtmlTag;
use Limatus\Vendor\Bootstrap\Style;
use Limatus\VendorInterface;

use function explode;
use function sprintf;

final class Bootstrap implements VendorInterface
{
    private string $class     = 'class';
    private string $id        = 'id';
    private ?string $markup   = null;
    private string $colClass;
    private string $rowClass;

    public function __construct(
        private HtmlTag $helper
    ) {
        $this->colClass = OptionKey::ColumnKey->keyValue();
        $this->rowClass = OptionKey::RowKey->keyValue();
    }

    public function render(RenderEvent $event): string
    {
        return match($event->getLayoutMode()) {
            LayoutMode::Default,
            LayoutMode::Grid => $this->grid($event->getAttributes(), $event->getOptions(), $event->getMarkup()),
            LayoutMode::Horizontal => $this->horizontal($event->getAttributes(), $event->getOptions(), $event->getMarkup()),
            LayoutMode::Inline => $this->inline($event->getAttributes(), $event->getOptions(), $event->getMarkup()),
            default => $event->getMarkup(),
        };
    }

    public function renderCollection(RenderEvent $event): string
    {
        $legend = '';
        /** @var Laminas\Form\Element */
        $element = $event->getElement();
        $formHelper = $event->getTarget();
        $tag     = $formHelper->getLabelWrapper();
        $markup  = $event->getMarkup();
        //$options = $event->getOptions();
        $attribs = $event->getAttributes();
        $label   = $event->getParam('label');
        //$labelOptions = $element->getLabelOptions();
        $labelAttribs = $element->getLabelAttributes();
        if (! empty($label)) {
            $this->helper->set($labelAttribs);
            $legend = $this->helper->openTag($tag) . $label . $this->helper->closeTag();
        }
        $this->helper->set($attribs);
        $attribString = $this->helper->getAttribString();
        $wrapper = $formHelper->getWrapper();
        $template = $formHelper->getTemplateWrapper();
        return sprintf(
            $wrapper,
            $markup,
            $legend,
            $template,
            $attribString
        );
    }

    public function renderInput(RenderEvent $event): string
    {
        return $this->grid(
            $event->getAttributes(),
            $event->getOptions(),
            $event->getMarkup()
        );
    }

    protected function grid(iterable $attribs, iterable $options, string $elementString): string
    {
        $target = $options[OptionKey::VendorKey->value][OptionKey::ColumnKey->value] ?? null;
        if (! empty($target) && ! $this->helper->hasValue($this->class, $target)) {
            $this->helper->set([
                $this->class => $options[OptionKey::VendorKey->value][OptionKey::ColumnKey->value]
            ]);
        }
        // wrap it in a div with the column value
        $this->markup = $this->helper->openTag() . $elementString . $this->helper->closeTag();

        return $this->markup;
    }

    protected function horizontal(iterable $attribs, iterable $options, string $elementString): string
    {
        $this->helper->set([$this->class => []]);
        $target = $options[OptionKey::VendorKey->value][OptionKey::RowKey->value] ?? null;
        if (! empty($target)) {
            if (! $this->helper->hasValue($this->class, $this->rowClass)) {
                $this->helper->set([$this->class => $this->rowClass]);
            }
        }
        $this->markup = $this->helper->openTag() . $elementString . $this->helper->closeTag();
        return $this->markup;
    }

    protected function inline(iterable $attribs, iterable $options, string $elementString): string
    {
        return $this->markup;
    }
}
