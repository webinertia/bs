<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap;

use Limatus\VendorInterface;

enum OptionKey: string
{
    case ColumnKey = 'column';
    case RowKey    = 'row';
    case VendorKey = VendorInterface::class;

    public function keyValue()
    {
        return match($this) {
            self::ColumnKey => Style\Column::Col->value,
            self::RowKey    => Style\Row::Row->value,
        };
    }
}
