<?php

declare(strict_types=1);

namespace Limatus\Form\View\Helper;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Captcha;
use Laminas\Form\Element\MonthSelect;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper\FormRow;
use Limatus\Events;
use Limatus\Form\View\Helper\Event\RenderEvent;

use function in_array;
use function method_exists;
use function sprintf;
use function strtolower;

class FormRowDelegator extends FormRow implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    public function __construct(
        private FormRow $formRow
    ) {
    }

    /**
     * Utility form helper that renders a label (if it exists), an element and errors
     *
     * @throws Exception\DomainException
     */
    public function render(ElementInterface $element, ?string $labelPosition = null): string
    {
        $manager = $this->getEventManager();
        // label is not being handled correctly, its nested. Unwrap it.
        $labelHelper         = $this->getLabelHelper();
        $elementHelper       = $this->getElementHelper();
        $elementErrorsHelper = $this->getElementErrorsHelper();

        $label           = $element->getLabel() ?? '';
        $inputErrorClass = $this->getInputErrorClass();

        if ($labelPosition === null) {
            $labelPosition = $this->labelPosition;
        }

        if ('' !== $label) {
            // Translate the label
            $label = $this->translateLabel($label);
        }

        // Does this element have errors ?
        if ($element->getMessages() && $inputErrorClass) {
            $classAttributes  = $element->hasAttribute('class') ? $element->getAttribute('class') . ' ' : '';
            $classAttributes .= $inputErrorClass;

            $element->setAttribute('class', $classAttributes);
        }

        if ($this->partial) {
            $vars = [
                'element'         => $element,
                'label'           => $label,
                'labelAttributes' => $this->labelAttributes,
                'labelPosition'   => $labelPosition,
                'renderErrors'    => $this->renderErrors,
            ];

            return $this->view->render($this->partial, $vars);
        }

        $elementErrors = '';
        if ($this->renderErrors) {
            $elementErrors = $elementErrorsHelper->render($element);
        }
        // trigger PreRenderElement to set id
        $event = new RenderEvent(Events::PreRenderElement->value, $this);
        $event->setElement($element);
        $result = $manager->triggerEvent($event);
        if ($result->last()) {
            // should be true
        }
        $elementString = $elementHelper->render($element);

        // trigger post render input,
        $event = new RenderEvent(Events::PostRenderInput->value, $this);
        $event->setElement($element);
        $event->setMarkup($elementString);
        $result = $manager->triggerEvent($event);
        $result = $result->last();
        // should only be true if layoutMode is not default or grid
        if (is_string($result)) {
            $elementString = $result;
        }

        // hidden elements do not need a <label> -https://github.com/zendframework/zf2/issues/5607
        $type = $element->getAttribute('type');

        if ($label === '' || $type === 'hidden') {
            return $elementString . $elementErrors;
        }

        $labelAttributes = [];

        if ($element instanceof LabelAwareInterface) {
            $labelAttributes = $element->getLabelAttributes();
        }

        $label = $this->escapeLabel($element, $label);

        if (empty($labelAttributes)) {
            $labelAttributes = $this->labelAttributes;
        }

        // Multicheckbox elements have to be handled differently as the HTML standard does not allow nested
        // labels. The semantic way is to group them inside a fieldset
        if (
            $type === 'multi_checkbox'
            || $type === 'radio'
            || $element instanceof MonthSelect
            || $element instanceof Captcha
        ) {
            // todo: trigger event to build correct markup for this
            $markup = sprintf(
                '<fieldset><legend>%s</legend>%s</fieldset>',
                $label,
                $elementString
            );

            return $markup . $elementErrors;
        }

        // Ensure element and label will be separated if element has an `id`-attribute.
        // If element has label option `always_wrap` it will be nested in any case.
        if (
            $element->hasAttribute('id')
            && ($element instanceof LabelAwareInterface && ! $element->getLabelOption('always_wrap'))
        ) {
            $labelOpen  = '';
            $labelClose = '';
            $label      = $labelHelper->openTag($element) . $label . $labelHelper->closeTag();
        } else {
            $labelOpen  = $labelHelper->openTag($labelAttributes);
            $labelClose = $labelHelper->closeTag();
        }
        // label is empty string and has no id OR has always_wrap option
        if (
            $label !== '' && (! $element->hasAttribute('id'))
            || ($element instanceof LabelAwareInterface && $element->getLabelOption('always_wrap'))
        ) {
            $label = '<span>' . $label . '</span>';
        }

        // Button element is a special case, because label is always rendered inside it
        if ($element instanceof Button) {
            $labelOpen = $labelClose = $label = '';
        }

        if ($element instanceof LabelAwareInterface && $element->getLabelOption('label_position')) {
            $labelPosition = $element->getLabelOption('label_position');
        }

        $markup = match ($labelPosition) {
            self::LABEL_PREPEND => $labelOpen . $label . $elementString . $labelClose,
            default => $labelOpen . $elementString . $label . $labelClose,
        };
        // trigger the post render element event to wrap the element in the div
        $event = new RenderEvent(Events::PostRenderElement->value, $this);
        $event->setElement($element);
        $event->setMarkup($markup . $elementErrors);
        $result = $manager->triggerEvent($event);
        return $result->last();
    }
}
