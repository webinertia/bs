<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap\Style;

enum Grid: string
{
    case One    = '1';
    case Two    = '2';
    case Three  = '3';
    case Four   = '4';
    case Five   = '5';
    case Six    = '6';
    case Seven  = '7';
    case Eight  = '8';
    case Nine   = '9';
    case Ten    = '10';
    case Eleven = '11';
    case Twelve = '12';
    case Auto   = Column::Auto->value;
}
