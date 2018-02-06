<?php

namespace Edgar\EzUIAuditBundle;

use Edgar\EzUIAuditBundle\DependencyInjection\Compiler\AuditPass;
use Edgar\EzUIAuditBundle\DependencyInjection\Security\PolicyProvider\UIAuditPolicyProvider;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EdgarEzUIAuditBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AuditPass());

        /** @var EzPublishCoreExtension $eZExtension */
        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addPolicyProvider(new UIAuditPolicyProvider($this->getPath()));
    }
}
