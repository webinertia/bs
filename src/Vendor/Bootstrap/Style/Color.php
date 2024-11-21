<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap\Style;

use function is_string;

enum Color: string
{
    use SeparaterTrait;

    case Primary   = 'primary';
    case Secondary = 'secondary';
    case Success   = 'success';
    case Danger    = 'danger';
    case Warning   = 'warning';
    case Info      = 'info';
    case Light     = 'light';
    case Dark      = 'dark';

    public function color(ColorType|string $type)
    {
        if (is_string($type)) {
            $type = ColorType::tryFrom($type);
        }

        return $type->value . (string) $this;
    }
}
