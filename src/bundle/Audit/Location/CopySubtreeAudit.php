<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class CopySubtreeAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\CopySubtreeSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'subtreeId' => $signal->subtreeId,
            'targetNewSubtreeId' => $signal->targetNewSubtreeId,
            'targetParentLocationId' => $signal->targetParentLocationId,
        ];

        $this->auditService->log($this);
    }
}
