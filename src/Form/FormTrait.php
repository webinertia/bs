<?php

declare(strict_types=1);

namespace Limatus\Form;

use Laminas\Form\Element;

trait FormTrait
{
    public function addSubmit(
        ?int $priority = 1,
        ?string $label = 'Save',
        ?string $class = 'btn btn-sm btn-secondary'
    ): void {
        $this->add(
            [
                'name'       => 'submit',
                'type'       => Element\Submit::class,
                'attributes' => [
                    'id'    => $this->getAttribute('name'),
                    'value' => $label,
                    'class' => $class,
                ],
            ],
            ['priority' => $priority],
        );
    }
}
