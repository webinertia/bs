<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap\Listener;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\Filter\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormLabel;
use Limatus\Events;
use Limatus\Form\RenderListenerInterface;
use Limatus\Form\View\Helper\Event\RenderEvent;
use Limatus\Vendor\Bootstrap\Bootstrap;
use Limatus\Vendor\Bootstrap\LayoutMode;
use Limatus\Vendor\InputType;
use Limatus\VendorInterface;
use Webinertia\Filter\DelimitedString;

use function is_string;

final class RenderListener extends AbstractListenerAggregate implements RenderListenerInterface
{
    use ListenerAggregateTrait;

    private ?LayoutMode $layoutMode = LayoutMode::Default;

    public function __construct(
        private VendorInterface&Bootstrap $bs,
        private DelimitedString $stringFilter,
        private array $config
    ) {
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(Events::PreRenderForm->value, [$this, 'onPreRenderForm'], $priority);

        $this->listeners[] = $events->attach(Events::PreRenderRow->value, [$this, 'onPreRenderRow'], $priority);
        $this->listeners[] = $events->attach(Events::PostRenderRow->value, [$this, 'onPostRenderRow'], $priority);

        $this->listeners[] = $events->attach(Events::PreRenderElement->value, [$this, 'onPreRenderElement'], $priority);
        $this->listeners[] = $events->attach(Events::RenderElement->value, [$this, 'onRenderElement'], $priority);
        $this->listeners[] = $events->attach(Events::PostRenderElement->value, [$this, 'onPostRenderElement'], $priority);

        $this->listeners[] = $events->attach(Events::PostRenderInput->value, [$this, 'onPostRenderInput'], $priority);

        $this->listeners[] = $events->attach(Events::RenderCollection->value, [$this, 'onRenderCollection'], $priority);

        // label listeners
        //$this->listeners[] = $events->attach(Events::PreRenderLabel->value, [$this, 'onPreRenderLabel'],  $priority);
    }

    public function onPreRenderForm(RenderEvent $event)
    {
        $attribs = $event->getAttributes();
        $options = $event->getOptions();
        $this->layoutMode = $event->getLayoutMode();

    }

    /**
     * Triggerd from FormCollectionDelegator
     *
     * @param RenderEvent $event
     * @return string
     */
    public function onRenderCollection(RenderEvent $event)
    {
        return $this->bs->renderCollection($event);
    }

    public function onPreRenderRow(RenderEvent $event)
    {

    }

    public function onPostRenderRow(RenderEvent $event)
    {
        $attribs = $event->getAttributes();
        $options = $event->getOptions();
        $markup  = $event->getMarkup();
    }

    /**
     * Triggered from FormRowDelegator
     *
     * This listener does the following
     * If the element does not have an id attribute then it sets the attribute to the name to prevent
     * laminas form from wrapping the label since all Bootstrap components use non wrapped labels
     * @param RenderEvent $event
     * @return void
     * @throws InvalidArgumentException
     */
    public function onPreRenderElement(RenderEvent $event): void
    {
        $element = $event->getElement();
        if (! $element->hasAttribute('id')) {
            /**
             * todo: test this on nested fieldsets that are more than a single fieldset deep, ie fieldset in a fieldset
             * possible solution is to have the filter return all matches
             */
            $id = $this->stringFilter->filter($element->getName());
            if (is_string($id) && $id !== '') {
                $element->setAttribute('id', $id);
            }
        }
    }

    public function onRenderElement(RenderEvent $event)
    {
        //return $this->bs->render($event);
    }

    public function onPostRenderElement(RenderEvent $event)
    {
        return $this->bs->render($event);
    }

    /**
     * Triggered from FormRowDelegator
     *
     * @param RenderEvent $event
     * @return null|string|false
     */
    public function onPostRenderInput(RenderEvent $event)
    {
        $layoutMode = $event->getLayoutMode();
        $element    = $event->getElement();
        $inputType  = InputType::tryFrom($element::class);
        if ($inputType === InputType::Hidden) {
            return false;
        }
        if ($layoutMode === LayoutMode::Horizontal || $layoutMode === LayoutMode::Inline) {
            return $this->bs->renderInput($event);
        }
        return true;
    }

    public function onPreRenderLabel(RenderEvent $event)
    {
        $options = $event->getOptions();
        if (! isset($options['label_options']['position'])) {
            $options['label_options']['position'] = FormLabel::PREPEND;
        }
        //return $this->bs->render($event);
    }
}
