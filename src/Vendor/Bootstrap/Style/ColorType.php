<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap\Style;

enum ColorType: string
{
    case Alert = 'alert';
    case Bg     = 'bg';
    case Border = 'border';
    case Button = 'btn';
    case Text   = 'text';
}
