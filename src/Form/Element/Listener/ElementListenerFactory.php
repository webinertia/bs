<?php

declare(strict_types=1);

namespace Limatus\Form\Element\Listener;

use Laminas\View\HelperPluginManager;
use Laminas\View\Helper\HtmlAttributes;
use Psr\Container\ContainerInterface;

final class ElementListenerFactory
{
    public function __invoke(ContainerInterface $container): ElementListener
    {
        $manager = $container->get(HelperPluginManager::class);
        return new ElementListener(($manager->get(HtmlAttributes::class))());
    }
}
