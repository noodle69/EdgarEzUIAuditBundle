<?php

namespace Edgar\EzUIAuditBundle\DependencyInjection\Security\PolicyProvider;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Security\PolicyProvider\YamlPolicyProvider;

class UIAuditPolicyProvider extends YamlPolicyProvider
{
    /** @var string $path bundle path */
    protected $path;

    /**
     * UIAuditPolicyProvider constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return [$this->path . '/Resources/config/policies.yml'];
    }
}
