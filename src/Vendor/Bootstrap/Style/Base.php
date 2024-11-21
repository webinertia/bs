<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap\Style;

enum Base: string
{
    case FormControl    = 'form-control ';
    case FormFile       = 'form-control-file ';
    case FormSelect     = 'form-select ';
    case FormCheckInput = 'form-check-input ';
    case FormCheckLabel = Label::FormCheck->value . ' ';
    case Button         = 'btn ';
    case TextNoWrap     = 'text-nowrap ';
    case Plaintext      = 'form-control-plaintext ';

    public function toString()
    {
        return $this->value;
    }
}
