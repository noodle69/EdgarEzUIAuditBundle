<?php

namespace Edgar\EzUIAuditBundle\Service;

use Edgar\EzUIAudit\Handler\AuditHandler;

class AuditService
{
    /** @var AuditHandler  */
    protected $auditHandler;

    public function __construct(
        AuditHandler $auditHandler
    ) {
        $this->auditHandler = $auditHandler;
    }

    public function loadAuditTypeGroups(): array
    {
        $audits = $this->auditHandler->getAudits();
        $auditGroups = array_keys($audits);
        ksort($auditGroups);
        return $auditGroups;
    }

    public function loadAuditTypes(string $auditTypeGroup): array
    {
        $audits = $this->auditHandler->getAudits();
        return $audits[$auditTypeGroup];
    }
}
