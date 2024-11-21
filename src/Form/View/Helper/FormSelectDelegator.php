<?php

declare(strict_types=1);

namespace Limatus\Form\View\Helper;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Form\ElementInterface;
use Laminas\Form\Element\Select as SelectElement;
use Laminas\Form\Exception;
use Laminas\Form\View\Helper\FormSelect;
use Limatus\Events;

final class FormSelectDelegator extends FormSelect implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    public function __construct(
        private FormSelect $formSelect
    ) {
    }

    /**
     * Render a form <select> element from the provided $element
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     */
    public function render(ElementInterface $element): string
    {
        if (! $element instanceof SelectElement) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Laminas\Form\Element\Select',
                __METHOD__
            ));
        }

        $name = $element->getName();
        if ($name === null || $name === '') {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $options = $element->getValueOptions();

        if (($emptyOption = $element->getEmptyOption()) !== null) {
            $options = ['' => $emptyOption] + $options;
        }

        $attributes = $element->getAttributes();
        $value      = $this->validateMultiValue($element->getValue(), $attributes);

        $attributes['name'] = $name;
        if (array_key_exists('multiple', $attributes) && $attributes['multiple']) {
            $attributes['name'] .= '[]';
        }
        $this->validTagAttributes = $this->validSelectAttributes;

        // trigger pre.render
        // $this->getEventManager()->trigger(
        //     Events::PreRenderElement->value, $this,
        //     [
        //         'attributes' => &$attributes,
        //         'options'    => &$options,
        //     ]
        // );

        $rendered = sprintf(
            '<select %s>%s</select>',
            $this->createAttributesString($attributes),
            $this->renderOptions($options, $value)
        );

        // Render hidden element
        if ($element->useHiddenElement()) {
            $rendered = $this->renderHiddenElement($element) . $rendered;
        }

        // trigger post.render
        // $this->getEventManager()->trigger(
        //     Events::PostRenderElement->value, $this,
        //     [
        //         'markup' => &$rendered,
        //     ]
        // );

        return $rendered;
    }
}
