<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class MoveSubtreeAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\MoveSubtreeSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'locationId' => $signal->locationId,
            'newParentLocationId' => $signal->newParentLocationId,
            'oldParentLocationId' => $signal->oldParentLocationId,
        ];

        $this->auditService->log($this);
    }
}
