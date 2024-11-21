<?php

declare(strict_types=1);

namespace Limatus\Form\View\Helper;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper\FormElement;
use Limatus\Events;
use Limatus\Form\View\Helper\Event\RenderEvent;

final class FormElementDelegator extends FormElement implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Render an element
     *
     * Introspects the element type and attributes to determine which
     * helper to utilize when rendering.
     */
    public function render(ElementInterface $element): string
    {
        $renderer = $this->getView();
        if ($renderer === null || ! method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }
        // return the standard laminas-form rendering
        return parent::render($element);
    }
}
