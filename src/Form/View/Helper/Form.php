<?php

declare(strict_types=1);

namespace Limatus\Form\View\Helper;

use Laminas\Form\FieldsetInterface;
use Laminas\Form\FormInterface;
use Laminas\View\Helper\Doctype;
use Laminas\View\Renderer\PhpRenderer;
use Limatus\Form\ModeAwareInterface;

use function array_key_exists;
use function array_merge;
use function assert;
use function method_exists;
use function sprintf;

/**
 * View helper for rendering Form objects
 */
class Form extends AbstractHelper
{
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
     * Invoke as function
     *
     * @template T as null|FormInterface
     * @psalm-param T $form
     * @psalm-return (T is null ? self : string)
     * @return Form|string
     */
    public function __invoke(?FormInterface $form = null)
    {
        if (! $form) {
            return $this;
        }
        return $this->render($form);
    }

    /**
     * Render a form from the provided $form,
     */
    public function render(FormInterface $form): string
    {
        if (method_exists($form, 'prepare')) {
            // should also set the mode for fieldsets and elements to the forms mode
            $form->prepare();
        }

        $formContent = '';

        $renderer = $this->getView();
        assert($renderer instanceof PhpRenderer);
        foreach ($form as $element) {
            if ($element instanceof ModeAwareInterface && $element instanceof FieldsetInterface) {
                $mode = $element->getMode();

                $formContent = match ($mode) {
                    ModeAwareInterface::GRID_MODE => $renderer->formGridCollection($element),
                    ModeAwareInterface::HORIZONTAL_MODE,
                    ModeAwareInterface::INLINE_MODE,
                    ModeAwareInterface::DEFAULT_MODE => $renderer->formCollection($element),
                };
            } elseif ($element instanceof FieldsetInterface) {
                $formContent .= $renderer->formCollection($element);
            } else {
                $formContent .= $renderer->formRow($element);
            }
        }

        return $this->openTag($form) . $formContent . $this->closeTag();
    }

    /**
     * Generate an opening form tag
     */
    public function openTag(?FormInterface $form = null): string
    {
        $doctype    = $this->getDoctype();
        $attributes = [];

        if (! (Doctype::HTML5 === $doctype || Doctype::XHTML5 === $doctype)) {
            $attributes = [
                'action' => '',
                'method' => 'get',
            ];
        }

        if ($form instanceof FormInterface) {
            // bootstrap start
            //$this->bootstrapForm($form, $mode);
            // bootstrap end
            $formAttributes = $form->getAttributes();
            if (! array_key_exists('id', $formAttributes) && array_key_exists('name', $formAttributes)) {
                $formAttributes['id'] = $formAttributes['name'];
            }
            $attributes = array_merge($attributes, $formAttributes);
        }

        if ($attributes) {
            return sprintf('<form %s>', $this->createAttributesString($attributes));
        }

        return '<form>';
    }

    /**
     * Generate a closing form tag
     */
    public function closeTag(): string
    {
        return '</form>';
    }
}
