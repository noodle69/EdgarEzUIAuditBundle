<?php

namespace Edgar\EzUIAudit\Audit;

use Edgar\EzUIAuditBundle\Service\AuditService;
use eZ\Publish\Core\SignalSlot\Slot;

abstract class AbstractAudit extends Slot implements AuditInterface
{
    /** @var AuditService */
    protected $auditService;

    /** @var array */
    protected $infos = [];

    /**
     * AbstractAudit constructor.
     *
     * @param AuditService $auditService
     */
    public function __construct(
        AuditService $auditService
    ) {
        $this->auditService = $auditService;
    }

    /**
     * Get audit identifier.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        $classInfos = explode('\\', get_class($this));

        return $classInfos[count($classInfos) - 2] . '/' . $classInfos[count($classInfos) - 1];
    }

    /**
     * Get audit name.
     *
     * @return string
     */
    public function getName(): string
    {
        $classInfos = explode('\\', get_class($this));
        $className = str_replace('Audit', '', $classInfos[count($classInfos) - 1]);

        return preg_replace('/(?<!^)([A-Z])/', ' \\1', $className);
    }

    /**
     * Get audit group.
     *
     * @return string
     */
    public function getGroup(): string
    {
        $classInfos = explode('\\', get_class($this));
        $classGroup = $classInfos[count($classInfos) - 2];

        return preg_replace('/(?<!^)([A-Z])/', ' \\1', $classGroup);
    }

    public function getInfos(): array
    {
        return $this->infos;
    }
}
