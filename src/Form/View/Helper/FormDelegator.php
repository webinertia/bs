<?php

declare(strict_types=1);

namespace Limatus\Form\View\Helper;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Form\Form;
use Laminas\Form\FieldsetInterface;
use Laminas\Form\FormInterface;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\Form\View\Helper\Form as FormHelper;
use Limatus\Events;
use Limatus\Form\View\Helper\Event\RenderEvent;

final class FormDelegator extends FormHelper implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Attribute prefixes valid for all tags
     *
     * @var array
     */
    protected $validTagAttributePrefixes = [
        'data-', // https://html.spec.whatwg.org/#attr-data-*
        'aria-', // https://html.spec.whatwg.org/#attr-aria-*
        // start htmx
        'hx-',
        'hx-swap-',
        'x-',
    ];
    /**
     * Attributes valid for this tag (form)
     *
     * @var array
     */
    protected $validTagAttributes = [
        'accept-charset' => true,
        'action'         => true,
        'autocomplete'   => true,
        'enctype'        => true,
        'method'         => true,
        'name'           => true,
        'novalidate'     => true,
        'target'         => true,
        // start htmx
        'post'           => true,
        'get'            => true,
        'put'            => true,
        'trigger'        => true,
        'swap'           => true,
        'sync'           => true,
    ];

    /**
     * Render a form from the provided $form,
     * @param Form $form
     */
    public function render(FormInterface $form): string
    {
        if (method_exists($form, 'prepare')) {
            $form->prepare();
        }

        $formContent = '';

        $renderer = $this->getView();
        assert($renderer instanceof PhpRenderer);
        // what LayoutMode should this helper use?,
        $event = new RenderEvent(Events::PreRenderForm->value, $this);
        $event->setAttributes($form->getAttributes())
                ->setOptions($form->getOptions())
                ->setElement($form);

        $this->getEventManager()->triggerEvent($event);

        foreach ($form as $element) {
            if ($element instanceof FieldsetInterface) {
                $formContent .= $renderer->formCollection($element);
            } else {
                $formContent .= $renderer->formRow($element);
            }
        }

        return $this->openTag($form) . $formContent . $this->closeTag();
    }
}
