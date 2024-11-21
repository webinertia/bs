<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap;

use Limatus\Vendor\InputType;

use function is_string;

trait CssProviderTrait
{
    public function getClass(InputType|string $inputType): string
    {
        if (is_string($inputType)) {
            $inputType = InputType::tryFrom($inputType);
        }
        return match($inputType) {
            InputType::Text,
            InputType::Image,
            InputType::Password,
            InputType::Date,
            InputType::DateTime,
            InputType::DateTimeLocal,
            InputType::Email,
            InputType::Month,
            InputType::Number,
            InputType::Range,
            InputType::Search,
            InputType::Tel,
            InputType::Time,
            InputType::Url,
            InputType::Week,
            InputType::Textarea => Style\Base::FormControl->value,
            InputType::Button,
            InputType::Reset,
            InputType::Submit   => Style\Base::Button->value,
            InputType::Checkbox,
            InputType::Radio    => Style\Base::FormCheckInput->value,
            InputType::File     => Style\Base::FormFile->value,
            InputType::DateTimeLocalSelect,
            InputType::MonthSelect,
            InputType::Select   => Style\Base::FormSelect->value,
            default => throw new \Exception('Unknown InputType')
        };
    }
}
