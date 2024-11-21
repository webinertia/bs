<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap;

use Laminas\View\HelperPluginManager;
use Limatus\View\Helper\HtmlTag;
use Psr\Container\ContainerInterface;

final class BootstrapFactory
{
    public function __invoke(ContainerInterface $container): Bootstrap
    {
        return new Bootstrap(
            ($container->get(HelperPluginManager::class))->get(HtmlTag::class)
        );
    }
}
