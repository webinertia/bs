<?php

declare(strict_types=1);

namespace Limatus\Form\Element\Listener;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Form\Element;
use Laminas\View\HtmlAttributesSet;
use Limatus\Events;

final class ElementListener extends AbstractListenerAggregate
{
    public function __construct(
        private HtmlAttributesSet $helper
    ) {
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(Events::SetOptions->value, [$this, 'onSetOptions'], 100);
    }

    public function onSetOptions(EventInterface $event)
    {
        /** @var Element */
        $element    = $event->getTarget();
        $options    = $element->getOptions();
        $attributes = $element->getAttributes();
        $this->helper->set($attributes);
        foreach ($options as $key => $value) {
            switch (true) {
                case $key === 'column':
                case $key === 'row':
                case $key === 'bootstrap_attributes':
                    $this->helper->add('class', $value);
                    break;

                default:
                    # code...
                    break;
            }
        }
        // todo: fix the array to string this causes when AbstractHelper::prepareAttributes is called
        // maybe override prepareAttributes and trigger listener to handle it?
        $element->setAttributes($this->helper->getArrayCopy());
    }
}
