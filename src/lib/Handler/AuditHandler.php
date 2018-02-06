<?php

namespace Edgar\EzUIAudit\Handler;

use Edgar\EzUIAudit\Audit\AuditInterface;

class AuditHandler
{
    /** @var AuditInterface[] * */
    private $audits = [];

    public function addAudit(AuditInterface $audit)
    {
        $classInfos = explode('\\', get_class($audit));
        $className = str_replace('Audit', '', $classInfos[count($classInfos) - 1]);
        $className = preg_replace('/(?<!^)([A-Z])/', ' \\1', $className);
        $classGroup = $classInfos[count($classInfos) - 2];
        $classGroup = preg_replace('/(?<!^)([A-Z])/', ' \\1', $classGroup);

        if (!isset($this->audits[$classGroup])) {
            $this->audits[$classGroup] = [];
        }

        $this->audits[$classGroup][$className] = $audit;
    }

    public function getAudits(): array
    {
        return $this->audits;
    }

    public function getAuditsData(): array
    {
    }
}
