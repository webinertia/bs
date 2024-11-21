<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap\Style;

enum Breakpoint: string
{
    use SeparaterTrait;

    case Xs  = 'xs';
    case Sm  = 'sm';
    case Md  = 'md';
    case Lg  = 'lg';
    case Xl  = 'xl';
    case Xxl = 'xxl';
}
