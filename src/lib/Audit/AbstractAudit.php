<?php

namespace Edgar\EzUIAudit\Audit;

use eZ\Publish\Core\SignalSlot\Slot;

abstract class AbstractAudit extends Slot implements AuditInterface
{
    public function getIdentifier(): string
    {
        $classInfos = explode('\\', get_class($this));
        return $classInfos[count($classInfos) - 1];
    }

    public function getName(): string
    {
        $classInfos = explode('\\', get_class($this));
        return str_replace('Audit', '', $classInfos[count($classInfos) - 1]);
    }
}
