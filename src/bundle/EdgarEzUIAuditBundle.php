<?php

namespace Edgar\EzUIAuditBundle;

use Edgar\EzUIAuditBundle\DependencyInjection\Compiler\AuditPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EdgarEzUIAuditBundle extends  Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AuditPass());
    }
}
