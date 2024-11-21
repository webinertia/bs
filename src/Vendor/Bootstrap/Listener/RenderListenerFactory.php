<?php

declare(strict_types=1);

namespace Limatus\Vendor\Bootstrap\Listener;

use Laminas\View\Helper\HtmlAttributes;
use Laminas\View\HelperPluginManager;
use Limatus\ConfigProvider;
use Limatus\VendorInterface;
use Psr\Container\ContainerInterface;
use Webinertia\Filter\DelimitedString;

final class RenderListenerFactory
{
    public function __invoke(ContainerInterface $container): RenderListener
    {
        $attribHelper = ($container->get(HelperPluginManager::class))->get(HtmlAttributes::class);
        return new RenderListener(
            $container->get(VendorInterface::class),
            new DelimitedString(['start' => '\[', 'end' => '\]']),
            $container->get('config')['view_helper_config'][ConfigProvider::class]
        );
    }
}
