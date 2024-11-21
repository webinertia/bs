<?php

declare(strict_types=1);

namespace Limatus\View\Helper\Service;

use Laminas\Escaper\Escaper;
use Laminas\View\HtmlAttributesSet;
use Limatus\View\Helper\HtmlTag;
use Psr\Container\ContainerInterface;

final class HtmlTagFactory
{
    public function __invoke(ContainerInterface $container): HtmlTag
    {
        $escaper = $container->has(Escaper::class)
            ? $container->get(Escaper::class)
            : new Escaper();
        return new HtmlTag(new HtmlAttributesSet($escaper));
    }
}
