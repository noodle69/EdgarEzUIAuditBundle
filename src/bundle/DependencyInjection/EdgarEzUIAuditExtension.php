<?php

namespace Edgar\EzUIAuditBundle\DependencyInjection;

use Edgar\EzUIAudit\Audit\AuditInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EdgarEzUIAuditExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $container->registerForAutoconfiguration(AuditInterface::class)
            ->addTag('edgar.audit')
        ;

        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('assetic', array('bundles' => array('EdgarEzUIAuditBundle')));
    }
}
