<?php

declare(strict_types=1);

namespace Limatus\Form\View\Helper;

use Laminas\EventManager\EventManagerInterface;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Limatus\Form\RenderListenerInterface;
use Psr\Container\ContainerInterface;

final class FormSelectDelegatorFactory implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, ?array $options = null): FormSelectDelegator
    {
        $em        = $container->get(EventManagerInterface::class);
        $delegator = new FormSelectDelegator($callback());
        $delegator->setEventManager($em);
        $listener = $container->get(RenderListenerInterface::class);
        $listener->attach($em);
        return $delegator;
    }
}
