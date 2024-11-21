<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap\Style;

trait SeparaterTrait
{
    public function toString(): string
    {
        return '-' . $this->value;
    }
}
