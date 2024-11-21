<?php

declare(strict_types=1);

namespace Limatus\Vendor;

use Laminas\Form\Element;
use Laminas\Form\Fieldset;

enum InputType: string
{
    public const ResetAlias = '\Laminas\Form\Element\Reset'; // create this here for consistency

    case Button              = Element\Button::class;
    case Checkbox            = Element\Checkbox::class;
    case Collection          = Element\Collection::class;
    case Date                = Element\Date::class;
    case DateTime            = Element\DateTime::class;
    case DateTimeLocal       = Element\DateTimeLocal::class;
    case DateTimeLocalSelect = Element\DateTimeSelect::class; // maps to select
    case Email               = Element\Email::class;
    case Fieldset            = Fieldset::class;
    case File                = Element\File::class;
    case Image               = Element\Image::class;
    case Hidden              = Element\Hidden::class;
    case Month               = Element\Month::class;
    case MonthSelect         = Element\MonthSelect::class; // maps to select
    case Multicheckbox       = Element\MultiCheckbox::class;
    case Number              = Element\Number::class;
    case Password            = Element\Password::class;
    case Radio               = Element\Radio::class;
    case Range               = Element\Range::class;
    case Reset               = self::ResetAlias; // laminas does not provide a concrete element class for reset
    case Search              = Element\Search::class;
    case Select              = Element\Select::class;
    case Submit              = Element\Submit::class;
    case Tel                 = Element\Tel::class;
    case Text                = Element\Text::class;
    case Textarea            = Element\Textarea::class;
    case Time                = Element\Time::class;
    case Url                 = Element\Url::class;
    case Week                = Element\Week::class;
}
