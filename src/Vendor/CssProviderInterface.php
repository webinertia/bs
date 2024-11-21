<?php

declare(strict_types=1);

namespace Limatus\Vendor;

interface CssProviderInterface
{
    public function getClass(): string;
    public function getStyle(): string;
}
