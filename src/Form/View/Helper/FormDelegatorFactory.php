<?php

declare(strict_types=1);

namespace Limatus\Form\View\Helper;

use Laminas\EventManager\EventManagerInterface;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Limatus\Form\RenderListenerInterface;
use Psr\Container\ContainerInterface;

class FormDelegatorFactory implements DelegatorFactoryInterface
{
    /** @inheritDoc */
    public function __invoke(
        ContainerInterface $container,
        $name,
        callable $callback,
        ?array $options = null
    ): FormDelegator {
        $em        = $container->get(EventManagerInterface::class);
        $delegator = new FormDelegator();
        $delegator->setEventManager($em);
        $listener = $container->get(RenderListenerInterface::class);
        $listener->attach($em, 1000);
        return $delegator;
    }
}
