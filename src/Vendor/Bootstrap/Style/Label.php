<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap\Style;

enum Label: string
{
    case FormLabel      = 'form-label';
    case FormCheck      = 'form-check-label';
    // todo check this usage
    case FieldsetLegend = 'col-form-label';
    case Hidden         = 'visually-hidden';
}
