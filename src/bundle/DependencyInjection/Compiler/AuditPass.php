<?php

namespace Edgar\EzUIAuditBundle\DependencyInjection\Compiler;

use Edgar\EzUIAudit\Handler\AuditHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AuditPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(AuditHandler::class)) {
            return;
        }

        $definition = $container->findDefinition(AuditHandler::class);

        $taggedServices = $container->findTaggedServiceIds('edgar.audit');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addAudit', [
                    new Reference($id),
                ]);
            }
        }
    }
}
